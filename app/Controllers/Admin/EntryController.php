<?php
declare(strict_types=1);

/**
 * Managing research entries. Each one becomes its own page at
 * /research/<slug> once it is published.
 */
function admin_entries_index(): void
{
    admin_require_login();

    if (route_method() === 'POST') {
        if (!admin_verify_csrf($_POST['csrf_token'] ?? null)) {
            admin_set_flash('error', 'The action could not be verified. Please try again.');
            redirect('/admin/entries');
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

        redirect('/admin/entries');
    }

    $typeFilter = (string)($_GET['type'] ?? 'all');
    if ($typeFilter !== 'all' && !array_key_exists($typeFilter, entry_types())) {
        $typeFilter = 'all';
    }

    $entries = [];
    try {
        $entries = entry_all($typeFilter === 'all' ? null : $typeFilter);
    } catch (Throwable $exception) {
        admin_set_flash('error', 'Entries could not be loaded. Run database/entries.sql to create the table.');
    }

    view('admin/entries/index', ['entries' => $entries, 'typeFilter' => $typeFilter]);
}

function admin_entries_form(array $params = []): void
{
    admin_require_login();

    $entryId = (int)($params['id'] ?? 0);
    $errors = [];

    if ($entryId > 0) {
        $entry = entry_find($entryId);
        if ($entry === null) {
            admin_set_flash('error', 'Entry not found.');
            redirect('/admin/entries');
        }
    } else {
        $entry = entry_blank();
    }

    $formAction = $entryId > 0 ? '/admin/entries/' . $entryId . '/edit' : '/admin/entries/new';

    if (route_method() === 'POST') {
        if (!admin_verify_csrf($_POST['csrf_token'] ?? null)) {
            admin_set_flash('error', 'The form could not be verified. Please try again.');
            redirect($formAction);
        }

        $submitted = (array)($_POST['entry'] ?? []);
        $submitted['id'] = $entryId;
        // Repopulate the form from what was typed, so a validation error never
        // discards the author's work.
        $entry = array_merge($entry, $submitted);

        try {
            $result = entry_save($submitted);
            $errors = $result['errors'];

            if ($errors === []) {
                admin_set_flash('success', $entryId > 0 ? 'Entry updated.' : 'Entry created.');
                redirect('/admin/entries');
            }
        } catch (Throwable $exception) {
            $errors[] = 'The entry could not be saved. Check that the entries table exists.';
        }
    }

    view('admin/entries/form', [
        'entry' => $entry,
        'errors' => $errors,
        'isNew' => $entryId === 0,
        'formAction' => $formAction,
    ]);
}
