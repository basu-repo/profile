<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

function entry_types(): array
{
    return [
        'paper' => 'Conference paper',
        'thesis' => 'Thesis',
        'project' => 'Research project',
    ];
}

function entry_type_label(string $type): string
{
    return entry_types()[$type] ?? $type;
}

function entry_blank(): array
{
    return [
        'id' => 0,
        'type' => 'paper',
        'slug' => '',
        'title' => '',
        'subtitle' => '',
        'authors' => '',
        'venue' => '',
        'year' => '',
        'official_url' => '',
        'abstract' => '',
        'body' => '',
        'sort_order' => 0,
        'is_published' => 0,
    ];
}

/**
 * URL-safe slug. Transliterates accents so "Malmö" becomes "malmo" rather
 * than being dropped.
 */
function entry_slugify(string $value): string
{
    $slug = trim($value);
    if ($slug === '') {
        return '';
    }

    $converted = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $slug);
    if (is_string($converted) && $converted !== '') {
        $slug = $converted;
    }

    $slug = strtolower($slug);
    $slug = preg_replace('/[^a-z0-9]+/', '-', $slug) ?? $slug;
    $slug = trim($slug, '-');

    return $slug === '' ? '' : substr($slug, 0, 160);
}

/**
 * Appends -2, -3 ... until the slug is free. $ignoreId lets a row keep its own
 * slug while being edited.
 */
function entry_unique_slug(string $slug, int $ignoreId = 0): string
{
    $slug = $slug === '' ? 'entry' : $slug;
    $candidate = $slug;
    $suffix = 1;

    $stmt = app_pdo()->prepare('SELECT id FROM entries WHERE slug = :slug AND id <> :id LIMIT 1');

    while (true) {
        $stmt->execute(['slug' => $candidate, 'id' => $ignoreId]);
        if (!$stmt->fetch()) {
            return $candidate;
        }

        $suffix++;
        $tail = '-' . $suffix;
        $candidate = substr($slug, 0, 160 - strlen($tail)) . $tail;
    }
}

function entry_all(?string $type = null, bool $publishedOnly = false): array
{
    $sql = 'SELECT id, type, slug, title, subtitle, authors, venue, year, official_url,
                   abstract, body, sort_order, is_published, created_at, updated_at
            FROM entries';
    $where = [];
    $params = [];

    if ($type !== null && array_key_exists($type, entry_types())) {
        $where[] = 'type = :type';
        $params['type'] = $type;
    }

    if ($publishedOnly) {
        $where[] = 'is_published = 1';
    }

    if ($where !== []) {
        $sql .= ' WHERE ' . implode(' AND ', $where);
    }

    $sql .= ' ORDER BY sort_order ASC, year DESC, id DESC';

    $stmt = app_pdo()->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll();
}

function entry_find(int $id): ?array
{
    if ($id <= 0) {
        return null;
    }

    $stmt = app_pdo()->prepare('SELECT * FROM entries WHERE id = :id LIMIT 1');
    $stmt->execute(['id' => $id]);
    $row = $stmt->fetch();

    return $row ?: null;
}

function entry_find_by_slug(string $slug, bool $publishedOnly = true): ?array
{
    if (trim($slug) === '') {
        return null;
    }

    $sql = 'SELECT * FROM entries WHERE slug = :slug';
    if ($publishedOnly) {
        $sql .= ' AND is_published = 1';
    }
    $sql .= ' LIMIT 1';

    $stmt = app_pdo()->prepare($sql);
    $stmt->execute(['slug' => $slug]);
    $row = $stmt->fetch();

    return $row ?: null;
}

/**
 * Validates and writes one entry. Returns [id, errors]; a non-empty errors
 * array means nothing was written.
 */
function entry_save(array $input): array
{
    $id = (int)($input['id'] ?? 0);
    $errors = [];

    $type = (string)($input['type'] ?? 'paper');
    if (!array_key_exists($type, entry_types())) {
        $errors[] = 'Choose a valid entry type.';
    }

    $title = trim((string)($input['title'] ?? ''));
    if ($title === '') {
        $errors[] = 'Title is required.';
    } elseif (mb_strlen($title) > 255) {
        $errors[] = 'Title must be 255 characters or fewer.';
    }

    $yearRaw = trim((string)($input['year'] ?? ''));
    $year = null;
    if ($yearRaw !== '') {
        $parsed = filter_var($yearRaw, FILTER_VALIDATE_INT);
        if ($parsed === false || $parsed < 1900 || $parsed > 2100) {
            $errors[] = 'Year must be between 1900 and 2100.';
        } else {
            $year = $parsed;
        }
    }

    $url = trim((string)($input['official_url'] ?? ''));
    if ($url !== '' && !preg_match('#^https?://#i', $url)) {
        $errors[] = 'Official URL must start with http:// or https://';
    }
    if (mb_strlen($url) > 500) {
        $errors[] = 'Official URL is too long.';
    }

    if ($errors !== []) {
        return ['id' => $id, 'errors' => $errors];
    }

    $slug = entry_slugify((string)($input['slug'] ?? ''));
    if ($slug === '') {
        $slug = entry_slugify($title);
    }
    $slug = entry_unique_slug($slug, $id);

    $params = [
        'type' => $type,
        'slug' => $slug,
        'title' => $title,
        'subtitle' => trim((string)($input['subtitle'] ?? '')) ?: null,
        'authors' => trim((string)($input['authors'] ?? '')) ?: null,
        'venue' => trim((string)($input['venue'] ?? '')) ?: null,
        'year' => $year,
        'official_url' => $url ?: null,
        'abstract' => trim((string)($input['abstract'] ?? '')) ?: null,
        'body' => app_sanitize_wysiwyg_html((string)($input['body'] ?? '')) ?: null,
        'sort_order' => (int)($input['sort_order'] ?? 0),
        'is_published' => ((string)($input['is_published'] ?? '0')) === '1' ? 1 : 0,
    ];

    if ($id > 0) {
        $params['id'] = $id;
        $stmt = app_pdo()->prepare(
            'UPDATE entries SET type = :type, slug = :slug, title = :title, subtitle = :subtitle,
                    authors = :authors, venue = :venue, year = :year, official_url = :official_url,
                    abstract = :abstract, body = :body, sort_order = :sort_order,
                    is_published = :is_published
             WHERE id = :id'
        );
        $stmt->execute($params);

        return ['id' => $id, 'errors' => []];
    }

    $stmt = app_pdo()->prepare(
        'INSERT INTO entries (type, slug, title, subtitle, authors, venue, year, official_url,
                              abstract, body, sort_order, is_published)
         VALUES (:type, :slug, :title, :subtitle, :authors, :venue, :year, :official_url,
                 :abstract, :body, :sort_order, :is_published)'
    );
    $stmt->execute($params);

    return ['id' => (int)app_pdo()->lastInsertId(), 'errors' => []];
}

function entry_delete(int $id): bool
{
    if ($id <= 0) {
        return false;
    }

    $stmt = app_pdo()->prepare('DELETE FROM entries WHERE id = :id');
    $stmt->execute(['id' => $id]);

    return $stmt->rowCount() > 0;
}

function entry_set_published(int $id, bool $isPublished): bool
{
    if ($id <= 0) {
        return false;
    }

    $stmt = app_pdo()->prepare('UPDATE entries SET is_published = :is_published WHERE id = :id');
    $stmt->execute(['is_published' => $isPublished ? 1 : 0, 'id' => $id]);

    return $stmt->rowCount() > 0;
}
