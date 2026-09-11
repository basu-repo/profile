<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/site_content.php';

$aboutParagraphs = site_content_get_array('about_paragraphs');
$aboutPageSkills = site_content_get_array('about_page_skills');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About - <?= h(site_content_get('site_name')) ?></title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <div class="logo"></div>
            <ul class="nav-menu">
                <li><a href="about.php" class="active">About</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </div>
    </nav>

    <section class="page-content">
        <div class="container">
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
        </div>
    </section>

    <footer>
        <div class="container">
            <p><?= h(site_content_get('footer_text')) ?></p>
            <p><a href="contact.php">Contact</a></p>
        </div>
    </footer>

    <script src="js/script.js"></script>
</body>
</html>
