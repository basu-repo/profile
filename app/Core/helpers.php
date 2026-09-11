<?php
declare(strict_types=1);

/**
 * Small helpers every layer is allowed to use: paths, URLs, escaping and
 * redirects.
 */

/** Absolute path to a file or directory inside the project. */
function base_path(string $relative = ''): string
{
    $root = dirname(__DIR__, 2);

    return $relative === '' ? $root : $root . '/' . ltrim($relative, '/');
}

/**
 * Root-relative URL for a stored asset. Content rows keep paths such as
 * "images/profile.png" or "uploads/certificates/x.png", which must resolve the
 * same way from "/", "/about" and "/admin/content".
 */
function asset_url(string $path): string
{
    $path = trim($path);

    if ($path === '' || preg_match('#^(?:https?:)?//#i', $path)) {
        return $path;
    }

    return '/' . ltrim($path, '/');
}

function h(?string $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function redirect(string $location): never
{
    header('Location: ' . $location);
    exit;
}

function excerpt(string $value, int $length = 90): string
{
    $clean = trim(preg_replace('/\s+/u', ' ', $value) ?? $value);

    if (function_exists('mb_strlen') && function_exists('mb_substr')) {
        if (mb_strlen($clean) <= $length) {
            return $clean;
        }

        return rtrim(mb_substr($clean, 0, $length - 3)) . '...';
    }

    if (strlen($clean) <= $length) {
        return $clean;
    }

    return rtrim(substr($clean, 0, $length - 3)) . '...';
}
