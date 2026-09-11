<?php
declare(strict_types=1);

/**
 * Reviewing the messages the public contact form saved.
 */
function admin_messages_index(): void
{
    admin_require_login();

    if (route_method() === 'POST') {
        if (!admin_verify_csrf($_POST['csrf_token'] ?? null)) {
            admin_set_flash('error', 'The action could not be verified. Please try again.');
            redirect('/admin/messages');
        }

        try {
            if (message_set_status((int)($_POST['message_id'] ?? 0), (string)($_POST['status'] ?? ''))) {
                admin_set_flash('success', 'Message status updated.');
            }
        } catch (Throwable $exception) {
            admin_set_flash('error', 'That action could not be completed.');
        }

        redirect('/admin/messages');
    }

    $statusFilter = (string)($_GET['status'] ?? 'all');
    if ($statusFilter !== 'all' && !in_array($statusFilter, message_statuses(), true)) {
        $statusFilter = 'all';
    }

    $messages = [];
    try {
        $messages = message_all($statusFilter === 'all' ? null : $statusFilter);
    } catch (Throwable $exception) {
        admin_set_flash('error', 'Messages could not be loaded. Check your database configuration.');
    }

    view('admin/messages/index', ['messages' => $messages, 'statusFilter' => $statusFilter]);
}

function admin_messages_show(array $params): void
{
    admin_require_login();

    $messageId = (int)($params['id'] ?? 0);
    if ($messageId <= 0) {
        admin_set_flash('error', 'Message not found.');
        redirect('/admin/messages');
    }

    if (route_method() === 'POST') {
        if (!admin_verify_csrf($_POST['csrf_token'] ?? null)) {
            admin_set_flash('error', 'The action could not be verified. Please try again.');
            redirect('/admin/messages/' . $messageId);
        }

        try {
            if (message_set_status($messageId, (string)($_POST['status'] ?? ''))) {
                admin_set_flash('success', 'Message status updated.');
            }
        } catch (Throwable $exception) {
            admin_set_flash('error', 'That action could not be completed.');
        }

        redirect('/admin/messages/' . $messageId);
    }

    // A missing row and an unreachable database are different problems, and
    // the reader deserves to be told which one happened rather than a 500.
    try {
        $message = message_find($messageId);
    } catch (Throwable $exception) {
        admin_set_flash('error', 'The message could not be loaded. Check your database configuration.');
        redirect('/admin/messages');
    }

    if ($message === null) {
        admin_set_flash('error', 'Message not found.');
        redirect('/admin/messages');
    }

    view('admin/messages/show', ['message' => $message]);
}
