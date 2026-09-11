<?php
declare(strict_types=1);

/**
 * Deletes the contact messages whose sender asked for them to expire.
 * Intended for cron:
 *
 *   php /path/to/site/bin/cleanup-expired-messages.php
 */

require __DIR__ . '/../app/Core/bootstrap.php';

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    echo 'This script can only be run from the command line.';
    exit;
}

try {
    echo 'Deleted expired messages: ' . message_delete_expired() . PHP_EOL;
} catch (Throwable $exception) {
    fwrite(STDERR, 'Cleanup failed: ' . $exception->getMessage() . PHP_EOL);
    exit(1);
}
