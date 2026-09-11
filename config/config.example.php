<?php
declare(strict_types=1);

/**
 * Copy this file to config/config.php and fill in the real values. The real
 * file is git-ignored and blocked from the web, so credentials never leave the
 * machine they belong to.
 */

return [
    'db' => [
        'host' => '127.0.0.1',
        'port' => 3306,
        'database' => 'my_profile',
        'username' => 'CHANGE_ME',
        'password' => 'CHANGE_ME',
        'charset' => 'utf8mb4',
    ],
    'security' => [
        'max_requests' => 5,
        'window_seconds' => 900,
        'min_submit_ms' => 3000,
        'max_submit_age_ms' => 7200000,
    ],
];
