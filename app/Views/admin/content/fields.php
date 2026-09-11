<?php
declare(strict_types=1);

/**
 * Field rendering for the content editor. These only emit markup, which is why
 * they sit with the views rather than with the controller.
 */

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

/** An empty row, used to seed a repeater that has no items yet. */
function admin_content_blank_repeater_item(array $fields): array
{
    $item = [];
    foreach ($fields as $field) {
        $item[(string)$field['key']] = '';
    }

    return $item;
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
                    <img src="<?= h(asset_url($value)) ?>" alt="<?= h($label) ?>" class="admin-image-preview">
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
                <?php view('partials/wysiwyg-toolbar'); ?>
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
