<?php
/**
 * @var array $entries
 * @var string $typeFilter
 */
admin_render_header('Research', 'research');
?>
<div class="admin-toolbar">
    <div class="social-links admin-filter-links">
        <a href="/admin/entries?type=all" class="<?= $typeFilter === 'all' ? 'active' : '' ?>">All</a>
        <?php foreach (entry_types() as $typeKey => $typeLabel): ?>
            <a href="/admin/entries?type=<?= h($typeKey) ?>" class="<?= $typeFilter === $typeKey ? 'active' : '' ?>"><?= h($typeLabel) ?></a>
        <?php endforeach; ?>
    </div>
    <a class="btn btn-primary" href="/admin/entries/new">New entry</a>
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
                        <?php $isPublished = ((int)$entry['is_published']) === 1; ?>
                        <tr>
                            <td>
                                <strong><?= h((string)$entry['title']) ?></strong>
                                <div class="form-meta">/research/<?= h((string)$entry['slug']) ?></div>
                            </td>
                            <td><?= h(entry_type_label((string)$entry['type'])) ?></td>
                            <td><?= h((string)($entry['year'] ?? '')) ?></td>
                            <td><?= h((string)($entry['venue'] ?? '')) ?></td>
                            <td>
                                <span class="admin-status <?= $isPublished ? 'read' : 'new' ?>">
                                    <?= $isPublished ? 'published' : 'draft' ?>
                                </span>
                            </td>
                            <td><?= h((string)$entry['sort_order']) ?></td>
                            <td>
                                <div class="admin-table-actions">
                                    <a class="btn btn-primary admin-btn-small" href="/admin/entries/<?= h((string)$entry['id']) ?>/edit">Edit</a>
                                    <form method="post" action="/admin/entries">
                                        <input type="hidden" name="csrf_token" value="<?= h(admin_csrf_token()) ?>">
                                        <input type="hidden" name="entry_id" value="<?= h((string)$entry['id']) ?>">
                                        <input type="hidden" name="action" value="<?= $isPublished ? 'unpublish' : 'publish' ?>">
                                        <button type="submit" class="btn admin-btn-secondary admin-btn-small">
                                            <?= $isPublished ? 'Unpublish' : 'Publish' ?>
                                        </button>
                                    </form>
                                    <form method="post" action="/admin/entries" data-confirm="Delete &quot;<?= h((string)$entry['title']) ?>&quot;? This cannot be undone.">
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
