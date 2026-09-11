<?php
/**
 * @var string $nowText
 * @var string $nowUpdated
 * @var string $metaDescription
 */
layout_start('Now', 'now', $metaDescription);
?>
<h1><?= h(site_content_get('now_page_heading')) ?></h1>
<?php if (site_content_get('now_page_intro') !== ''): ?>
    <p class="entry-card-abstract"><?= h(site_content_get('now_page_intro')) ?></p>
<?php endif; ?>

<?php if (trim($nowText) === ''): ?>
    <p class="entry-empty-note">Nothing here yet.</p>
<?php else: ?>
    <div class="rich-text-content"><?= app_render_wysiwyg_html($nowText) ?></div>
<?php endif; ?>

<?php if (trim($nowUpdated) !== ''): ?>
    <p class="now-updated-line">Last updated <?= h($nowUpdated) ?></p>
<?php endif; ?>
<?php
layout_end();
