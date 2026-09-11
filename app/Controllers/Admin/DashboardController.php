<?php
declare(strict_types=1);

function admin_dashboard(): void
{
    admin_require_login();

    $stats = ['total_messages' => 0, 'new_messages' => 0, 'read_messages' => 0];
    $recentMessages = [];

    try {
        $stats = message_counts();
        $recentMessages = message_recent(5);
    } catch (Throwable $exception) {
        admin_set_flash('error', 'The dashboard could not load message data. Check your database import and credentials.');
    }

    view('admin/dashboard', ['stats' => $stats, 'recentMessages' => $recentMessages]);
}
