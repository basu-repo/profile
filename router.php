<?php
declare(strict_types=1);

/**
 * Router for the PHP built-in server only:
 *   php -S localhost:8000 router.php
 * Apache uses the equivalent rules in .htaccess, which the built-in server
 * ignores. Returning false lets the server serve a real file from disk.
 */

$path = parse_url((string)($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH) ?: '/';

if (preg_match('#^/research/?$#', $path)) {
    require __DIR__ . '/research.php';
    return true;
}

if (preg_match('#^/research/([A-Za-z0-9_-]+)/?$#', $path, $matches)) {
    $_GET['slug'] = $matches[1];
    require __DIR__ . '/research.php';
    return true;
}

if (preg_match('#^/now/?$#', $path)) {
    require __DIR__ . '/now.php';
    return true;
}

if ($path === '/') {
    require __DIR__ . '/index.php';
    return true;
}

$file = realpath(__DIR__ . $path);
if ($file !== false && is_file($file) && str_starts_with($file, __DIR__)) {
    return false;
}

http_response_code(404);
echo 'Not found';
return true;
