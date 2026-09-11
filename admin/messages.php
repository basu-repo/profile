<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/admin_layout.php';

admin_require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!admin_verify_csrf($_POST['csrf_token'] ?? null)) {
        admin_set_flash('error', 'The action could not be verified. Please try again.');
        redirect('messages.php');
    }

    $messageId = (int)($_POST['message_id'] ?? 0);
    $status = (string)($_POST['status'] ?? '');
    $allowed = ['new', 'read', 'archived'];

    if ($messageId > 0 && in_array($status, $allowed, true)) {
        $stmt = app_pdo()->prepare('UPDATE contact_messages SET status = :status WHERE id = :id');
        $stmt->execute(['status' => $status, 'id' => $messageId]);
        admin_set_flash('success', 'Message status updated.');
    }

    redirect('messages.php');
}

$statusFilter = (string)($_GET['status'] ?? 'all');
$allowedFilters = ['all', 'new', 'read', 'archived'];
if (!in_array($statusFilter, $allowedFilters, true)) {
    $statusFilter = 'all';
}

$messages = [];
try {
    if ($statusFilter === 'all') {
        $stmt = app_pdo()->query(
            'SELECT id, name, email, message, status, delete_after_days, delete_after_at, created_at
             FROM contact_messages
             ORDER BY created_at DESC'
        );
    } else {
        $stmt = app_pdo()->prepare(
            'SELECT id, name, email, message, status, delete_after_days, delete_after_at, created_at
             FROM contact_messages
             WHERE status = :status
             ORDER BY created_at DESC'
        );
        $stmt->execute(['status' => $statusFilter]);
    }
    $messages = $stmt->fetchAll();
} catch (Throwable $exception) {
    admin_set_flash('error', 'Messages could not be loaded. Check your database configuration.');
}

admin_render_header('Messages', 'messages');
?>
<div class="admin-toolbar">
    <div class="social-links admin-filter-links">
        <a href="messages.php?status=all" class="<?= $statusFilter === 'all' ? 'active' : '' ?>">All</a>
        <a href="messages.php?status=new" class="<?= $statusFilter === 'new' ? 'active' : '' ?>">New</a>
        <a href="messages.php?status=read" class="<?= $statusFilter === 'read' ? 'active' : '' ?>">Read</a>
        <a href="messages.php?status=archived" class="<?= $statusFilter === 'archived' ? 'active' : '' ?>">Archived</a>
    </div>
</div>

<div class="contact-form">
    <?php if (!$messages): ?>
        <p class="form-meta">No messages found for this filter.</p>
    <?php else: ?>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Message</th>
                        <th>Status</th>
                        <th>Delete On</th>
                        <th>Received</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($messages as $message): ?>
                        <tr>
                            <td><?= h((string)$message['name']) ?></td>
                            <td><?= h((string)$message['email']) ?></td>
                            <td><?= h(excerpt((string)$message['message'], 90)) ?></td>
                            <td><span class="admin-status <?= h((string)$message['status']) ?>"><?= h((string)$message['status']) ?></span></td>
                            <td>
                                <?php if (!empty($message['delete_after_at'])): ?>
                                    <?= h((string)$message['delete_after_at']) ?>
                                    <div class="form-meta">after <?= h((string)$message['delete_after_days']) ?> days</div>
                                <?php else: ?>
                                    <span class="form-meta">Keep until manual deletion</span>
                                <?php endif; ?>
                            </td>
                            <td><?= h((string)$message['created_at']) ?></td>
                            <td>
                                <div class="admin-table-actions">
                                    <a class="btn btn-primary admin-btn-small" href="message.php?id=<?= h((string)$message['id']) ?>">Open</a>
                                    <form method="post">
                                        <input type="hidden" name="csrf_token" value="<?= h(admin_csrf_token()) ?>">
                                        <input type="hidden" name="message_id" value="<?= h((string)$message['id']) ?>">
                                        <input type="hidden" name="status" value="read">
                                        <button type="submit" class="btn admin-btn-secondary admin-btn-small">Mark Read</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
<?php
admin_render_footer();
