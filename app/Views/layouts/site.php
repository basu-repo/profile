<?php
declare(strict_types=1);

/**
 * Shared chrome for every public page: navbar, page-content wrapper and
 * footer, matching the classes in css/styles.css.
 */
function layout_start(string $title, string $active = '', string $description = ''): void
{
    $siteName = site_content_get('site_name');
    $fullTitle = $title === '' ? $siteName : $title . ' - ' . $siteName;
    $description = $description !== '' ? $description : site_content_get('meta_description');

    $nav = [
        'home' => ['label' => 'Home', 'href' => '/'],
        'research' => ['label' => 'Research', 'href' => '/research'],
        'now' => ['label' => 'Now', 'href' => '/now'],
        'contact' => ['label' => 'Contact', 'href' => '/contact'],
    ];
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= h($fullTitle) ?></title>
    <meta name="description" content="<?= h($description) ?>">
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?= h($fullTitle) ?>">
    <meta property="og:description" content="<?= h($description) ?>">
    <meta property="og:site_name" content="<?= h($siteName) ?>">
    <meta name="twitter:card" content="summary">
    <link rel="stylesheet" href="/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&family=Dancing+Script:wght@600&display=swap" rel="stylesheet">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <div class="logo"></div>
            <ul class="nav-menu">
                <?php foreach ($nav as $key => $item): ?>
                    <li><a href="<?= h($item['href']) ?>"<?= $active === $key ? ' class="active"' : '' ?>><?= h($item['label']) ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </nav>

    <section class="page-content">
        <div class="container">
    <?php
}

function layout_end(): void
{
    ?>
        </div>
    </section>

    <footer>
        <div class="container">
            <p><?= h(site_content_get('footer_text')) ?></p>
            <p><a href="/">Back to Home</a></p>
        </div>
    </footer>

    <button id="scrollToTop" title="Back to top"><i class="fas fa-arrow-up"></i></button>

    <script src="/js/script.js?v=20260423a"></script>
</body>
</html>
    <?php
}

/**
 * Labels the outbound link with its host, so an entry says "View on
 * ieeexplore.ieee.org" rather than a bare URL.
 */
function layout_official_label(string $url): string
{
    $host = parse_url($url, PHP_URL_HOST);
    if (!is_string($host) || $host === '') {
        return 'View the official record';
    }

    return 'View on ' . preg_replace('/^www\./i', '', $host);
}
