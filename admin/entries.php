<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/admin_layout.php';
require_once __DIR__ . '/../includes/entries.php';

admin_require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!admin_verify_csrf($_POST['csrf_token'] ?? null)) {
        admin_set_flash('error', 'The action could not be verified. Please try again.');
        redirect('entries.php');
    }

    $entryId = (int)($_POST['entry_id'] ?? 0);
    $action = (string)($_POST['action'] ?? '');

    try {
        if ($action === 'publish' || $action === 'unpublish') {
            entry_set_published($entryId, $action === 'publish');
            admin_set_flash('success', $action === 'publish' ? 'Entry published.' : 'Entry moved back to draft.');
        } elseif ($action === 'delete') {
            entry_delete($entryId);
            admin_set_flash('success', 'Entry deleted.');
        }
    } catch (Throwable $exception) {
        admin_set_flash('error', 'That action could not be completed.');
    }

    redirect('entries.php');
}

$typeFilter = (string)($_GET['type'] ?? 'all');
if ($typeFilter !== 'all' && !array_key_exists($typeFilter, entry_types())) {
    $typeFilter = 'all';
}

$entries = [];
try {
    $entries = entry_all($typeFilter === 'all' ? null : $typeFilter);
} catch (Throwable $exception) {
    admin_set_flash('error', 'Entries could not be loaded. Run database-entries.sql to create the table.');
}

admin_render_header('Research', 'research');
?>
<div class="admin-toolbar">
    <div class="social-links admin-filter-links">
        <a href="entries.php?type=all" class="<?= $typeFilter === 'all' ? 'active' : '' ?>">All</a>
        <?php foreach (entry_types() as $typeKey => $typeLabel): ?>
            <a href="entries.php?type=<?= h($typeKey) ?>" class="<?= $typeFilter === $typeKey ? 'active' : '' ?>"><?= h($typeLabel) ?></a>
        <?php endforeach; ?>
    </div>
    <a class="btn btn-primary" href="entry.php">New entry</a>
</div>

<div class="contact-form">
    <?php if (!$entries): ?>
        <p class="form-meta">No entries yet. Each one you add becomes its own page at <code>/research/&lt;slug&gt;</code>.</p>
    <?php else: ?>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Year</th>
                        <th>Venue</th>
                        <th>Status</th>
                        <th>Order</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($entries as $entry): ?>
                        <tr>
                            <td>
                                <strong><?= h((string)$entry['title']) ?></strong>
                                <div class="form-meta">/research/<?= h((string)$entry['slug']) ?></div>
                            </td>
                            <td><?= h(entry_type_label((string)$entry['type'])) ?></td>
                            <td><?= h((string)($entry['year'] ?? '')) ?></td>
                            <td><?= h((string)($entry['venue'] ?? '')) ?></td>
                            <td>
                                <span class="admin-status <?= ((int)$entry['is_published']) === 1 ? 'read' : 'new' ?>">
                                    <?= ((int)$entry['is_published']) === 1 ? 'published' : 'draft' ?>
                                </span>
                            </td>
                            <td><?= h((string)$entry['sort_order']) ?></td>
                            <td>
                                <div class="admin-table-actions">
                                    <a class="btn btn-primary admin-btn-small" href="entry.php?id=<?= h((string)$entry['id']) ?>">Edit</a>
                                    <form method="post">
                                        <input type="hidden" name="csrf_token" value="<?= h(admin_csrf_token()) ?>">
                                        <input type="hidden" name="entry_id" value="<?= h((string)$entry['id']) ?>">
                                        <input type="hidden" name="action" value="<?= ((int)$entry['is_published']) === 1 ? 'unpublish' : 'publish' ?>">
                                        <button type="submit" class="btn admin-btn-secondary admin-btn-small">
                                            <?= ((int)$entry['is_published']) === 1 ? 'Unpublish' : 'Publish' ?>
                                        </button>
                                    </form>
                                    <form method="post" data-confirm="Delete &quot;<?= h((string)$entry['title']) ?>&quot;? This cannot be undone.">
                                        <input type="hidden" name="csrf_token" value="<?= h(admin_csrf_token()) ?>">
                                        <input type="hidden" name="entry_id" value="<?= h((string)$entry['id']) ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <button type="submit" class="btn admin-btn-secondary admin-btn-small">Delete</button>
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
