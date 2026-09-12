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
        'site_title' => 'Basudeo Shrestha - Product Owner & AI/ML Researcher',
        'site_name' => 'Basudeo Shrestha',
        'site_short_name' => 'Basudeo Shrestha',
        'profile_image' => 'images/profile_pic.png',
        'show_about_section' => '1',
        'show_skills_section' => '1',
        'show_experience_section' => '1',
        'show_education_section' => '1',
        'show_contact_section' => '1',
        'show_privacy_policy' => '1',
        'meta_description' => 'Basudeo Shrestha - Product Owner & AI/ML Researcher',
        'meta_keywords' => 'product owner, AI/ML, professional, digital transformation',
        'hero_name' => 'Basudeo Shrestha',
        'hero_tagline' => 'Product Owner | Applied AI/ML Researcher | Intelligent Products & Digital Transformation',
        'hero_location' => 'Malmö, Sweden',
        'linkedin_url' => 'https://www.linkedin.com/in/basudeo-shrestha-785b80ba/',
        'github_url' => 'https://github.com/basu-repo',
        'facebook_url' => 'https://www.facebook.com/basu.shrestha88/',
        'discord_username' => 'basudeo88',
        'about_intro_heading' => 'Overview',
        'about_text' => "I am a Product Owner with more than five years of experience working on digital products in cross-functional, international teams. My work is mainly about turning unclear business goals and operational needs into a prioritized backlog, with requirements that balance user needs, business value and technical feasibility.\n\nI also work on the research side. I completed an MSc in Data Analytics in 2020 and am currently studying for a second MSc in AI/ML at Halmstad University. My thesis and published papers deal with real-time prediction on edge hardware. This has made me more evidence-driven in product decisions and more careful about solutions that have not been tested under realistic conditions.\n\nI have worked in consulting, in in-house product teams and in delivery operations, covering the full cycle from discovery and requirement shaping through to delivery, validation and adoption. What matters most to me is whether a delivered product actually solves the problem it was meant to solve.",
        'skills_heading' => 'Skills & Expertise',
        'skills_cards' => [
            [
                'title' => 'Product & Delivery',
                'description' => 'Product ownership, backlog management, user stories, acceptance criteria, requirements engineering, stakeholder management, UAT',
            ],
            [
                'title' => 'Agile Frameworks',
                'description' => 'Scrum, SAFe, sprint planning, reviews and retrospectives, PI planning, cross-team dependency management, workshop facilitation, delivery coordination',
            ],
            [
                'title' => 'Domain Expertise',
                'description' => 'Digital platforms, business process analysis, distributed systems, edge computing, AI/ML fundamentals, data analytics',
            ],
            [
                'title' => 'Technical',
                'description' => 'Python, SQL, Git, Linux, ROS 2, Gazebo, Jira, Confluence, Excel, M365, LaTeX',
            ],
        ],
        'experience_heading' => 'Roles & Impact',
        'experiences' => [
            [
                'title' => 'Project Assistant',
                'company' => 'Eira Systems AB, Göteborg, Sweden (Hybrid)',
                'start_date' => '2026-06-01',
                'end_date' => '',
                'is_current' => '1',
                'date' => 'Jun 2026 - Present',
                'details' => [
                    'Worked on unmanned ground and aerial vehicle (UGV/UAV) scenarios in simulation, setting up test cases, running them, and collecting structured data from each run',
                    'Studied 5G connectivity for command, control, and telemetry between the vehicles and the ground station, looking at link behaviour and latency across the tested configurations',
                    'Built and maintained the simulation and data pipeline in ROS 2 and Gazebo, including rosbag recording, dataset export, and offline evaluation of trajectory prediction models',
                    'Documented test protocols, configurations, and results so experiments stayed reproducible and could be handed over cleanly',
                ],
            ],
            [
                'title' => 'Consultant - Business Analyst (Part-Time)',
                'company' => 'Dryice Solutions Pvt. Ltd., Kathmandu, Nepal',
                'start_date' => '2023-03-01',
                'end_date' => '2024-07-01',
                'is_current' => '0',
                'date' => 'Mar 2023 - Jul 2024',
                'details' => [
                    'Gathered and refined business and user requirements across digital initiatives, converting them into clear functional requirements and actionable delivery inputs',
                    'Analyzed current-state workflows, identified process and solution gaps, and collaborated with stakeholders and technical teams on improvement options',
                    'Supported backlog refinement, feature clarification, workflow definition, acceptance criteria, and validation activities throughout the product lifecycle',
                    'Improved alignment between business expectations and delivery outputs through structured documentation, traceable requirements, and solution-focused communication',
                ],
            ],
            [
                'title' => 'Lecturer / Teaching Assistant (Part-Time)',
                'company' => 'Herald College Kathmandu, Kathmandu, Nepal',
                'start_date' => '2022-02-01',
                'end_date' => '2024-06-01',
                'is_current' => '0',
                'date' => 'Feb 2022 - Jun 2024',
                'details' => [
                    'Taught undergraduate courses in Database Systems, Big Data, Artificial Intelligence, and Machine Learning',
                    'Prepared course material and adapted explanations of technical topics for students at different levels',
                ],
            ],
            [
                'title' => 'Consultant - Sr. Product Owner (Part-Time)',
                'company' => 'Navyakrita Solutions Pvt. Ltd., Kathmandu, Nepal',
                'start_date' => '2021-09-01',
                'end_date' => '2024-08-01',
                'is_current' => '0',
                'date' => 'Sep 2021 - Aug 2024',
                'details' => [
                    'Translated business goals, user needs, and operational challenges into product requirements, prioritized backlog items, user stories, acceptance criteria, and delivery-ready workflows',
                    'Aligned stakeholders, developers, and delivery teams on product priorities, scope, dependencies, and implementation constraints throughout the delivery lifecycle',
                    'Facilitated product and requirement workshops to resolve ambiguity, support decisions, and build shared understanding across business and technical teams',
                    'Supported backlog refinement, feature prioritization, release readiness, UAT, deployment handoffs, and iterative feedback cycles',
                    'Improved delivery consistency by introducing clearer requirement practices, reusable workflows, earlier validation, and more structured cross-team coordination',
                ],
            ],
            [
                'title' => 'Project Manager I',
                'company' => 'Infinite Software Services Nepal Pvt. Ltd., Kathmandu, Nepal',
                'start_date' => '2021-07-01',
                'end_date' => '2024-07-01',
                'is_current' => '0',
                'date' => 'Jul 2021 - Jul 2024',
                'details' => [
                    'Coordinated recurring healthcare data-processing services for multiple US clients in a high-volume, deadline-driven environment',
                    'Analyzed delivery constraints and stakeholder needs, improving operational efficiency by 30% through clearer priorities, earlier validation, and proactive issue handling',
                    'Worked across teams to maintain service quality, delivery continuity, and client satisfaction while managing changing requirements and timelines',
                ],
            ],
            [
                'title' => 'Product Owner',
                'company' => 'Exolutus Pvt. Ltd., Kathmandu, Nepal',
                'start_date' => '2019-01-01',
                'end_date' => '2021-07-01',
                'is_current' => '0',
                'date' => 'Jan 2019 - Jul 2021',
                'details' => [
                    'Owned requirement analysis and translated business needs into user stories, functional specifications, workflows, business rules, and acceptance criteria',
                    'Supported delivery of Learning Management, Budget Management, Human Resource Management, and Monitoring & Evaluation platforms',
                    'Collaborated with users, developers, and project stakeholders to prioritize features, clarify integrations, and validate delivered functionality',
                    'Led UAT support, product demonstrations, and iterative feedback cycles, improving product fit and delivery consistency through structured requirements and workflows',
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
                'description' => 'Coursework and applied research in AI/ML, distributed systems, edge computing and digital platform environments. The thesis is listed under Research & Publications.',
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
        ],
        'research_heading' => 'Research & Publications',
        'research_items' => [
            [
                'title' => 'Edge-enabled hybrid deep learning for real-time multi-agent tactical maneuver prediction (MSc thesis, Halmstad University, 2026)',
                'url' => 'https://hh.diva-portal.org/smash/record.jsf?pid=diva2%3A2083693',
            ],
            [
                'title' => 'A CNN-GNN-LSTM hybrid deep learning for multi-agent tactical maneuver prediction (IEEE ICARAI, 2026)',
                'url' => 'https://ieeexplore.ieee.org/document/11635358',
            ],
            [
                'title' => 'Secure real-time IoT systems using blockchain-enabled edge computing and anomaly detection (IEEE MeditCom, 2025)',
                'url' => 'https://ieeexplore.ieee.org/document/11104430',
            ],
        ],
        'contact_heading' => 'Get In Touch',
        'contact_quote' => "\"Whether you have a question, an opportunity, or simply want to exchange ideas, I'd love to hear from you.\"",
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
                ['key' => 'footer_text', 'label' => 'Footer Text', 'type' => 'text'],
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
                ['key' => 'discord_username', 'label' => 'Discord Username', 'type' => 'text'],
            ],
        ],
        [
            'title' => 'Overview',
            'visibility_key' => 'show_about_section',
            'fields' => [
                ['key' => 'about_intro_heading', 'label' => 'Heading', 'type' => 'text'],
                ['key' => 'about_text', 'label' => 'Text', 'type' => 'wysiwyg'],
            ],
        ],
        [
            'title' => 'Skills Section',
            'visibility_key' => 'show_skills_section',
            'fields' => [
                ['key' => 'skills_heading', 'label' => 'Skills Heading', 'type' => 'text'],
            ],
        ],
        [
            'title' => 'Skills',
            'key' => 'skills_cards',
            'type' => 'repeater',
            'item_label' => 'Skill',
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
            'title' => 'Contact Section',
            'visibility_key' => 'show_contact_section',
            'fields' => [
                ['key' => 'contact_heading', 'label' => 'Contact Heading', 'type' => 'text'],
                ['key' => 'contact_quote', 'label' => 'Contact Quote', 'type' => 'wysiwyg'],
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
    $legacyAboutParagraphs = null;
    $hasAboutText = false;

    try {
        $stmt = app_pdo()->query('SELECT content_key, content_value FROM site_content');
        foreach ($stmt as $row) {
            $key = (string)$row['content_key'];
            $hasAboutText = $hasAboutText || $key === 'about_text';

            // The overview used to be stored as a list of separate paragraphs.
            // A site that has not been saved since then still only has that row.
            if ($key === 'about_paragraphs') {
                $decoded = json_decode((string)$row['content_value'], true);
                $legacyAboutParagraphs = is_array($decoded) ? $decoded : null;
                continue;
            }

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

        if (!$hasAboutText && $legacyAboutParagraphs !== null) {
            $content['about_text'] = implode('', array_map(
                static fn(mixed $paragraph): string => app_render_wysiwyg_html((string)$paragraph),
                $legacyAboutParagraphs
            ));
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
