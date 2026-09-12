<?php
declare(strict_types=1);

/**
 * The single-page home view, assembled entirely from editable site content.
 */
function home_index(): void
{
    view('pages/home', [
        'skillsCards' => site_content_get_array('skills_cards'),
        'experiences' => site_content_get_array('experiences'),
        'educationItems' => site_content_get_array('education_items'),
        'certifications' => site_content_get_array('certifications'),
        'languages' => site_content_get_array('languages'),
        'researchItems' => site_content_get_array('research_items'),
        'privacyPolicyText' => site_content_get('privacy_policy_text'),
        'showAboutSection' => site_content_get_bool('show_about_section'),
        'showSkillsSection' => site_content_get_bool('show_skills_section'),
        'showExperienceSection' => site_content_get_bool('show_experience_section'),
        'showEducationSection' => site_content_get_bool('show_education_section'),
        'showContactSection' => site_content_get_bool('show_contact_section'),
        'showPrivacyPolicy' => site_content_get_bool('show_privacy_policy'),
    ]);
}
