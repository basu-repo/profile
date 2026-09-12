<?php
declare(strict_types=1);

/**
 * Front controller. Every request the web server cannot satisfy with a real
 * file arrives here, is matched against app/routes.php, and is handed to a
 * controller in app/Controllers.
 */

// The application uses PHP 8.1 syntax, which an older PHP cannot even parse,
// so the failure would otherwise be a blank 500 page. This file stays readable
// by old versions so it can say what is wrong. On cPanel the version is changed
// per domain under "MultiPHP Manager" or "Select PHP Version".
if (PHP_VERSION_ID < 80100) {
    error_log('My_Profile needs PHP 8.1 or newer; this server runs PHP ' . PHP_VERSION . '.');
    http_response_code(503);
    header('Content-Type: text/plain; charset=UTF-8');
    header('Retry-After: 3600');
    echo "This site needs PHP 8.1 or newer and cannot run on this server's PHP version.\n";
    echo "Change the PHP version for this domain in cPanel, then reload the page.\n";
    exit;
}

require __DIR__ . '/app/Core/bootstrap.php';

route_dispatch(require __DIR__ . '/app/routes.php');
