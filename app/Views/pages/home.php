<?php
/**
 * Home page. Every value here is supplied by HomeController.
 *
 * The page is a two-column shell: a fixed identity rail on the left (photo,
 * name, location, social links and the section index) and a scrolling column
 * of content on the right. Each topic in the rail is its own <section> so the
 * index has something to point at.
 *
 * @var array $skillsCards
 * @var array $experiences
 * @var array $educationItems
 * @var array $certifications
 * @var array $languages
 * @var array $researchItems
 * @var string $privacyPolicyText
 * @var bool $showAboutSection
 * @var bool $showSkillsSection
 * @var bool $showExperienceSection
 * @var bool $showEducationSection
 * @var bool $showContactSection
 * @var bool $showPrivacyPolicy
 */

/**
 * The rail index, built from the same headings the sections print, so renaming
 * a heading in the admin area renames its link too. Sections the admin has
 * switched off never reach the index.
 */
$railSections = [];

if ($showAboutSection) {
    $railSections[] = ['href' => '#about', 'label' => site_content_get('about_intro_heading')];
}

if ($showEducationSection) {
    $railSections[] = ['href' => '#research', 'label' => site_content_get('research_heading')];
}

if ($showExperienceSection) {
    $railSections[] = ['href' => '#experience', 'label' => site_content_get('experience_heading')];
}

if ($showSkillsSection) {
    $railSections[] = ['href' => '#skills', 'label' => site_content_get('skills_heading')];
}

if ($showEducationSection) {
    $railSections[] = ['href' => '#education', 'label' => site_content_get('education_heading')];
    $railSections[] = ['href' => '#certifications', 'label' => site_content_get('certifications_heading')];
    $railSections[] = ['href' => '#languages', 'label' => site_content_get('languages_heading')];
}

if ($showContactSection) {
    $railSections[] = ['href' => '#contact', 'label' => site_content_get('contact_heading')];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= h(site_content_get('meta_description')) ?>">
    <meta name="keywords" content="<?= h(site_content_get('meta_keywords')) ?>">
    <title><?= h(site_content_get('site_title')) ?></title>
    <link rel="stylesheet" href="/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&family=Dancing+Script:wght@600&display=swap" rel="stylesheet">
</head>
<body class="profile-page">
    <div class="profile-shell">
        <aside class="profile-rail" id="home">
            <div class="profile-rail-inner">
                <div class="rail-identity">
                    <img src="<?= h(asset_url(site_content_get('profile_image'))) ?>" alt="<?= h(site_content_get('site_name')) ?>" class="rail-photo">
                    <h1 class="rail-name"><?= h(site_content_get('hero_name')) ?></h1>
                    <p class="rail-location"><i class="fas fa-map-marker-alt"></i> <?= h(site_content_get('hero_location')) ?></p>
                    <!-- Floats as a bar on the right of the window on desktop; falls back
                         into the rail, under the location, once the rail stacks. -->
                    <nav class="social-dock" aria-label="Social profiles">
                        <a href="<?= h(site_content_get('linkedin_url')) ?>" target="_blank" rel="noopener noreferrer" title="LinkedIn" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                        <a href="<?= h(site_content_get('github_url')) ?>" target="_blank" rel="noopener noreferrer" title="GitHub" aria-label="GitHub"><i class="fab fa-github"></i></a>
                        <a href="<?= h(site_content_get('facebook_url')) ?>" target="_blank" rel="noopener noreferrer" title="Facebook" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <?php $discord = trim((string)site_content_get('discord_username')); ?>
                        <?php if ($discord !== ''): ?>
                            <!-- Discord has no public profile URL for a username, so this copies
                                 the handle rather than linking nowhere. -->
                            <button type="button" id="discordCopy" data-handle="<?= h($discord) ?>" title="Discord: <?= h($discord) ?>" aria-label="Copy Discord username <?= h($discord) ?>"><i class="fab fa-discord"></i></button>
                        <?php endif; ?>
                    </nav>
                </div>

                <?php if ($railSections !== []): ?>
                    <nav class="rail-nav" aria-label="Sections on this page">
                        <p class="rail-nav-title">On this page</p>
                        <ul>
                            <?php foreach ($railSections as $section): ?>
                                <li><a class="rail-link" href="<?= h($section['href']) ?>"><?= h($section['label']) ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            </div>
        </aside>

        <main class="profile-main">
            <header class="profile-intro">
                <div class="container">
                    <div class="profile-intro-tagline rich-text-content"><?= app_render_wysiwyg_html(site_content_get('hero_tagline')) ?></div>
                </div>
            </header>

            <?php if ($showAboutSection): ?>
                <section id="about" class="about-section-full profile-section">
                    <div class="container">
                        <h1><?= h(site_content_get('about_intro_heading')) ?></h1>
                        <div class="about-content">
                            <div class="rich-text-content about-paragraph"><?= app_render_wysiwyg_html(site_content_get('about_text')) ?></div>
                        </div>
                    </div>
                </section>
            <?php endif; ?>

            <?php if ($showEducationSection): ?>
                <section id="research" class="education-section-full profile-section">
                    <div class="container">
                        <h1><?= h(site_content_get('research_heading')) ?></h1>
                        <div class="research-content">
                            <?php foreach ($researchItems as $item): ?>
                                <a class="research-row" href="<?= h((string)($item['url'] ?? '')) ?>" target="_blank" rel="noopener noreferrer">
                                    <span class="research-row-title"><?= h((string)($item['title'] ?? '')) ?></span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </section>
            <?php endif; ?>

            <?php if ($showExperienceSection): ?>
                <section id="experience" class="experience-section-full profile-section">
                    <div class="container">
                        <h1><?= h(site_content_get('experience_heading')) ?></h1>
                        <div class="experience-content">
                            <?php foreach ($experiences as $experience): ?>
                                <div class="experience-item">
                                    <div class="experience-header">
                                        <h3><?= h((string)($experience['title'] ?? '')) ?></h3>
                                        <p class="company"><?= h((string)($experience['company'] ?? '')) ?></p>
                                        <p class="date"><?= h(app_format_date_range((array)$experience)) ?></p>
                                    </div>
                                    <div class="experience-details rich-text-content">
                                        <?= app_format_wysiwyg_value($experience['details'] ?? []) ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </section>
            <?php endif; ?>

            <?php if ($showSkillsSection): ?>
                <section id="skills" class="about-section-full profile-section">
                    <div class="container">
                        <h1><?= h(site_content_get('skills_heading')) ?></h1>
                        <div class="skills-section">
                            <?php foreach ($skillsCards as $card): ?>
                                <div class="skill-category">
                                    <h3><?= h((string)($card['title'] ?? '')) ?></h3>
                                    <div class="rich-text-content"><?= app_render_wysiwyg_html((string)($card['description'] ?? '')) ?></div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </section>
            <?php endif; ?>

            <?php if ($showEducationSection): ?>
                <section id="education" class="education-section-full profile-section">
                    <div class="container">
                        <h1><?= h(site_content_get('education_heading')) ?></h1>
                        <div class="education-content">
                            <?php foreach ($educationItems as $item): ?>
                                <div class="education-item">
                                    <h3><?= h((string)($item['degree'] ?? '')) ?></h3>
                                    <p class="institution"><?= h((string)($item['institution'] ?? '')) ?></p>
                                    <p class="date"><?= h(app_format_date_range((array)$item)) ?></p>
                                    <?php if ((string)($item['description'] ?? '') !== ''): ?>
                                        <div class="description rich-text-content"><?= app_render_wysiwyg_html((string)($item['description'] ?? '')) ?></div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </section>

                <section id="certifications" class="education-section-full profile-section">
                    <div class="container">
                        <h1><?= h(site_content_get('certifications_heading')) ?></h1>
                        <div class="cert-accordion">
                            <?php foreach ($certifications as $index => $certification): ?>
                                <?php $certId = 'cert-' . ($index + 1); ?>
                                <div class="cert-accordion-item" id="<?= h($certId) ?>">
                                    <div class="cert-accordion-header js-cert-toggle" data-cert-target="<?= h($certId) ?>">
                                        <div class="cert-header-left">
                                            <div class="cert-icon"><i class="fas fa-certificate"></i></div>
                                            <div>
                                                <h4><?= h((string)($certification['title'] ?? '')) ?></h4>
                                                <p class="cert-meta"><?= h((string)($certification['meta'] ?? '')) ?></p>
                                            </div>
                                        </div>
                                        <span class="cert-toggle-icon"><i class="fas fa-chevron-down"></i></span>
                                    </div>
                                    <div class="cert-accordion-body">
                                        <img src="<?= h(asset_url((string)($certification['image'] ?? ''))) ?>" alt="<?= h((string)($certification['alt'] ?? '')) ?>" class="cert-inline-img">
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </section>

                <section id="languages" class="education-section-full profile-section">
                    <div class="container">
                        <h1><?= h(site_content_get('languages_heading')) ?></h1>
                        <ul class="language-list">
                            <?php foreach ($languages as $language): ?>
                                <li class="language-item">
                                    <span class="language-name"><?= h((string)($language['name'] ?? '')) ?></span>
                                    <span class="language-level"><?= h((string)($language['level'] ?? '')) ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </section>
            <?php endif; ?>

            <?php if ($showContactSection): ?>
                <section id="contact" class="contact-section-full profile-section">
                    <div class="container">
                        <h1><?= h(site_content_get('contact_heading')) ?></h1>
                        <div class="contact-quote">
                            <div class="rich-text-content contact-quote-text"><?= app_render_wysiwyg_html(site_content_get('contact_quote')) ?></div>
                        </div>
                        <div class="contact-form">
                            <?php view('partials/contact-form'); ?>
                        </div>
                    </div>
                </section>
            <?php endif; ?>
            <footer class="profile-footer">
                <div class="container">
                    <p><?= h(site_content_get('footer_text')) ?></p>
                    <?php if ($showPrivacyPolicy): ?>
                        <p><a href="#" id="privacyPolicyLink">Privacy Policy</a></p>
                    <?php endif; ?>
                </div>
            </footer>
        </main>
    </div>

    <?php if ($showPrivacyPolicy): ?>
        <div id="privacyModal" class="privacy-modal">
            <div class="privacy-modal-content">
                <div class="privacy-modal-header">
                    <h3><i class="fas fa-shield-alt"></i> Privacy Policy</h3>
                    <button class="privacy-modal-close" id="privacyModalClose" aria-label="Close privacy policy">&times;</button>
                </div>
                <div class="privacy-modal-body">
                    <?= $privacyPolicyText ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <button id="scrollToTop" title="Back to top"><i class="fas fa-arrow-up"></i></button>

    <script src="/js/script.js?v=20260912b"></script>
</body>
</html>
