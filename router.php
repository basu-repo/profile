<?php
declare(strict_types=1);

/**
 * Router for the PHP built-in server only:
 *
 *   php -S localhost:8000 router.php
 *
 * Apache uses the equivalent rules in .htaccess, which the built-in server
 * ignores. Returning false lets the server serve a real file (CSS, JS, images)
 * straight from disk; everything else goes through the front controller.
 */

$path = parse_url((string)($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH) ?: '/';

// Directories that hold code or data, never anything the browser may fetch.
if (preg_match('#^/(app|config|database|storage|bin|legacy)(/|$)#', $path)) {
    http_response_code(403);
    echo 'Forbidden';
    return true;
}

$file = realpath(__DIR__ . $path);
if ($path !== '/' && $file !== false && is_file($file) && str_starts_with($file, __DIR__ . DIRECTORY_SEPARATOR)) {
    return false;
}

require __DIR__ . '/index.php';
return true;
