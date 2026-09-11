<?php
declare(strict_types=1);

function app_config(): array
{
    static $config;

    if ($config === null) {
        $configFile = dirname(__DIR__) . '/config.php';
        if (!file_exists($configFile)) {
            throw new RuntimeException('Missing config.php');
        }

        $config = require $configFile;
    }

    return $config;
}

function app_pdo(): PDO
{
    static $pdo;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $db = app_config()['db'] ?? [];
    $dsn = sprintf(
        'mysql:host=%s;port=%d;dbname=%s;charset=%s',
        (string)($db['host'] ?? '127.0.0.1'),
        (int)($db['port'] ?? 3306),
        (string)($db['database'] ?? ''),
        (string)($db['charset'] ?? 'utf8mb4')
    );

    $pdo = new PDO(
        $dsn,
        (string)($db['username'] ?? ''),
        (string)($db['password'] ?? ''),
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );

    return $pdo;
}

function h(?string $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function redirect(string $location): never
{
    header('Location: ' . $location);
    exit;
}

function excerpt(string $value, int $length = 90): string
{
    $clean = trim(preg_replace('/\s+/u', ' ', $value) ?? $value);

    if (function_exists('mb_strlen') && function_exists('mb_substr')) {
        if (mb_strlen($clean) <= $length) {
            return $clean;
        }

        return rtrim(mb_substr($clean, 0, $length - 3)) . '...';
    }

    if (strlen($clean) <= $length) {
        return $clean;
    }

    return rtrim(substr($clean, 0, $length - 3)) . '...';
}

function app_store_uploaded_image(array $file, string $subdirectory): ?string
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Image upload failed.');
    }

    $tmpName = (string)($file['tmp_name'] ?? '');
    if ($tmpName === '' || !is_uploaded_file($tmpName)) {
        throw new RuntimeException('Invalid uploaded file.');
    }

    $originalName = (string)($file['name'] ?? 'upload');
    $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    if (!in_array($extension, $allowedExtensions, true)) {
        throw new RuntimeException('Unsupported image type. Use JPG, PNG, GIF, or WEBP.');
    }

    $baseDirectory = dirname(__DIR__) . '/uploads/' . trim($subdirectory, '/');
    if (!is_dir($baseDirectory) && !mkdir($baseDirectory, 0755, true) && !is_dir($baseDirectory)) {
        throw new RuntimeException('Upload directory could not be created.');
    }

    $fileName = date('YmdHis') . '-' . bin2hex(random_bytes(6)) . '.' . $extension;
    $destination = $baseDirectory . '/' . $fileName;

    if (!move_uploaded_file($tmpName, $destination)) {
        throw new RuntimeException('Image upload could not be saved.');
    }

    return 'uploads/' . trim($subdirectory, '/') . '/' . $fileName;
}

/**
 * True when the value carries markup the editor is allowed to produce. Text
 * that merely contains a "<" (for example "<100ms" or "50<x<80") is content,
 * not HTML, and must never be handed to an HTML parser.
 */
function app_html_has_markup(string $value): bool
{
    return (bool)preg_match('/<\/?(?:p|br|strong|b|em|i|u|ul|ol|li|a)\b[^>]*>/i', $value);
}

function app_plain_text_to_html(string $text): string
{
    $paragraphs = preg_split('/\r\n\r\n|\r\r|\n\n/', trim($text)) ?: [];
    $paragraphs = array_values(array_filter(
        array_map('trim', $paragraphs),
        static fn(string $paragraph): bool => $paragraph !== ''
    ));

    if ($paragraphs === []) {
        return '';
    }

    return implode('', array_map(
        static fn(string $paragraph): string => '<p>' . nl2br(h($paragraph), false) . '</p>',
        $paragraphs
    ));
}

/**
 * Allowed tag => allowed attributes. Anything else is unwrapped so its text
 * survives; the tags in app_wysiwyg_is_void_of_content() are dropped whole.
 */
function app_wysiwyg_allowed_tags(): array
{
    return [
        'p' => [], 'br' => [], 'strong' => [], 'b' => [], 'em' => [], 'i' => [],
        'u' => [], 'ul' => [], 'ol' => [], 'li' => [], 'a' => ['href'],
    ];
}

function app_wysiwyg_is_void_of_content(DOMElement $element): bool
{
    return in_array(strtolower($element->nodeName), [
        'script', 'style', 'noscript', 'iframe', 'object', 'embed', 'template', 'svg', 'math',
    ], true);
}

function app_sanitize_wysiwyg_node(DOMNode $node): void
{
    $allowed = app_wysiwyg_allowed_tags();

    foreach (iterator_to_array($node->childNodes) as $child) {
        if ($child instanceof DOMText) {
            continue;
        }

        if (!$child instanceof DOMElement) {
            $child->parentNode->removeChild($child);
            continue;
        }

        if (app_wysiwyg_is_void_of_content($child)) {
            $child->parentNode->removeChild($child);
            continue;
        }

        $tag = strtolower($child->nodeName);

        if (!array_key_exists($tag, $allowed)) {
            app_sanitize_wysiwyg_node($child);
            while ($child->firstChild) {
                $child->parentNode->insertBefore($child->firstChild, $child);
            }
            $child->parentNode->removeChild($child);
            continue;
        }

        foreach (iterator_to_array($child->attributes) as $attribute) {
            if (!in_array(strtolower($attribute->nodeName), $allowed[$tag], true)) {
                $child->removeAttribute($attribute->nodeName);
            }
        }

        if ($tag === 'a') {
            $url = trim($child->getAttribute('href'));
            if ($url === '' || !preg_match('/^(https?:|mailto:|\/)/i', $url)) {
                $child->removeAttribute('href');
            } else {
                $child->setAttribute('rel', 'noopener noreferrer');
                $child->setAttribute('target', '_blank');
            }
        }

        app_sanitize_wysiwyg_node($child);
    }
}

function app_sanitize_wysiwyg_html(string $html): string
{
    $html = trim($html);
    if ($html === '') {
        return '';
    }

    if (!app_html_has_markup($html)) {
        return app_plain_text_to_html($html);
    }

    $document = new DOMDocument();
    $previousState = libxml_use_internal_errors(true);
    $loaded = $document->loadHTML(
        '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"><div>' . $html . '</div>',
        LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
    );
    libxml_clear_errors();
    libxml_use_internal_errors($previousState);

    if (!$loaded) {
        return app_plain_text_to_html($html);
    }

    $root = (new DOMXPath($document))->query('//div')->item(0);
    if (!$root instanceof DOMElement) {
        return app_plain_text_to_html($html);
    }

    app_sanitize_wysiwyg_node($root);

    $sanitized = '';
    foreach ($root->childNodes as $child) {
        $sanitized .= $document->saveHTML($child);
    }

    return trim($sanitized);
}

function app_render_wysiwyg_html(string $html): string
{
    $html = trim($html);
    if ($html === '') {
        return '';
    }

    if (!app_html_has_markup($html)) {
        return app_plain_text_to_html($html);
    }

    return app_sanitize_wysiwyg_html($html);
}

function app_format_wysiwyg_value(mixed $value): string
{
    if (is_array($value)) {
        $items = array_values(array_filter(array_map(
            static fn(mixed $item): string => trim((string)$item),
            $value
        ), static fn(string $item): bool => $item !== ''));

        if ($items === []) {
            return '';
        }

        $html = '<ul>';
        foreach ($items as $item) {
            $html .= '<li>' . h($item) . '</li>';
        }
        $html .= '</ul>';

        return $html;
    }

    return app_render_wysiwyg_html((string)$value);
}

function app_normalize_html_date(string $value): string
{
    $value = trim($value);
    if ($value === '') {
        return '';
    }

    $date = DateTimeImmutable::createFromFormat('Y-m-d', $value);
    $errors = DateTimeImmutable::getLastErrors();
    if (!$date || (($errors['warning_count'] ?? 0) > 0) || (($errors['error_count'] ?? 0) > 0)) {
        return '';
    }

    return $date->format('Y-m-d');
}

function app_extract_date_range_fields(array $item): array
{
    $startDate = app_normalize_html_date((string)($item['start_date'] ?? ''));
    $endDate = app_normalize_html_date((string)($item['end_date'] ?? ''));
    $isCurrent = ((string)($item['is_current'] ?? '0')) === '1';

    if ($startDate !== '' || $endDate !== '' || $isCurrent) {
        return [
            'start_date' => $startDate,
            'end_date' => $isCurrent ? '' : $endDate,
            'is_current' => $isCurrent ? '1' : '0',
        ];
    }

    $legacy = trim((string)($item['date'] ?? ''));
    if ($legacy === '') {
        return [
            'start_date' => '',
            'end_date' => '',
            'is_current' => '0',
        ];
    }

    if (preg_match('/^\s*([A-Za-z]{3,9}\s+\d{4})\s*-\s*(Present|Current|[A-Za-z]{3,9}\s+\d{4})\s*$/i', $legacy, $matches)) {
        $startDate = app_parse_month_year_string($matches[1]);
        $endPart = trim($matches[2]);
        $isCurrent = in_array(strtolower($endPart), ['present', 'current'], true);
        $endDate = $isCurrent ? '' : app_parse_month_year_string($endPart);

        return [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'is_current' => $isCurrent ? '1' : '0',
        ];
    }

    return [
        'start_date' => '',
        'end_date' => '',
        'is_current' => '0',
    ];
}

function app_parse_month_year_string(string $value): string
{
    $value = trim($value);
    if ($value === '') {
        return '';
    }

    $formats = ['M Y', 'F Y'];
    foreach ($formats as $format) {
        $date = DateTimeImmutable::createFromFormat('!'.$format, $value);
        $errors = DateTimeImmutable::getLastErrors();
        if ($date && (($errors['warning_count'] ?? 0) === 0) && (($errors['error_count'] ?? 0) === 0)) {
            return $date->format('Y-m-01');
        }
    }

    return '';
}

function app_format_month_year(string $value): string
{
    $normalized = app_normalize_html_date($value);
    if ($normalized === '') {
        return '';
    }

    $date = new DateTimeImmutable($normalized);
    return $date->format('M Y');
}

function app_format_date_range(array $item): string
{
    $range = app_extract_date_range_fields($item);
    $start = app_format_month_year($range['start_date']);
    $end = $range['is_current'] === '1'
        ? 'Present'
        : app_format_month_year($range['end_date']);

    if ($start !== '' && $end !== '') {
        return $start . ' - ' . $end;
    }

    if ($start !== '') {
        return $start;
    }

    return trim((string)($item['date'] ?? ''));
}
