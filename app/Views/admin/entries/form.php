<?php
/**
 * @var array $entry
 * @var array $errors
 * @var bool $isNew
 * @var string $formAction
 */
admin_render_header($isNew ? 'New Research Entry' : 'Edit Research Entry', 'research', true);
?>
<div class="contact-form">
    <?php if ($errors): ?>
        <div class="admin-alert error"><?= h(implode(' ', $errors)) ?></div>
    <?php endif; ?>

    <form method="post" action="<?= h($formAction) ?>" class="admin-content-form">
        <input type="hidden" name="csrf_token" value="<?= h(admin_csrf_token()) ?>">

        <div class="admin-form-grid">
            <div class="form-group">
                <label for="entry-type">Type</label>
                <select id="entry-type" name="entry[type]">
                    <?php foreach (entry_types() as $typeKey => $typeLabel): ?>
                        <option value="<?= h($typeKey) ?>" <?= (string)$entry['type'] === $typeKey ? 'selected' : '' ?>><?= h($typeLabel) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="entry-year">Year</label>
                <input type="number" id="entry-year" name="entry[year]" min="1900" max="2100" value="<?= h((string)($entry['year'] ?? '')) ?>">
            </div>
        </div>

        <div class="form-group">
            <label for="entry-title">Title</label>
            <input type="text" id="entry-title" name="entry[title]" maxlength="255" value="<?= h((string)$entry['title']) ?>" required>
        </div>

        <div class="form-group">
            <label for="entry-subtitle">Subtitle <span class="form-meta">(optional)</span></label>
            <input type="text" id="entry-subtitle" name="entry[subtitle]" maxlength="255" value="<?= h((string)($entry['subtitle'] ?? '')) ?>">
        </div>

        <div class="admin-form-grid">
            <div class="form-group">
                <label for="entry-venue">Venue</label>
                <input type="text" id="entry-venue" name="entry[venue]" maxlength="190" value="<?= h((string)($entry['venue'] ?? '')) ?>" placeholder="IEEE MeditCom">
            </div>

            <div class="form-group">
                <label for="entry-authors">Authors</label>
                <input type="text" id="entry-authors" name="entry[authors]" maxlength="255" value="<?= h((string)($entry['authors'] ?? '')) ?>" placeholder="B. N. Shrestha, ...">
            </div>
        </div>

        <div class="form-group">
            <label for="entry-url">Official URL <span class="form-meta">(IEEE Xplore, university repository, project site)</span></label>
            <input type="url" id="entry-url" name="entry[official_url]" maxlength="500" value="<?= h((string)($entry['official_url'] ?? '')) ?>">
        </div>

        <div class="form-group">
            <label for="entry-abstract">Abstract <span class="form-meta">(shown in the list; plain text)</span></label>
            <textarea id="entry-abstract" name="entry[abstract]" rows="4"><?= h((string)($entry['abstract'] ?? '')) ?></textarea>
        </div>

        <div class="form-group">
            <label for="entry-body">Write-up</label>
            <div class="admin-wysiwyg" data-wysiwyg>
                <?php view('partials/wysiwyg-toolbar'); ?>
                <div class="admin-wysiwyg-editor" data-editor-surface><?= app_render_wysiwyg_html((string)($entry['body'] ?? '')) ?></div>
                <textarea id="entry-body" name="entry[body]" rows="12" class="admin-wysiwyg-source" data-editor-input><?= h((string)($entry['body'] ?? '')) ?></textarea>
            </div>
            <small class="form-meta">The problem, your approach, what you found. This is the page body.</small>
        </div>

        <div class="admin-form-grid">
            <div class="form-group">
                <label for="entry-slug">URL slug <span class="form-meta">(leave blank to generate from the title)</span></label>
                <input type="text" id="entry-slug" name="entry[slug]" maxlength="160" value="<?= h((string)$entry['slug']) ?>">
            </div>

            <div class="form-group">
                <label for="entry-sort">Sort order <span class="form-meta">(lower shows first)</span></label>
                <input type="number" id="entry-sort" name="entry[sort_order]" value="<?= h((string)$entry['sort_order']) ?>">
            </div>
        </div>

        <div class="form-group">
            <input type="hidden" name="entry[is_published]" value="0">
            <label class="admin-checkbox-row" for="entry-published">
                <input type="checkbox" id="entry-published" name="entry[is_published]" value="1" <?= ((int)$entry['is_published']) === 1 ? 'checked' : '' ?>>
                <span>Published — visible on the public site</span>
            </label>
        </div>

        <div class="admin-section-actions">
            <button type="submit" class="btn btn-primary"><?= $isNew ? 'Create entry' : 'Save changes' ?></button>
            <a class="btn admin-btn-secondary" href="/admin/entries">Cancel</a>
        </div>
    </form>
</div>
<?php
admin_render_footer();
