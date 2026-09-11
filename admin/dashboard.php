<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/admin_layout.php';

admin_require_login();

$stats = [
    'total_messages' => 0,
    'new_messages' => 0,
    'read_messages' => 0,
];
$recentMessages = [];

try {
    $stats['total_messages'] = (int)app_pdo()->query('SELECT COUNT(*) FROM contact_messages')->fetchColumn();
    $stats['new_messages'] = (int)app_pdo()->query("SELECT COUNT(*) FROM contact_messages WHERE status = 'new'")->fetchColumn();
    $stats['read_messages'] = (int)app_pdo()->query("SELECT COUNT(*) FROM contact_messages WHERE status = 'read'")->fetchColumn();

    $stmt = app_pdo()->query(
        'SELECT id, name, email, status, created_at
         FROM contact_messages
         ORDER BY created_at DESC
         LIMIT 5'
    );
    $recentMessages = $stmt->fetchAll();
} catch (Throwable $exception) {
    admin_set_flash('error', 'The dashboard could not load message data. Check your database import and credentials.');
}

admin_render_header('Dashboard', 'dashboard');
?>
<div class="admin-stats-grid">
    <div class="feature-card admin-stat-card">
        <h3>Total Messages</h3>
        <p class="admin-stat-value"><?= h((string)$stats['total_messages']) ?></p>
    </div>
    <div class="feature-card admin-stat-card">
        <h3>New Messages</h3>
        <p class="admin-stat-value"><?= h((string)$stats['new_messages']) ?></p>
    </div>
    <div class="feature-card admin-stat-card">
        <h3>Read Messages</h3>
        <p class="admin-stat-value"><?= h((string)$stats['read_messages']) ?></p>
    </div>
</div>

<div class="admin-panel-grid">
    <div class="contact-form">
        <h2>Quick Actions</h2>
        <div class="admin-action-list">
            <a class="btn btn-primary" href="messages.php">Review Messages</a>
            <a class="btn btn-primary" href="content.php">Edit Profile Content</a>
            <a class="btn btn-primary" href="../index.php" target="_blank" rel="noopener noreferrer">Open Public Site</a>
        </div>
    </div>

    <div class="contact-form">
        <h2>Recent Messages</h2>
        <?php if (!$recentMessages): ?>
            <p class="form-meta">No messages yet.</p>
        <?php else: ?>
            <div class="admin-mini-list">
                <?php foreach ($recentMessages as $message): ?>
                    <a class="admin-mini-item" href="message.php?id=<?= h((string)$message['id']) ?>">
                        <strong><?= h((string)$message['name']) ?></strong>
                        <span><?= h((string)$message['email']) ?></span>
                        <span><?= h((string)$message['status']) ?> • <?= h((string)$message['created_at']) ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php
admin_render_footer();
