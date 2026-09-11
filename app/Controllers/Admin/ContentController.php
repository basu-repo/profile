<?php
declare(strict_types=1);

/**
 * The profile content editor. Each section of site content is its own form, so
 * a save only ever rewrites the section that was submitted.
 */

/**
 * PHP spreads a nested file input across parallel arrays
 * ($_FILES[root]['name'][a][b], $_FILES[root]['tmp_name'][a][b], ...). This
 * walks those arrays back into the single-file shape move_uploaded_file wants.
 */
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

/** Blank rows the author added but never filled in are dropped on save. */
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

function admin_content_index(): void
{
    admin_require_login();

    $sections = site_content_form_sections();
    $values = site_content_all();

    if (route_method() === 'POST') {
        admin_content_save($sections, $values);
    }

    view('admin/content/index', ['sections' => $sections, 'values' => $values]);
}

/** Normalises and stores the one section that was submitted, then redirects. */
function admin_content_save(array $sections, array $values): never
{
    if (!admin_verify_csrf($_POST['csrf_token'] ?? null)) {
        admin_set_flash('error', 'The form could not be verified. Please try again.');
        redirect('/admin/content');
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
        redirect('/admin/content');
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
        redirect('/admin/content');
    }

    site_content_save($payload);
    $sectionLabel = $targetSectionTitle !== '' ? $targetSectionTitle : 'This section';
    admin_set_flash('success', $sectionLabel . ' updated successfully.');
    redirect('/admin/content');
}
