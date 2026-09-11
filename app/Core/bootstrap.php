<?php
declare(strict_types=1);

/**
 * Boots the application: loads the core services, the models and the view
 * layouts, in that order. Everything here only defines functions, so requiring
 * this file never produces output.
 *
 * The code is function-based rather than class-based, so there is nothing for
 * an autoloader to hook into -- the files are listed explicitly instead.
 */

require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/html.php';
require_once __DIR__ . '/uploads.php';
require_once __DIR__ . '/view.php';
require_once __DIR__ . '/router.php';

require_once base_path('app/Models/SiteContent.php');
require_once base_path('app/Models/Entry.php');
require_once base_path('app/Models/Message.php');
require_once base_path('app/Models/AdminUser.php');

require_once base_path('app/Views/layouts/site.php');
require_once base_path('app/Views/layouts/admin.php');
