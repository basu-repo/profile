<?php
declare(strict_types=1);

/**
 * The research index and the per-entry pages behind /research/<slug>.
 */
function research_index(): void
{
    $entries = [];
    $loadFailed = false;

    try {
        $entries = entry_all(null, true);
    } catch (Throwable $exception) {
        $loadFailed = true;
    }

    // Keep the declared type order (paper, thesis, project) rather than
    // whatever the rows happen to come back in.
    $grouped = [];
    foreach (array_keys(entry_types()) as $typeKey) {
        $grouped[$typeKey] = [];
    }
    foreach ($entries as $entry) {
        $grouped[(string)$entry['type']][] = $entry;
    }

    view('pages/research/index', [
        'grouped' => $grouped,
        'isEmpty' => $loadFailed || $entries === [],
    ]);
}

function research_show(array $params): void
{
    $slug = trim((string)($params['slug'] ?? ''));
    $entry = null;

    try {
        $entry = entry_find_by_slug($slug);
    } catch (Throwable $exception) {
        $entry = null;
    }

    if ($entry === null) {
        http_response_code(404);
        view('pages/research/not-found');
        return;
    }

    $abstract = trim((string)($entry['abstract'] ?? ''));

    $metaBits = array_values(array_filter([
        entry_type_label((string)$entry['type']),
        trim((string)($entry['venue'] ?? '')),
        (string)($entry['year'] ?? ''),
    ], static fn(string $bit): bool => $bit !== ''));

    view('pages/research/show', [
        'entry' => $entry,
        'abstract' => $abstract,
        'body' => trim((string)($entry['body'] ?? '')),
        'officialUrl' => trim((string)($entry['official_url'] ?? '')),
        'metaBits' => $metaBits,
    ]);
}
