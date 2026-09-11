<?php
declare(strict_types=1);

function about_index(): void
{
    view('pages/about', [
        'aboutParagraphs' => site_content_get_array('about_paragraphs'),
        'aboutPageSkills' => site_content_get_array('about_page_skills'),
    ]);
}
