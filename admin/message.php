<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/admin_layout.php';

admin_require_login();

$messageId = (int)($_GET['id'] ?? 0);
if ($messageId <= 0) {
    admin_set_flash('error', 'Message not found.');
    redirect('messages.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!admin_verify_csrf($_POST['csrf_token'] ?? null)) {
        admin_set_flash('error', 'The action could not be verified. Please try again.');
        redirect('message.php?id=' . $messageId);
    }

    $status = (string)($_POST['status'] ?? '');
    $allowed = ['new', 'read', 'archived'];
    if (in_array($status, $allowed, true)) {
        $stmt = app_pdo()->prepare('UPDATE contact_messages SET status = :status WHERE id = :id');
        $stmt->execute(['status' => $status, 'id' => $messageId]);
        admin_set_flash('success', 'Message status updated.');
    }

    redirect('message.php?id=' . $messageId);
}

$stmt = app_pdo()->prepare(
    'SELECT id, name, email, message, status, ip_address, user_agent, referrer, delete_after_days, delete_after_at, created_at
     FROM contact_messages
     WHERE id = :id
     LIMIT 1'
);
$stmt->execute(['id' => $messageId]);
$message = $stmt->fetch();

if (!$message) {
    admin_set_flash('error', 'Message not found.');
    redirect('messages.php');
}

admin_render_header('Message Detail', 'messages');
?>
<div class="admin-panel-grid single-column">
    <div class="contact-form">
        <div class="admin-detail-grid">
            <div>
                <h2><?= h((string)$message['name']) ?></h2>
                <p><strong>Email:</strong> <?= h((string)$message['email']) ?></p>
                <p><strong>Status:</strong> <span class="admin-status <?= h((string)$message['status']) ?>"><?= h((string)$message['status']) ?></span></p>
                <p><strong>Received:</strong> <?= h((string)$message['created_at']) ?></p>
                <p><strong>IP Address:</strong> <?= h((string)$message['ip_address']) ?></p>
            </div>
            <div>
                <p><strong>Referrer:</strong> <?= h((string)$message['referrer']) ?></p>
                <p><strong>User Agent:</strong> <?= h((string)$message['user_agent']) ?></p>
                <p><strong>Auto-delete:</strong>
                    <?php if (!empty($message['delete_after_at'])): ?>
                        <?= h((string)$message['delete_after_at']) ?> (after <?= h((string)$message['delete_after_days']) ?> days)
                    <?php else: ?>
                        Not requested
                    <?php endif; ?>
                </p>
            </div>
        </div>

        <div class="admin-message-body">
            <h3>Message</h3>
            <p><?= nl2br(h((string)$message['message'])) ?></p>
        </div>

        <div class="admin-action-list">
            <form method="post">
                <input type="hidden" name="csrf_token" value="<?= h(admin_csrf_token()) ?>">
                <input type="hidden" name="status" value="new">
                <button type="submit" class="btn admin-btn-secondary">Mark New</button>
            </form>
            <form method="post">
                <input type="hidden" name="csrf_token" value="<?= h(admin_csrf_token()) ?>">
                <input type="hidden" name="status" value="read">
                <button type="submit" class="btn admin-btn-secondary">Mark Read</button>
            </form>
            <form method="post">
                <input type="hidden" name="csrf_token" value="<?= h(admin_csrf_token()) ?>">
                <input type="hidden" name="status" value="archived">
                <button type="submit" class="btn btn-primary">Archive</button>
            </form>
        </div>
    </div>
</div>
<?php
admin_render_footer();
