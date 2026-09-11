<?php
declare(strict_types=1);

/**
 * Configuration loading and the single shared PDO connection.
 */

function app_config(): array
{
    static $config;

    if ($config === null) {
        $configFile = base_path('config/config.php');
        if (!file_exists($configFile)) {
            throw new RuntimeException('Missing config/config.php');
        }

        $config = require $configFile;
    }

    return $config;
}

function app_pdo(): PDO
{
    static $pdo;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $db = app_config()['db'] ?? [];
    $dsn = sprintf(
        'mysql:host=%s;port=%d;dbname=%s;charset=%s',
        (string)($db['host'] ?? '127.0.0.1'),
        (int)($db['port'] ?? 3306),
        (string)($db['database'] ?? ''),
        (string)($db['charset'] ?? 'utf8mb4')
    );

    $pdo = new PDO(
        $dsn,
        (string)($db['username'] ?? ''),
        (string)($db['password'] ?? ''),
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );

    return $pdo;
}
