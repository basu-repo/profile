<?php
/**
 * @var array $messages
 * @var string $statusFilter
 */
admin_render_header('Messages', 'messages');
?>
<div class="admin-toolbar">
    <div class="social-links admin-filter-links">
        <a href="/admin/messages?status=all" class="<?= $statusFilter === 'all' ? 'active' : '' ?>">All</a>
        <?php foreach (message_statuses() as $status): ?>
            <a href="/admin/messages?status=<?= h($status) ?>" class="<?= $statusFilter === $status ? 'active' : '' ?>"><?= h(ucfirst($status)) ?></a>
        <?php endforeach; ?>
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
                                    <a class="btn btn-primary admin-btn-small" href="/admin/messages/<?= h((string)$message['id']) ?>">Open</a>
                                    <form method="post" action="/admin/messages">
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
