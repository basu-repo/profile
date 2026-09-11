<?php
declare(strict_types=1);

/**
 * Editable site copy: the defaults, the form definition the admin content
 * editor renders from, and the read/write accessors.
 */

function site_content_defaults(): array
{
    static $defaults;

    if ($defaults !== null) {
        return $defaults;
    }

    $defaults = [
        'site_title' => 'Basudeo Narayan Shrestha - Product Owner & AI/ML Researcher',
        'site_name' => 'Basudeo Narayan Shrestha',
        'site_short_name' => 'Basudeo Shrestha',
        'profile_image' => 'images/profile.png',
        'show_expertise_section' => '1',
        'show_about_section' => '1',
        'show_experience_section' => '1',
        'show_education_section' => '1',
        'show_contact_section' => '1',
        'show_privacy_policy' => '1',
        'meta_description' => 'Basudeo Narayan Shrestha - Product Owner & AI/ML Researcher',
        'meta_keywords' => 'product owner, AI/ML, professional, digital transformation',
        'hero_name' => 'Basudeo Narayan Shrestha',
        'hero_tagline' => 'Product Owner | Applied AI/ML Researcher | Intelligent Products & Digital Transformation',
        'hero_location' => 'Malmo, Sweden',
        'linkedin_url' => 'https://www.linkedin.com/in/basudeo-shrestha-785b80ba/',
        'github_url' => 'https://github.com/basudeo88',
        'facebook_url' => 'https://www.facebook.com/basu.shrestha88/',
        'expertise_heading' => 'Key Expertise',
        'expertise_cards' => [
            [
                'title' => 'Product Ownership',
                'description' => '5+ years of experience shaping and delivering digital products in cross-functional and global environments',
            ],
            [
                'title' => 'AI/ML & Data Analytics',
                'description' => 'Pursuing dual MSc studies with focus on data-driven product decisions and intelligent solutions',
            ],
            [
                'title' => 'Agile & SAFe Practitioner',
                'description' => 'Registered Product Owner & Certified SAFe® 6 Practitioner supporting teams through entire product journey',
            ],
        ],
        'about_heading' => 'About Me',
        'about_intro_heading' => 'Professional Overview',
        'about_paragraphs' => [
            'Product Owner with 5+ years of experience shaping and delivering digital products in cross-functional and global environments. My work focuses on turning complex business needs into clear product direction, prioritized backlogs, and actionable requirements that align user needs, business value, and technical feasibility.',
            'Alongside my professional experience, I am pursuing dual MSc studies in AI/ML and Data Analytics, which has strengthened my data-driven mindset and systems thinking. I am especially interested in how AI, analytics, and modern digital technologies can support better product decisions and create scalable, practical solutions.',
            'As a Registered Product Owner and Certified SAFe® 6 Practitioner, I have supported Agile teams and stakeholders throughout the product journey, from discovery and requirement shaping to delivery, validation, and adoption. I am passionate about building products that solve real problems and create measurable impact.',
        ],
        'skills_heading' => 'Skills & Expertise',
        'skills_cards' => [
            [
                'title' => 'Product & Delivery',
                'description' => 'Product ownership, backlog management, user stories, acceptance criteria, requirements engineering, stakeholder management',
            ],
            [
                'title' => 'Agile Frameworks',
                'description' => 'Scrum, SAFe, workshop facilitation, delivery coordination',
            ],
            [
                'title' => 'Technical',
                'description' => 'Python, SQL, Git, Jira, Confluence, Excel, M365 LaTeX',
            ],
            [
                'title' => 'Domain Expertise',
                'description' => 'Digital platforms, business process analysis, distributed systems, AI/ML fundamentals, data analytics, Linux',
            ],
        ],
        'about_page_skills_heading' => 'Key Skills',
        'about_page_skills' => [
            [
                'title' => 'Product & Delivery',
                'description' => 'Product ownership, backlog management, user stories, acceptance criteria, requirements engineering, stakeholder management',
            ],
            [
                'title' => 'Agile Frameworks',
                'description' => 'Scrum, SAFe, workshop facilitation, delivery coordination',
            ],
            [
                'title' => 'Technical',
                'description' => 'Python, SQL, Git, Jira, Confluence, Excel, VS Code, LaTeX',
            ],
            [
                'title' => 'Domain Expertise',
                'description' => 'Digital platforms, business process analysis, distributed systems, AI/ML fundamentals, data analytics, Linux',
            ],
        ],
        'experience_heading' => 'Roles & Impact',
        'experiences' => [
            [
                'title' => 'Consultant - Sr. Product Owner',
                'company' => 'Navyakrita Solutions Pvt. Ltd., Nepal',
                'start_date' => '2021-09-01',
                'end_date' => '2024-08-01',
                'is_current' => '0',
                'date' => 'Sep 2021 - Aug 2024',
                'details' => [
                    'Owned product requirements by translating business goals, user needs, and operational challenges into user stories, workflows, and actionable backlog items',
                    'Worked closely with stakeholders, developers, and delivery teams to align priorities, define scope, and support successful solution delivery',
                    'Facilitated requirement discussions and product clarification sessions to reduce ambiguity and improve team understanding',
                    'Ensured delivered solutions aligned with client expectations, usability needs, and implementation feasibility, contributing to strong long-term client retention',
                ],
            ],
            [
                'title' => 'Product Owner',
                'company' => 'Dryice Solutions Pvt. Ltd., Nepal',
                'start_date' => '2023-03-01',
                'end_date' => '2024-07-01',
                'is_current' => '0',
                'date' => 'Mar 2023 - Jul 2024',
                'details' => [
                    'Gathered and refined business and user requirements across digital initiatives and converted them into clear functional requirements and delivery-ready product inputs',
                    'Collaborated with stakeholders and technical teams to analyze current workflows, identify gaps, and shape solution improvements',
                    'Supported backlog refinement, feature clarification, workflow definition, and validation activities throughout the product lifecycle',
                    'Helped improve alignment between business expectations and delivery outputs through structured communication and solution-focused analysis',
                ],
            ],
            [
                'title' => 'Project Manager I',
                'company' => 'Infinite Software Services Nepal Pvt. Ltd., Nepal',
                'start_date' => '2021-07-01',
                'end_date' => '2024-07-01',
                'is_current' => '0',
                'date' => 'Jul 2021 - Jul 2024',
                'details' => [
                    'Led coordination of delivery operations for recurring healthcare data processing services across multiple US clients in a high-volume environment',
                    'Balanced stakeholder expectations, timelines, and operational constraints to improve delivery efficiency by 30%',
                    'Improved process stability by introducing earlier validation and issue handling, resulting in smoother execution and more predictable delivery outcomes',
                    'Worked across teams to maintain service quality, delivery continuity, and client satisfaction in a deadline-driven setting',
                ],
            ],
            [
                'title' => 'Product Owner',
                'company' => 'Exolutus Pvt. Ltd., Nepal',
                'start_date' => '2019-01-01',
                'end_date' => '2021-07-01',
                'is_current' => '0',
                'date' => 'Jan 2019 - Jul 2021',
                'details' => [
                    'Owned requirement analysis and translated business needs into user stories, workflows, functional specifications, and acceptance criteria for software product development',
                    'Contributed to the delivery of multiple digital products, including Learning Management, Budget Management, Human Resource Management, and Monitoring & Evaluation platforms',
                    'Collaborated with business users, developers, and project stakeholders to clarify requirements, support prioritization, and validate delivered features',
                    'Supported UAT, demonstrations, and iterative feedback cycles to ensure solutions delivered measurable business value and matched user expectations',
                ],
            ],
            [
                'title' => 'Lecturer / Teaching Assistant (Part-Time)',
                'company' => 'Herald College Kathmandu, Nepal',
                'start_date' => '2022-02-01',
                'end_date' => '2024-06-01',
                'is_current' => '0',
                'date' => 'Feb 2022 - Jun 2024',
                'details' => [
                    'Taught undergraduate courses in Database Systems, Big Data, Artificial Intelligence, and Machine Learning',
                    'Strengthened the ability to explain complex concepts clearly, support diverse audiences, and structure knowledge in a practical and understandable way',
                ],
            ],
        ],
        'education_heading' => 'Education',
        'education_items' => [
            [
                'degree' => 'MSc in Information Technology (Artificial Intelligence / Machine Learning)',
                'institution' => 'Halmstad University, Sweden',
                'start_date' => '2024-09-01',
                'end_date' => '',
                'is_current' => '1',
                'date' => 'Sep 2024 - Present',
                'description' => "Strengthening technical understanding of AI/ML, distributed systems, edge computing, and digital platform environments through ongoing master's studies and applied technical research. Current thesis work focuses on real-time intelligent systems, simulation-driven experimentation, and system design under operational constraints.",
            ],
            [
                'degree' => 'MSc in Information Technology (Data Analytics)',
                'institution' => 'London Metropolitan University',
                'start_date' => '2018-02-01',
                'end_date' => '2020-03-01',
                'is_current' => '0',
                'date' => 'Feb 2018 - Mar 2020',
                'description' => '',
            ],
        ],
        'certifications_heading' => 'Certifications & Credentials',
        'certifications' => [
            [
                'title' => 'Registered Product Owner (RPO)',
                'meta' => 'Scrum Inc. • Sep 2023 • ID: RPO-0205519',
                'image' => 'certificates/RPO.png',
                'alt' => 'Registered Product Owner Certificate',
            ],
            [
                'title' => 'Certified SAFe® 6 Practitioner',
                'meta' => 'Scaled Agile, Inc. • Mar 2024 • ID: 52311850-9544',
                'image' => 'certificates/SAFe.png',
                'alt' => 'Certified SAFe 6 Practitioner Certificate',
            ],
        ],
        'languages_heading' => 'Languages',
        'languages' => [
            ['name' => 'English', 'level' => 'Fluent'],
            ['name' => 'Swedish', 'level' => 'Basic, actively improving'],
            ['name' => 'Hindi', 'level' => 'Fluent'],
            ['name' => 'Bengali', 'level' => 'Fluent'],
            ['name' => 'Urdu', 'level' => 'Basic'],
        ],
        'research_heading' => 'Research & Publications',
        'research_items' => [
            [
                'title' => 'Secure real-time IoT systems using blockchain-enabled edge computing and anomaly detection (IEEE MeditCom, 2025)',
                'url' => 'https://ieeexplore.ieee.org/document/11104430',
            ],
        ],
        'research_page_heading' => 'Research',
        'research_page_intro' => 'Papers, thesis, and current project work.',
        'now_page_heading' => 'Now',
        'now_page_intro' => '',
        'now_text' => '',
        'now_updated' => '',
        'contact_heading' => 'Get In Touch',
        'contact_quote' => "\"I believe great things happen when people connect. Whether you have a question, an opportunity, or simply want to exchange ideas — I'd love to hear from you.\"",
        'contact_signature' => '— Basudeo Narayan Shrestha',
        'contact_email' => 'shrestha.basudeo88@gmail.com',
        'contact_phone' => '+46 76 432 0803',
        'contact_location' => 'Oxie, Malmo',
        'contact_linkedin_label' => 'View LinkedIn Profile',
        'privacy_policy_text' => "Your data is protected. Information submitted through this site is used only to respond to your message.\n\nWhen you contact me by email, your information is used only for communication related to your message.\n\nYour information is not sold or shared with third parties for marketing. It is handled responsibly and kept only as needed to respond to your request.\n\nBy contacting me, you consent to this data handling. For any privacy request, contact: shrestha.basudeo88@gmail.com.",
        'footer_text' => '© 2026 Basudeo Shrestha. All rights reserved.',
    ];

    return $defaults;
}

function site_content_form_sections(): array
{
    return [
        [
            'title' => 'Basic Settings',
            'fields' => [
                ['key' => 'site_title', 'label' => 'Site Title', 'type' => 'text'],
                ['key' => 'site_name', 'label' => 'Full Name', 'type' => 'text'],
                ['key' => 'meta_description', 'label' => 'Meta Description', 'type' => 'textarea'],
                ['key' => 'meta_keywords', 'label' => 'Meta Keywords', 'type' => 'textarea'],
            ],
        ],
        [
            'title' => 'Images',
            'fields' => [
                ['key' => 'profile_image', 'label' => 'Profile Image', 'type' => 'image'],
            ],
        ],
        [
            'title' => 'Hero Section',
            'fields' => [
                ['key' => 'hero_name', 'label' => 'Hero Name', 'type' => 'text'],
                ['key' => 'hero_tagline', 'label' => 'Hero Tagline', 'type' => 'wysiwyg'],
                ['key' => 'hero_location', 'label' => 'Hero Location', 'type' => 'text'],
                ['key' => 'linkedin_url', 'label' => 'LinkedIn URL', 'type' => 'url'],
                ['key' => 'github_url', 'label' => 'GitHub URL', 'type' => 'url'],
                ['key' => 'facebook_url', 'label' => 'Facebook URL', 'type' => 'url'],
            ],
        ],
        [
            'title' => 'Expertise Cards',
            'key' => 'expertise_cards',
            'type' => 'repeater',
            'item_label' => 'Card',
            'visibility_key' => 'show_expertise_section',
            'fields' => [
                ['key' => 'title', 'label' => 'Title', 'type' => 'text'],
                ['key' => 'description', 'label' => 'Description', 'type' => 'wysiwyg'],
            ],
        ],
        [
            'title' => 'About Section',
            'visibility_key' => 'show_about_section',
            'fields' => [
                ['key' => 'about_heading', 'label' => 'About Heading', 'type' => 'text'],
                ['key' => 'about_intro_heading', 'label' => 'About Intro Heading', 'type' => 'text'],
                ['key' => 'skills_heading', 'label' => 'Skills Heading', 'type' => 'text'],
                ['key' => 'about_page_skills_heading', 'label' => 'About Page Skills Heading', 'type' => 'text'],
            ],
        ],
        [
            'title' => 'About Paragraphs',
            'key' => 'about_paragraphs',
            'type' => 'string_list',
            'item_label' => 'Paragraph',
            'item_type' => 'wysiwyg',
        ],
        [
            'title' => 'Homepage Skill Cards',
            'key' => 'skills_cards',
            'type' => 'repeater',
            'item_label' => 'Skill Card',
            'fields' => [
                ['key' => 'title', 'label' => 'Title', 'type' => 'text'],
                ['key' => 'description', 'label' => 'Description', 'type' => 'wysiwyg'],
            ],
        ],
        [
            'title' => 'About Page Skill Items',
            'key' => 'about_page_skills',
            'type' => 'repeater',
            'item_label' => 'Skill Item',
            'fields' => [
                ['key' => 'title', 'label' => 'Title', 'type' => 'text'],
                ['key' => 'description', 'label' => 'Description', 'type' => 'wysiwyg'],
            ],
        ],
        [
            'title' => 'Experience Section',
            'visibility_key' => 'show_experience_section',
            'fields' => [
                ['key' => 'experience_heading', 'label' => 'Experience Heading', 'type' => 'text'],
            ],
        ],
        [
            'title' => 'Experience Items',
            'key' => 'experiences',
            'type' => 'repeater',
            'item_label' => 'Experience',
            'fields' => [
                ['key' => 'title', 'label' => 'Role Title', 'type' => 'text'],
                ['key' => 'company', 'label' => 'Company', 'type' => 'text'],
                ['key' => 'start_date', 'label' => 'Start Date', 'type' => 'date'],
                ['key' => 'end_date', 'label' => 'End Date', 'type' => 'date', 'input_attrs' => ['data-end-date' => '1']],
                ['key' => 'is_current', 'label' => 'Currently Employed', 'type' => 'checkbox', 'input_attrs' => ['data-current-toggle' => '1']],
                ['key' => 'details', 'label' => 'Bullet Points', 'type' => 'wysiwyg'],
            ],
        ],
        [
            'title' => 'Education Section',
            'visibility_key' => 'show_education_section',
            'fields' => [
                ['key' => 'education_heading', 'label' => 'Education Heading', 'type' => 'text'],
                ['key' => 'certifications_heading', 'label' => 'Certifications Heading', 'type' => 'text'],
                ['key' => 'languages_heading', 'label' => 'Languages Heading', 'type' => 'text'],
                ['key' => 'research_heading', 'label' => 'Research Heading', 'type' => 'text'],
            ],
        ],
        [
            'title' => 'Education Items',
            'key' => 'education_items',
            'type' => 'repeater',
            'item_label' => 'Education Item',
            'fields' => [
                ['key' => 'degree', 'label' => 'Degree', 'type' => 'text'],
                ['key' => 'institution', 'label' => 'Institution', 'type' => 'text'],
                ['key' => 'start_date', 'label' => 'Start Date', 'type' => 'date'],
                ['key' => 'end_date', 'label' => 'End Date', 'type' => 'date', 'input_attrs' => ['data-end-date' => '1']],
                ['key' => 'is_current', 'label' => 'Currently Studying', 'type' => 'checkbox', 'input_attrs' => ['data-current-toggle' => '1']],
                ['key' => 'description', 'label' => 'Description', 'type' => 'wysiwyg'],
            ],
        ],
        [
            'title' => 'Certifications',
            'key' => 'certifications',
            'type' => 'repeater',
            'item_label' => 'Certification',
            'fields' => [
                ['key' => 'title', 'label' => 'Title', 'type' => 'text'],
                ['key' => 'meta', 'label' => 'Meta Text', 'type' => 'text'],
                ['key' => 'image', 'label' => 'Certificate Image', 'type' => 'image'],
                ['key' => 'alt', 'label' => 'Image Alt Text', 'type' => 'text'],
            ],
        ],
        [
            'title' => 'Languages',
            'key' => 'languages',
            'type' => 'repeater',
            'item_label' => 'Language',
            'layout' => 'compact',
            'fields' => [
                ['key' => 'name', 'label' => 'Language', 'type' => 'text'],
                ['key' => 'level', 'label' => 'Level', 'type' => 'text'],
            ],
        ],
        [
            'title' => 'Research Items',
            'key' => 'research_items',
            'type' => 'repeater',
            'item_label' => 'Research Item',
            'fields' => [
                ['key' => 'title', 'label' => 'Title', 'type' => 'text'],
                ['key' => 'url', 'label' => 'URL', 'type' => 'url'],
            ],
        ],
        [
            'title' => 'Research Page',
            'fields' => [
                ['key' => 'research_page_heading', 'label' => 'Research Page Heading', 'type' => 'text'],
                ['key' => 'research_page_intro', 'label' => 'Research Page Intro', 'type' => 'textarea'],
            ],
        ],
        [
            'title' => 'Now Page',
            'fields' => [
                ['key' => 'now_page_heading', 'label' => 'Now Page Heading', 'type' => 'text'],
                ['key' => 'now_page_intro', 'label' => 'Now Page Intro', 'type' => 'textarea'],
                ['key' => 'now_text', 'label' => 'What You Are Working On Now', 'type' => 'wysiwyg'],
                ['key' => 'now_updated', 'label' => 'Last Updated (for example: September 2026)', 'type' => 'text'],
            ],
        ],
        [
            'title' => 'Contact Section',
            'visibility_key' => 'show_contact_section',
            'fields' => [
                ['key' => 'contact_heading', 'label' => 'Contact Heading', 'type' => 'text'],
                ['key' => 'contact_quote', 'label' => 'Contact Quote', 'type' => 'wysiwyg'],
                ['key' => 'contact_signature', 'label' => 'Contact Signature', 'type' => 'text'],
                ['key' => 'contact_email', 'label' => 'Contact Email', 'type' => 'email'],
                ['key' => 'contact_phone', 'label' => 'Contact Phone', 'type' => 'text'],
                ['key' => 'contact_location', 'label' => 'Contact Location', 'type' => 'text'],
                ['key' => 'contact_linkedin_label', 'label' => 'Contact LinkedIn Label', 'type' => 'text'],
                ['key' => 'footer_text', 'label' => 'Footer Text', 'type' => 'text'],
            ],
        ],
        [
            'title' => 'Privacy Policy',
            'visibility_key' => 'show_privacy_policy',
            'fields' => [
                ['key' => 'privacy_policy_text', 'label' => 'Privacy Policy Text', 'type' => 'wysiwyg'],
            ],
        ],
    ];
}

function site_content_trim_recursive(mixed $value): mixed
{
    if (is_array($value)) {
        $trimmed = [];
        foreach ($value as $key => $item) {
            $trimmed[$key] = site_content_trim_recursive($item);
        }
        return $trimmed;
    }

    return is_string($value) ? trim($value) : $value;
}

function site_content_decode_value(string $key, string $storedValue): mixed
{
    $defaults = site_content_defaults();
    $default = $defaults[$key] ?? null;

    if (!is_array($default)) {
        return $storedValue;
    }

    $decoded = json_decode($storedValue, true);
    return is_array($decoded) ? $decoded : $default;
}

function site_content_all(): array
{
    static $content;

    if ($content !== null) {
        return $content;
    }

    $content = site_content_defaults();

    try {
        $stmt = app_pdo()->query('SELECT content_key, content_value FROM site_content');
        foreach ($stmt as $row) {
            $key = (string)$row['content_key'];
            if (!array_key_exists($key, $content)) {
                if ($key === 'privacy_policy_paragraphs' && isset($content['privacy_policy_text'])) {
                    $legacyParagraphs = site_content_decode_value($key, (string)$row['content_value']);
                    if (is_array($legacyParagraphs)) {
                        $content['privacy_policy_text'] = implode("\n\n", array_map('strval', $legacyParagraphs));
                    }
                }
                continue;
            }

            $content[$key] = site_content_decode_value($key, (string)$row['content_value']);
        }
    } catch (Throwable $exception) {
        // Fallback to defaults when DB is not ready yet.
    }

    return $content;
}

function site_content_get(string $key): string
{
    $content = site_content_all();
    return is_array($content[$key] ?? null) ? '' : (string)($content[$key] ?? '');
}

function site_content_get_bool(string $key): bool
{
    return site_content_get($key) === '1';
}

function site_content_get_array(string $key): array
{
    $content = site_content_all();
    $value = $content[$key] ?? [];
    return is_array($value) ? $value : [];
}

function site_content_normalize_payload(array $input): array
{
    $defaults = site_content_defaults();
    $normalized = [];

    foreach ($defaults as $key => $defaultValue) {
        if (!array_key_exists($key, $input)) {
            $normalized[$key] = $defaultValue;
            continue;
        }

        $value = site_content_trim_recursive($input[$key]);
        $normalized[$key] = is_array($defaultValue) ? (is_array($value) ? $value : $defaultValue) : (string)$value;
    }

    return $normalized;
}

function site_content_save(array $data): void
{
    $payload = site_content_normalize_payload($data);
    $pdo = app_pdo();
    $stmt = $pdo->prepare(
        'INSERT INTO site_content (content_key, content_value)
         VALUES (:content_key, :content_value)
         ON DUPLICATE KEY UPDATE content_value = VALUES(content_value), updated_at = CURRENT_TIMESTAMP'
    );

    foreach ($payload as $key => $value) {
        $stmt->execute([
            'content_key' => $key,
            'content_value' => is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : (string)$value,
        ]);
    }
}

function site_content_lines_to_array(string $value): array
{
    $lines = preg_split('/\r\n|\r|\n/', $value) ?: [];
    $lines = array_map('trim', $lines);
    return array_values(array_filter($lines, static fn(string $line): bool => $line !== ''));
}
