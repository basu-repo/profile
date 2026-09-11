<?php
declare(strict_types=1);

function now_index(): void
{
    $nowText = site_content_get('now_text');

    view('pages/now', [
        'nowText' => $nowText,
        'nowUpdated' => site_content_get('now_updated'),
        'metaDescription' => excerpt(strip_tags(app_render_wysiwyg_html($nowText)), 160),
    ]);
}
