<?php
/**
 * The content editor. One form per section, so saving one section never
 * rewrites another.
 *
 * @var array $sections
 * @var array $values
 */
require_once __DIR__ . '/fields.php';

admin_render_header('Edit Content', 'content', true);
?>
<div class="contact-form">
    <h2>Profile Content Editor</h2>
    <p class="form-meta">Edit the website using normal fields. You can upload images and add more items for repeatable sections like experience, certifications, languages, and research.</p>

    <?php foreach ($sections as $section): ?>
        <?php
        $sectionTitle = (string)$section['title'];
        $sectionKey = (string)($section['key'] ?? '');
        $sectionType = (string)($section['type'] ?? '');
        $sectionFields = (array)($section['fields'] ?? []);
        $sectionId = trim((string)($section['key'] ?? $sectionTitle));
        $sectionSlug = preg_replace('/[^a-zA-Z0-9_-]+/', '-', $sectionId);
        $visibilityKey = (string)($section['visibility_key'] ?? '');
        $isSectionHidden = $visibilityKey !== '' && site_content_get($visibilityKey) !== '1';
        ?>
        <form method="post" action="/admin/content" enctype="multipart/form-data" class="admin-content-form">
            <input type="hidden" name="csrf_token" value="<?= h(admin_csrf_token()) ?>">
            <input type="hidden" name="section_id" value="<?= h($sectionId) ?>">
            <input type="hidden" name="section_title" value="<?= h($sectionTitle) ?>">

            <div class="admin-content-section">
                <button type="button" class="admin-section-toggle" data-section-toggle aria-expanded="false">
                    <span class="admin-section-title"><?= h($sectionTitle) ?></span>
                    <span class="admin-section-toggle-icon" aria-hidden="true"><i class="fas fa-chevron-down"></i></span>
                </button>

                <div class="admin-section-body" data-section-body hidden>
                    <?php if ($visibilityKey !== ''): ?>
                        <div class="admin-section-visibility">
                            <label class="admin-checkbox-row" for="<?= h('hide-' . $sectionSlug) ?>">
                                <input type="checkbox" id="<?= h('hide-' . $sectionSlug) ?>" name="hide_section" value="1" <?= $isSectionHidden ? 'checked' : '' ?>>
                                <span>Hide this section on the site</span>
                            </label>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($section['fields']) && !isset($section['key'])): ?>
                        <div class="admin-form-grid">
                            <?php foreach ($sectionFields as $field): ?>
                                <?php
                                $fieldKey = (string)$field['key'];
                                admin_content_render_field(
                                    'content[' . $fieldKey . ']',
                                    (string)$field['label'],
                                    (string)$field['type'],
                                    (string)($values[$fieldKey] ?? ''),
                                    ['upload_name' => 'content_uploads[' . $fieldKey . ']']
                                );
                                ?>
                            <?php endforeach; ?>
                        </div>
                    <?php elseif ($sectionType === 'string_list'): ?>
                        <?php
                        $items = (array)($values[$sectionKey] ?? []);
                        $itemType = (string)($section['item_type'] ?? 'textarea');
                        if ($items === []) {
                            $items = [''];
                        }
                        ?>
                        <div class="admin-repeatable" data-repeatable data-section-type="string_list" data-next-index="<?= h((string)count($items)) ?>">
                            <div class="admin-repeatable-items">
                                <?php foreach ($items as $index => $item): ?>
                                    <div class="admin-repeater-card admin-repeatable-item" data-repeatable-item>
                                        <div class="admin-repeatable-item-header">
                                            <h4><?= h(((string)$section['item_label']) . ' ' . ($index + 1)) ?></h4>
                                            <button type="button" class="btn admin-btn-secondary admin-btn-small" data-remove-repeatable>Remove</button>
                                        </div>
                                        <?php admin_content_render_field(
                                            'content[' . $sectionKey . '][' . $index . ']',
                                            (string)$section['item_label'],
                                            $itemType,
                                            (string)$item
                                        ); ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <template>
                                <div class="admin-repeater-card admin-repeatable-item" data-repeatable-item>
                                    <div class="admin-repeatable-item-header">
                                        <h4><?= h((string)$section['item_label']) ?> __NUMBER__</h4>
                                        <button type="button" class="btn admin-btn-secondary admin-btn-small" data-remove-repeatable>Remove</button>
                                    </div>
                                    <?php admin_content_render_field(
                                        'content[' . $sectionKey . '][__INDEX__]',
                                        (string)$section['item_label'],
                                        $itemType,
                                        ''
                                    ); ?>
                                </div>
                            </template>

                            <button type="button" class="btn btn-primary admin-add-btn" data-add-repeatable>Add <?= h((string)$section['item_label']) ?></button>
                        </div>
                    <?php elseif ($sectionType === 'repeater'): ?>
                        <?php
                        $items = (array)($values[$sectionKey] ?? []);
                        $layoutClass = (string)($section['layout'] ?? '') === 'compact' ? ' admin-form-grid-compact' : '';
                        $blankItem = admin_content_blank_repeater_item($sectionFields);
                        $hasDateRange = array_key_exists('start_date', $blankItem);
                        if ($items === []) {
                            $items = [$blankItem];
                        }
                        ?>
                        <div class="admin-repeatable" data-repeatable data-section-type="repeater" data-next-index="<?= h((string)count($items)) ?>">
                            <div class="admin-repeatable-items">
                                <?php foreach ($items as $index => $item): ?>
                                    <?php $item = $hasDateRange ? admin_content_prepare_date_range_item($item) : $item; ?>
                                    <div class="admin-repeater-card admin-repeatable-item" data-repeatable-item>
                                        <div class="admin-repeatable-item-header">
                                            <h4><?= h(((string)$section['item_label']) . ' ' . ($index + 1)) ?></h4>
                                            <button type="button" class="btn admin-btn-secondary admin-btn-small" data-remove-repeatable>Remove</button>
                                        </div>
                                        <div class="admin-form-grid<?= h($layoutClass) ?>">
                                            <?php foreach ($sectionFields as $field): ?>
                                                <?php
                                                $fieldKey = (string)$field['key'];
                                                $fieldType = (string)$field['type'];
                                                $fieldValue = $item[$fieldKey] ?? '';
                                                if ($fieldType === 'list' && is_array($fieldValue)) {
                                                    $fieldValue = implode(PHP_EOL, $fieldValue);
                                                } elseif ($fieldType === 'wysiwyg') {
                                                    $fieldValue = app_format_wysiwyg_value($fieldValue);
                                                }

                                                admin_content_render_field(
                                                    'content[' . $sectionKey . '][' . $index . '][' . $fieldKey . ']',
                                                    (string)$field['label'],
                                                    $fieldType,
                                                    (string)$fieldValue,
                                                    [
                                                        'upload_name' => 'content_uploads[' . $sectionKey . '][' . $index . '][' . $fieldKey . ']',
                                                        'input_attrs' => (array)($field['input_attrs'] ?? []),
                                                    ]
                                                );
                                                ?>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <template>
                                <div class="admin-repeater-card admin-repeatable-item" data-repeatable-item>
                                    <div class="admin-repeatable-item-header">
                                        <h4><?= h((string)$section['item_label']) ?> __NUMBER__</h4>
                                        <button type="button" class="btn admin-btn-secondary admin-btn-small" data-remove-repeatable>Remove</button>
                                    </div>
                                    <div class="admin-form-grid<?= h($layoutClass) ?>">
                                        <?php foreach ($sectionFields as $field): ?>
                                            <?php
                                            admin_content_render_field(
                                                'content[' . $sectionKey . '][__INDEX__][' . (string)$field['key'] . ']',
                                                (string)$field['label'],
                                                (string)$field['type'],
                                                '',
                                                [
                                                    'upload_name' => 'content_uploads[' . $sectionKey . '][__INDEX__][' . (string)$field['key'] . ']',
                                                    'input_attrs' => (array)($field['input_attrs'] ?? []),
                                                ]
                                            );
                                            ?>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </template>

                            <button type="button" class="btn btn-primary admin-add-btn" data-add-repeatable>Add <?= h((string)$section['item_label']) ?></button>
                        </div>
                    <?php endif; ?>

                    <div class="admin-section-actions">
                        <button type="submit" class="btn btn-primary">Save <?= h($sectionTitle) ?></button>
                    </div>
                </div>
            </div>
        </form>
    <?php endforeach; ?>
</div>
<?php
admin_render_footer();
