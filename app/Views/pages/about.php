<?php
/**
 * @var array $aboutParagraphs
 * @var array $aboutPageSkills
 */
layout_start('About', 'about');
?>
<h1><?= h(site_content_get('about_heading')) ?></h1>
<div class="about-section">
    <h2><?= h(site_content_get('about_intro_heading')) ?></h2>
    <?php foreach ($aboutParagraphs as $paragraph): ?>
        <div class="rich-text-content about-paragraph"><?= app_render_wysiwyg_html((string)$paragraph) ?></div>
    <?php endforeach; ?>

    <h3><?= h(site_content_get('about_page_skills_heading')) ?></h3>
    <ul class="skills-list">
        <?php foreach ($aboutPageSkills as $item): ?>
            <li>
                <strong><?= h((string)($item['title'] ?? '')) ?>:</strong>
                <div class="rich-text-content rich-text-compact"><?= app_render_wysiwyg_html((string)($item['description'] ?? '')) ?></div>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
<?php
layout_end();
