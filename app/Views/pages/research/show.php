<?php
/**
 * @var array $entry
 * @var string $abstract
 * @var string $body
 * @var string $officialUrl
 * @var array $metaBits
 */
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
