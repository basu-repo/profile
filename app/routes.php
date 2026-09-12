<?php
declare(strict_types=1);

/**
 * The URL map. "METHOD /path" => handler, with {name} segments passed to the
 * handler as named parameters. Several verbs share one entry with "GET|POST"
 * where a screen renders a form and also handles its own submission.
 *
 * Old .php addresses are redirected onto these paths by .htaccess, so nothing
 * that was bookmarked or indexed breaks.
 */

require_once base_path('app/Controllers/HomeController.php');
require_once base_path('app/Controllers/ContactFormController.php');
require_once base_path('app/Controllers/Admin/AuthController.php');
require_once base_path('app/Controllers/Admin/DashboardController.php');
require_once base_path('app/Controllers/Admin/MessageController.php');
require_once base_path('app/Controllers/Admin/ContentController.php');

return [
    // ------------------------------------------------------------- public
    'GET /' => static fn(): mixed => home_index(),
    'GET|POST /contact/submit' => static fn(): mixed => contact_submit(),
    // Browsers holding a cached copy of the old script.js still post here.
    // It is a route rather than a rewrite because an internal rewrite leaves
    // REQUEST_URI pointing at the original address, which is what the router
    // matches on -- so the rewrite never reached this handler.
    'GET|POST /submit-form.php' => static fn(): mixed => contact_submit(),

    // -------------------------------------------------------------- admin
    'GET /admin' => static fn(): mixed => admin_index(),
    'GET|POST /admin/login' => static fn(): mixed => admin_login_page(),
    'GET /admin/logout' => static fn(): mixed => admin_logout_page(),
    'GET|POST /admin/setup' => static fn(): mixed => admin_setup_page(),
    'GET /admin/dashboard' => static fn(): mixed => admin_dashboard(),

    'GET|POST /admin/messages' => static fn(): mixed => admin_messages_index(),
    'GET|POST /admin/messages/{id}' => static fn(array $p): mixed => admin_messages_show($p),

    'GET|POST /admin/content' => static fn(): mixed => admin_content_index(),
];
