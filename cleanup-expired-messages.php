<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    echo 'This script can only be run from the command line.';
    exit;
}

try {
    $stmt = app_pdo()->prepare(
        'DELETE FROM contact_messages
         WHERE delete_after_at IS NOT NULL
           AND delete_after_at <= NOW()'
    );
    $stmt->execute();
    echo 'Deleted expired messages: ' . $stmt->rowCount() . PHP_EOL;
} catch (Throwable $exception) {
    fwrite(STDERR, 'Cleanup failed: ' . $exception->getMessage() . PHP_EOL);
    exit(1);
}
