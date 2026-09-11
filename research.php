<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/layout.php';
require_once __DIR__ . '/includes/entries.php';

$slug = trim((string)($_GET['slug'] ?? ''));

// ---------------------------------------------------------------- detail view
if ($slug !== '') {
    $entry = null;
    try {
        $entry = entry_find_by_slug($slug);
    } catch (Throwable $exception) {
        $entry = null;
    }

    if ($entry === null) {
        http_response_code(404);
        layout_start('Not found', 'research');
        ?>
        <h1>Not found</h1>
        <p class="entry-empty-note">There is no research entry at that address.</p>
        <p><a href="/research">See everything under Research</a></p>
        <?php
        layout_end();
        exit;
    }

    $officialUrl = trim((string)($entry['official_url'] ?? ''));
    $abstract = trim((string)($entry['abstract'] ?? ''));
    $body = trim((string)($entry['body'] ?? ''));

    $metaBits = array_values(array_filter([
        entry_type_label((string)$entry['type']),
        trim((string)($entry['venue'] ?? '')),
        (string)($entry['year'] ?? ''),
    ], static fn(string $bit): bool => $bit !== ''));

    layout_start((string)$entry['title'], 'research', $abstract !== '' ? excerpt($abstract, 160) : '');
    ?>
    <article>
        <header>
            <div class="entry-detail-meta"><?= h(implode(' · ', $metaBits)) ?></div>
            <h1><?= h((string)$entry['title']) ?></h1>
            <?php if (trim((string)($entry['subtitle'] ?? '')) !== ''): ?>
                <p class="company"><?= h((string)$entry['subtitle']) ?></p>
            <?php endif; ?>
            <?php if (trim((string)($entry['authors'] ?? '')) !== ''): ?>
                <p><?= h((string)$entry['authors']) ?></p>
            <?php endif; ?>
            <?php if ($officialUrl !== ''): ?>
                <a class="entry-official-link" href="<?= h($officialUrl) ?>" rel="noopener noreferrer" target="_blank"><?= h(layout_official_label($officialUrl)) ?> →</a>
            <?php endif; ?>
        </header>

        <?php if ($abstract !== ''): ?>
            <div class="entry-detail-abstract"><?= nl2br(h($abstract), false) ?></div>
        <?php endif; ?>

        <?php if ($body !== ''): ?>
            <div class="rich-text-content"><?= app_render_wysiwyg_html($body) ?></div>
        <?php endif; ?>

        <a class="entry-back-link" href="/research">← Research</a>
    </article>
    <?php
    layout_end();
    exit;
}

// ------------------------------------------------------------------ list view
$entries = [];
$loadFailed = false;
try {
    $entries = entry_all(null, true);
} catch (Throwable $exception) {
    $loadFailed = true;
}

// Keep the declared type order (paper, thesis, project) rather than whatever
// the rows happen to come back in.
$grouped = [];
foreach (array_keys(entry_types()) as $typeKey) {
    $grouped[$typeKey] = [];
}
foreach ($entries as $entry) {
    $grouped[(string)$entry['type']][] = $entry;
}

layout_start('Research', 'research', site_content_get('research_page_intro'));
?>
<h1><?= h(site_content_get('research_page_heading')) ?></h1>
<?php if (site_content_get('research_page_intro') !== ''): ?>
    <p class="entry-card-abstract"><?= h(site_content_get('research_page_intro')) ?></p>
<?php endif; ?>

<?php if ($loadFailed || $entries === []): ?>
    <p class="entry-empty-note">Nothing published here yet.</p>
<?php else: ?>
    <?php foreach ($grouped as $typeKey => $items): ?>
        <?php if ($items === []) { continue; } ?>
        <section class="entry-group">
            <h2 class="entry-group-heading"><?= h(entry_type_label($typeKey)) ?></h2>
            <?php foreach ($items as $entry): ?>
                <?php
                $metaBits = array_values(array_filter([
                    trim((string)($entry['venue'] ?? '')),
                    (string)($entry['year'] ?? ''),
                ], static fn(string $bit): bool => $bit !== ''));
                $abstract = trim((string)($entry['abstract'] ?? ''));
                ?>
                <a class="entry-card" href="/research/<?= h((string)$entry['slug']) ?>">
                    <?php if ($metaBits !== []): ?>
                        <span class="entry-card-meta"><?= h(implode(' · ', $metaBits)) ?></span>
                    <?php endif; ?>
                    <span class="entry-card-title"><?= h((string)$entry['title']) ?></span>
                    <?php if ($abstract !== ''): ?>
                        <p class="entry-card-abstract"><?= h(excerpt($abstract, 190)) ?></p>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </section>
    <?php endforeach; ?>
<?php endif; ?>
<?php
layout_end();
