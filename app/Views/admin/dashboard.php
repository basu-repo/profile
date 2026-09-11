<?php
/**
 * @var array $stats
 * @var array $recentMessages
 */
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
            <a class="btn btn-primary" href="/admin/messages">Review Messages</a>
            <a class="btn btn-primary" href="/admin/content">Edit Profile Content</a>
            <a class="btn btn-primary" href="/" target="_blank" rel="noopener noreferrer">Open Public Site</a>
        </div>
    </div>

    <div class="contact-form">
        <h2>Recent Messages</h2>
        <?php if (!$recentMessages): ?>
            <p class="form-meta">No messages yet.</p>
        <?php else: ?>
            <div class="admin-mini-list">
                <?php foreach ($recentMessages as $message): ?>
                    <a class="admin-mini-item" href="/admin/messages/<?= h((string)$message['id']) ?>">
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
