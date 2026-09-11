<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/admin_layout.php';

function admin_content_extract_upload(string $root, array $keys): array
{
    if (!isset($_FILES[$root]) || !is_array($_FILES[$root])) {
        return ['error' => UPLOAD_ERR_NO_FILE];
    }

    $file = $_FILES[$root];
    $attributes = ['name', 'type', 'tmp_name', 'error', 'size'];
    $result = [];

    foreach ($attributes as $attribute) {
        $value = $file[$attribute] ?? null;
        foreach ($keys as $key) {
            if (!is_array($value) || !array_key_exists($key, $value)) {
                $value = null;
                break;
            }
            $value = $value[$key];
        }
        $result[$attribute] = $value;
    }

    return $result;
}

function admin_content_has_repeater_content(array $item, array $fields): bool
{
    foreach ($fields as $field) {
        $fieldKey = (string)$field['key'];
        $fieldType = (string)($field['type'] ?? 'text');
        $value = $item[$fieldKey] ?? '';

        if ($fieldType === 'checkbox') {
            if ((string)$value === '1') {
                return true;
            }
            continue;
        }

        if (is_array($value) && !empty(array_filter($value, static fn($entry): bool => trim((string)$entry) !== ''))) {
            return true;
        }

        if (!is_array($value) && trim((string)$value) !== '') {
            return true;
        }
    }

    return false;
}

function admin_content_blank_repeater_item(array $fields): array
{
    $item = [];
    foreach ($fields as $field) {
        $item[(string)$field['key']] = '';
    }
    return $item;
}

function admin_content_render_attrs(array $attrs): string
{
    $parts = [];
    foreach ($attrs as $name => $value) {
        if ($value === null || $value === false || $value === '') {
            continue;
        }

        if ($value === true) {
            $parts[] = h((string)$name);
            continue;
        }

        $parts[] = h((string)$name) . '="' . h((string)$value) . '"';
    }

    return $parts === [] ? '' : ' ' . implode(' ', $parts);
}

function admin_content_prepare_date_range_item(array $item): array
{
    $range = app_extract_date_range_fields($item);
    $item['start_date'] = $range['start_date'];
    $item['end_date'] = $range['end_date'];
    $item['is_current'] = $range['is_current'];

    return $item;
}

function admin_content_render_field(string $name, string $label, string $type, string $value, array $options = []): void
{
    $inputId = preg_replace('/[^a-zA-Z0-9_-]+/', '-', $name) ?: uniqid('field-', false);
    $wrapperClass = trim('form-group ' . (string)($options['wrapper_class'] ?? ''));
    $inputAttrs = admin_content_render_attrs((array)($options['input_attrs'] ?? []));
    ?>
    <div class="<?= h($wrapperClass) ?>">
        <?php if (!in_array($type, ['checkbox', 'wysiwyg'], true)): ?>
            <label for="<?= h($inputId) ?>"><?= h($label) ?></label>
        <?php endif; ?>
        <?php if ($type === 'image'): ?>
            <input type="hidden" name="<?= h($name) ?>" value="<?= h($value) ?>">
            <?php if ($value !== ''): ?>
                <div class="admin-image-preview-wrap">
                    <img src="<?= h('../' . ltrim($value, '/')) ?>" alt="<?= h($label) ?>" class="admin-image-preview">
                </div>
                <small class="form-meta">Current file: <?= h($value) ?></small>
            <?php endif; ?>
            <input type="file" id="<?= h($inputId) ?>" name="<?= h((string)($options['upload_name'] ?? '')) ?>" accept=".jpg,.jpeg,.png,.gif,.webp">
            <small class="form-meta">Upload JPG, PNG, GIF, or WEBP.</small>
        <?php elseif ($type === 'checkbox'): ?>
            <input type="hidden" name="<?= h($name) ?>" value="0">
            <label class="admin-checkbox-row" for="<?= h($inputId) ?>">
                <input type="checkbox" id="<?= h($inputId) ?>" name="<?= h($name) ?>" value="1" <?= $value === '1' ? 'checked' : '' ?><?= $inputAttrs ?>>
                <span><?= h($label) ?></span>
            </label>
        <?php elseif ($type === 'wysiwyg'): ?>
            <label for="<?= h($inputId) ?>"><?= h($label) ?></label>
            <div class="admin-wysiwyg" data-wysiwyg>
                <div class="admin-wysiwyg-toolbar" data-editor-toolbar>
                    <span class="ql-formats">
                        <button type="button" class="ql-bold" aria-label="Bold"></button>
                        <button type="button" class="ql-italic" aria-label="Italic"></button>
                        <button type="button" class="ql-underline" aria-label="Underline"></button>
                    </span>
                    <span class="ql-formats">
                        <button type="button" class="ql-list" value="ordered" aria-label="Ordered List"></button>
                        <button type="button" class="ql-list" value="bullet" aria-label="Bullet List"></button>
                    </span>
                    <span class="ql-formats">
                        <button type="button" class="ql-link" aria-label="Link"></button>
                        <button type="button" class="ql-clean" aria-label="Clear Formatting"></button>
                    </span>
                </div>
                <div class="admin-wysiwyg-editor" data-editor-surface><?= $value ?></div>
                <textarea id="<?= h($inputId) ?>" name="<?= h($name) ?>" rows="8" class="admin-wysiwyg-source" data-editor-input><?= h($value) ?></textarea>
            </div>
            <small class="form-meta">Use the Quill toolbar to format text. The editor saves formatted content safely.</small>
        <?php elseif (in_array($type, ['textarea', 'list'], true)): ?>
            <textarea id="<?= h($inputId) ?>" name="<?= h($name) ?>" rows="<?= $type === 'list' ? '5' : '4' ?>"<?= $inputAttrs ?>><?= h($value) ?></textarea>
            <?php if ($type === 'list'): ?>
                <small class="form-meta">Enter one item per line.</small>
            <?php endif; ?>
        <?php else: ?>
            <input type="<?= h($type) ?>" id="<?= h($inputId) ?>" name="<?= h($name) ?>" value="<?= h($value) ?>"<?= $inputAttrs ?>>
        <?php endif; ?>
    </div>
    <?php
}

admin_require_login();

$sections = site_content_form_sections();
$values = site_content_all();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!admin_verify_csrf($_POST['csrf_token'] ?? null)) {
        admin_set_flash('error', 'The form could not be verified. Please try again.');
        redirect('content.php');
    }

    $payload = $values;
    $input = $_POST['content'] ?? [];
    $targetSectionId = trim((string)($_POST['section_id'] ?? ''));
    $targetSectionTitle = trim((string)($_POST['section_title'] ?? ''));
    $targetSection = null;

    foreach ($sections as $section) {
        $sectionId = trim((string)($section['key'] ?? ($section['title'] ?? '')));
        if ($sectionId === $targetSectionId) {
            $targetSection = $section;
            break;
        }
    }

    if ($targetSection === null) {
        admin_set_flash('error', 'The selected section could not be found.');
        redirect('content.php');
    }

    try {
        $visibilityKey = (string)($targetSection['visibility_key'] ?? '');
        if ($visibilityKey !== '') {
            $payload[$visibilityKey] = isset($_POST['hide_section']) ? '0' : '1';
        }

        if (isset($targetSection['fields']) && !isset($targetSection['key'])) {
            foreach ($targetSection['fields'] as $field) {
                $key = (string)$field['key'];
                $fieldType = (string)$field['type'];

                if ($fieldType === 'image') {
                    $existingValue = trim((string)($input[$key] ?? $values[$key] ?? ''));
                    $uploadedFile = admin_content_extract_upload('content_uploads', [$key]);
                    $storedPath = app_store_uploaded_image($uploadedFile, 'profile');
                    $payload[$key] = $storedPath ?? $existingValue;
                    continue;
                }

                if ($fieldType === 'checkbox') {
                    $payload[$key] = ($input[$key] ?? '0') === '1' ? '1' : '0';
                    continue;
                }

                if ($fieldType === 'wysiwyg') {
                    $payload[$key] = app_sanitize_wysiwyg_html((string)($input[$key] ?? ''));
                    continue;
                }

                $payload[$key] = trim((string)($input[$key] ?? ''));
            }
        } else {
            $sectionKey = (string)($targetSection['key'] ?? '');
            $sectionType = (string)($targetSection['type'] ?? '');

            if ($sectionType === 'string_list') {
                $items = $input[$sectionKey] ?? [];
                $normalizedItems = [];
                $itemType = (string)($targetSection['item_type'] ?? 'textarea');
                foreach ((array)$items as $item) {
                    $value = $itemType === 'wysiwyg'
                        ? app_sanitize_wysiwyg_html((string)$item)
                        : trim((string)$item);
                    if ($value !== '') {
                        $normalizedItems[] = $value;
                    }
                }
                $payload[$sectionKey] = $normalizedItems;
            } elseif ($sectionType === 'repeater') {
                $items = $input[$sectionKey] ?? [];
                $normalizedItems = [];
                $fields = (array)($targetSection['fields'] ?? []);

                foreach ((array)$items as $index => $item) {
                    $normalizedItem = [];
                    foreach ($fields as $field) {
                        $fieldKey = (string)$field['key'];
                        $fieldType = (string)$field['type'];
                        $rawValue = $item[$fieldKey] ?? '';

                        if ($fieldType === 'list') {
                            $normalizedItem[$fieldKey] = site_content_lines_to_array((string)$rawValue);
                            continue;
                        }

                        if ($fieldType === 'image') {
                            $existingValue = trim((string)$rawValue);
                            $uploadedFile = admin_content_extract_upload('content_uploads', [$sectionKey, $index, $fieldKey]);
                            $uploadFolder = $sectionKey === 'certifications' ? 'certificates' : $sectionKey;
                            $storedPath = app_store_uploaded_image($uploadedFile, $uploadFolder);
                            $normalizedItem[$fieldKey] = $storedPath ?? $existingValue;
                            continue;
                        }

                        if ($fieldType === 'checkbox') {
                            $normalizedItem[$fieldKey] = ((string)$rawValue) === '1' ? '1' : '0';
                            continue;
                        }

                        if ($fieldType === 'date') {
                            $normalizedItem[$fieldKey] = app_normalize_html_date((string)$rawValue);
                            continue;
                        }

                        if ($fieldType === 'wysiwyg') {
                            $normalizedItem[$fieldKey] = app_sanitize_wysiwyg_html((string)$rawValue);
                            continue;
                        }

                        $normalizedItem[$fieldKey] = trim((string)$rawValue);
                    }

                    if (array_key_exists('is_current', $normalizedItem) && $normalizedItem['is_current'] === '1') {
                        $normalizedItem['end_date'] = '';
                    }

                    if (array_key_exists('start_date', $normalizedItem) || array_key_exists('end_date', $normalizedItem) || array_key_exists('is_current', $normalizedItem)) {
                        $normalizedItem['date'] = app_format_date_range($normalizedItem);
                    }

                    if (admin_content_has_repeater_content($normalizedItem, $fields)) {
                        $normalizedItems[] = $normalizedItem;
                    }
                }

                $payload[$sectionKey] = $normalizedItems;
            }
        }
    } catch (Throwable $exception) {
        admin_set_flash('error', $exception->getMessage());
        redirect('content.php');
    }

    site_content_save($payload);
    $sectionLabel = $targetSectionTitle !== '' ? $targetSectionTitle : 'This section';
    admin_set_flash('success', $sectionLabel . ' updated successfully.');
    redirect('content.php');
}

admin_render_header('Edit Content', 'content');
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
        $visibilityKey = (string)($section['visibility_key'] ?? '');
        $isSectionHidden = $visibilityKey !== '' && site_content_get($visibilityKey) !== '1';
        ?>
        <form method="post" enctype="multipart/form-data" class="admin-content-form">
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
                            <label class="admin-checkbox-row" for="<?= h('hide-' . preg_replace('/[^a-zA-Z0-9_-]+/', '-', $sectionId)) ?>">
                                <input type="checkbox" id="<?= h('hide-' . preg_replace('/[^a-zA-Z0-9_-]+/', '-', $sectionId)) ?>" name="hide_section" value="1" <?= $isSectionHidden ? 'checked' : '' ?>>
                                <span>Hide this section on the site</span>
                            </label>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($section['fields']) && !isset($section['key'])): ?>
                        <div class="admin-form-grid">
                            <?php foreach ($sectionFields as $field): ?>
                                <?php
                                $fieldKey = (string)$field['key'];
                                $fieldType = (string)$field['type'];
                                admin_content_render_field(
                                    'content[' . $fieldKey . ']',
                                    (string)$field['label'],
                                    $fieldType,
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
                        if ($items === []) {
                            $items = [admin_content_blank_repeater_item($sectionFields)];
                        }
                        ?>
                        <div class="admin-repeatable" data-repeatable data-section-type="repeater" data-next-index="<?= h((string)count($items)) ?>">
                            <div class="admin-repeatable-items">
                                <?php foreach ($items as $index => $item): ?>
                                    <?php
                                    if (array_key_exists('start_date', admin_content_blank_repeater_item($sectionFields))) {
                                        $item = admin_content_prepare_date_range_item($item);
                                    }
                                    ?>
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
