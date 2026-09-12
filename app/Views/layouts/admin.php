<?php
declare(strict_types=1);

/**
 * Shared chrome for the admin area.
 *
 * The rich-text editor is only pulled in by the content editor, the one
 * screen that edits prose. The header records that choice so the footer emits the matching
 * script tag -- it used to guess from the running script's filename, which no
 * longer says anything now that every request enters through index.php.
 */
function admin_layout_uses_editor(?bool $set = null): bool
{
    static $usesEditor = false;

    if ($set !== null) {
        $usesEditor = $set;
    }

    return $usesEditor;
}

function admin_render_header(string $title, string $active = '', bool $withEditor = false): void
{
    admin_layout_uses_editor($withEditor);

    $flash = admin_get_flash();
    $user = admin_user();
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= h($title) ?> - Admin</title>
    <link rel="stylesheet" href="/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <?php if ($withEditor): ?>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css">
    <?php endif; ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <nav class="navbar admin-navbar">
        <div class="container">
            <div class="logo"></div>
            <?php if ($user): ?>
                <ul class="nav-menu">
                    <li><a href="/admin/dashboard" class="<?= $active === 'dashboard' ? 'active' : '' ?>">Dashboard</a></li>
                    <li><a href="/admin/messages" class="<?= $active === 'messages' ? 'active' : '' ?>">Messages</a></li>
                    <li><a href="/admin/content" class="<?= $active === 'content' ? 'active' : '' ?>">Content</a></li>
                    <li><a href="/" target="_blank" rel="noopener noreferrer">View Site</a></li>
                    <li><a href="/admin/logout">Logout</a></li>
                </ul>
            <?php elseif (admin_count_users() === 0): ?>
                <ul class="nav-menu">
                    <li><a href="/admin/setup" class="<?= $active === 'setup' ? 'active' : '' ?>">Register</a></li>
                </ul>
            <?php else: ?>
                <ul class="nav-menu">
                    <li><a href="/admin/login" class="<?= $active === 'login' ? 'active' : '' ?>">Login</a></li>
                </ul>
            <?php endif; ?>
        </div>
    </nav>

    <section class="page-content admin-page">
        <div class="container">
            <div class="admin-header-row">
                <div>
                    <h1><?= h($title) ?></h1>
                    <?php if ($user): ?>
                        <p class="admin-subtitle">Signed in as <?= h($user['full_name']) ?> (<?= h($user['email']) ?>)</p>
                    <?php endif; ?>
                </div>
            </div>

            <?php if ($flash): ?>
                <div class="admin-alert <?= h($flash['type']) ?>"><?= h($flash['message']) ?></div>
            <?php endif; ?>
    <?php
}

function admin_render_footer(): void
{
    ?>
        </div>
    </section>
    <?php if (admin_layout_uses_editor()): ?>
        <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
    <?php endif; ?>
    <script src="/js/script.js?v=20260911a"></script>
</body>
</html>
    <?php
}
