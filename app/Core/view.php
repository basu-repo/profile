<?php
declare(strict_types=1);

/**
 * View rendering. A controller hands a template name (relative to app/Views,
 * without the .php suffix) and an array of data; the data becomes local
 * variables inside the template and nothing else leaks in.
 */
function view(string $template, array $data = []): void
{
    $file = base_path('app/Views/' . trim($template, '/') . '.php');

    if (!is_file($file)) {
        throw new RuntimeException('View not found: ' . $template);
    }

    (static function (string $__file, array $__data): void {
        extract($__data, EXTR_SKIP);
        require $__file;
    })($file, $data);
}

/**
 * Renders a view into a string, for the rare case where markup has to be
 * captured rather than sent straight to the browser.
 */
function view_capture(string $template, array $data = []): string
{
    ob_start();
    view($template, $data);

    return (string)ob_get_clean();
}

/** Sends a JSON response and stops. Used by the contact endpoint. */
function json_response(int $statusCode, array $payload): never
{
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($payload);
    exit;
}
