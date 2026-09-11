<?php
/**
 * @var array $message
 */
admin_render_header('Message Detail', 'messages');
$formAction = '/admin/messages/' . (string)$message['id'];
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
            <?php foreach (['new' => 'Mark New', 'read' => 'Mark Read', 'archived' => 'Archive'] as $status => $label): ?>
                <form method="post" action="<?= h($formAction) ?>">
                    <input type="hidden" name="csrf_token" value="<?= h(admin_csrf_token()) ?>">
                    <input type="hidden" name="status" value="<?= h($status) ?>">
                    <button type="submit" class="btn <?= $status === 'archived' ? 'btn-primary' : 'admin-btn-secondary' ?>"><?= h($label) ?></button>
                </form>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php
admin_render_footer();
