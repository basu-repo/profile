<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/site_content.php';

$expertiseCards = site_content_get_array('expertise_cards');
$aboutParagraphs = site_content_get_array('about_paragraphs');
$skillsCards = site_content_get_array('skills_cards');
$experiences = site_content_get_array('experiences');
$educationItems = site_content_get_array('education_items');
$certifications = site_content_get_array('certifications');
$languages = site_content_get_array('languages');
$researchItems = site_content_get_array('research_items');
$privacyPolicyText = site_content_get('privacy_policy_text');
$showExpertiseSection = site_content_get_bool('show_expertise_section');
$showAboutSection = site_content_get_bool('show_about_section');
$showExperienceSection = site_content_get_bool('show_experience_section');
$showEducationSection = site_content_get_bool('show_education_section');
$showContactSection = site_content_get_bool('show_contact_section');
$showPrivacyPolicy = site_content_get_bool('show_privacy_policy');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= h(site_content_get('meta_description')) ?>">
    <meta name="keywords" content="<?= h(site_content_get('meta_keywords')) ?>">
    <title><?= h(site_content_get('site_title')) ?></title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&family=Dancing+Script:wght@600&display=swap" rel="stylesheet">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <div class="logo"></div>
            <ul class="nav-menu">
                <?php if ($showAboutSection): ?>
                    <li><a href="#about">About</a></li>
                <?php endif; ?>
                <?php if ($showExperienceSection): ?>
                    <li><a href="#experience">Roles &amp; Impact</a></li>
                <?php endif; ?>
                <?php if ($showEducationSection): ?>
                    <li><a href="#education">Education</a></li>
                <?php endif; ?>
                <?php if ($showContactSection): ?>
                    <li><a href="#contact">Contact</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <section id="home" class="hero">
        <div class="hero-content">
            <div class="hero-image-container">
                <div class="profile-image-wrapper">
                    <img src="<?= h(site_content_get('profile_image')) ?>" alt="<?= h(site_content_get('site_name')) ?>" class="profile-image">
                </div>
            </div>
            <div class="hero-text-container">
                <h1><?= h(site_content_get('hero_name')) ?></h1>
                <div class="hero-tagline rich-text-content"><?= app_render_wysiwyg_html(site_content_get('hero_tagline')) ?></div>
                <p class="hero-location"><i class="fas fa-map-marker-alt"></i> <?= h(site_content_get('hero_location')) ?></p>
                <div class="social-links">
                    <span class="social-label">Connect With Me:</span>
                    <a href="<?= h(site_content_get('linkedin_url')) ?>" target="_blank" rel="noopener noreferrer" title="LinkedIn"><i class="fab fa-linkedin"></i> LinkedIn</a>
                    <a href="<?= h(site_content_get('github_url')) ?>" target="_blank" rel="noopener noreferrer" title="GitHub"><i class="fab fa-github"></i> GitHub</a>
                    <a href="<?= h(site_content_get('facebook_url')) ?>" target="_blank" rel="noopener noreferrer" title="Facebook"><i class="fab fa-facebook"></i> Facebook</a>
                </div>
            </div>
        </div>
    </section>

    <?php if ($showExpertiseSection): ?>
        <section class="features">
            <div class="container">
                <h2><?= h(site_content_get('expertise_heading')) ?></h2>
                <div class="feature-grid">
                    <?php foreach ($expertiseCards as $card): ?>
                        <div class="feature-card">
                            <h3><?= h((string)($card['title'] ?? '')) ?></h3>
                            <div class="rich-text-content"><?= app_render_wysiwyg_html((string)($card['description'] ?? '')) ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php if ($showAboutSection): ?>
        <section id="about" class="about-section-full">
            <div class="container">
                <h1><?= h(site_content_get('about_heading')) ?></h1>
                <div class="about-content">
                    <h2><?= h(site_content_get('about_intro_heading')) ?></h2>
                    <?php foreach ($aboutParagraphs as $paragraph): ?>
                        <div class="rich-text-content about-paragraph"><?= app_render_wysiwyg_html((string)$paragraph) ?></div>
                    <?php endforeach; ?>

                    <h2><?= h(site_content_get('skills_heading')) ?></h2>
                    <div class="skills-section">
                        <?php foreach ($skillsCards as $card): ?>
                            <div class="skill-category">
                                <h3><?= h((string)($card['title'] ?? '')) ?></h3>
                                <div class="rich-text-content"><?= app_render_wysiwyg_html((string)($card['description'] ?? '')) ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php if ($showExperienceSection): ?>
        <section id="experience" class="experience-section-full">
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

    <?php if ($showEducationSection): ?>
        <section id="education" class="education-section-full">
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

                    <h2 style="margin-top: 2.75rem;"><?= h(site_content_get('certifications_heading')) ?></h2>
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
                                    <img src="<?= h((string)($certification['image'] ?? '')) ?>" alt="<?= h((string)($certification['alt'] ?? '')) ?>" class="cert-inline-img">
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <h2 style="margin-top: 2.75rem;"><?= h(site_content_get('languages_heading')) ?></h2>
                    <div class="languages-grid">
                        <?php foreach ($languages as $language): ?>
                            <div class="language-card">
                                <h4><?= h((string)($language['name'] ?? '')) ?></h4>
                                <p><?= h((string)($language['level'] ?? '')) ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <h2 style="margin-top: 2.75rem;"><?= h(site_content_get('research_heading')) ?></h2>
                    <div class="research-content">
                        <?php foreach ($researchItems as $item): ?>
                            <a class="research-row" href="<?= h((string)($item['url'] ?? '')) ?>" target="_blank" rel="noopener noreferrer">
                                <span class="research-row-title"><?= h((string)($item['title'] ?? '')) ?></span>
                                <span class="research-row-action">Open</span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php if ($showContactSection): ?>
        <section id="contact" class="contact-section-full">
            <div class="container">
                <h1><?= h(site_content_get('contact_heading')) ?></h1>
                <div class="contact-quote">
                    <div class="rich-text-content contact-quote-text"><?= app_render_wysiwyg_html(site_content_get('contact_quote')) ?></div>
                    <span class="contact-signature"><?= h(site_content_get('contact_signature')) ?></span>
                    <p class="contact-email-inline">
                        <span class="contact-email-text"><?= h(site_content_get('contact_email')) ?></span>
                    </p>
                </div>
                <h2 class="contact-form-heading">Message me here</h2>
                <div class="contact-form">
                    <form id="contactForm" novalidate>
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" id="name" name="name" maxlength="120" autocomplete="name" required>
                        </div>

                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" maxlength="254" autocomplete="email" required>
                        </div>

                        <div class="form-group">
                            <label for="message">Message:</label>
                            <textarea id="message" name="message" rows="5" maxlength="500" required></textarea>
                            <small id="messageCounter" class="form-meta">0/500 characters</small>
                        </div>

                        <div class="form-group form-retention-group">
                            <p class="form-meta retention-label">Optional:</p>
                            <div class="retention-inline-row">
                                <label class="checkbox-row" for="autoDeleteConsent">
                                    <input type="checkbox" id="autoDeleteConsent" name="autoDeleteConsent" value="1">
                                    <span>Delete my message automatically after</span>
                                </label>
                                <div class="retention-picker">
                                    <select id="deleteAfterDays" name="deleteAfterDays" disabled>
                                        <option value="">Select days</option>
                                        <option value="7">7 days</option>
                                        <option value="14">14 days</option>
                                        <option value="30">30 days</option>
                                        <option value="60">60 days</option>
                                        <option value="90">90 days</option>
                                    </select>
                                    <span>days.</span>
                                </div>
                            </div>
                            <small class="form-meta">The message are stored in database and will automatically delete in 90 days.</small>
                        </div>

                        <div class="hp-field" aria-hidden="true">
                            <label for="website">Website</label>
                            <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
                        </div>

                        <input type="hidden" id="submittedAt" name="submittedAt" value="">
                        <button type="submit" class="btn btn-primary contact-submit-btn" aria-label="Send message">
                            <i class="fas fa-paper-plane" aria-hidden="true"></i>
                        </button>
                    </form>
                    <div id="formMessage"></div>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <footer>
        <div class="container">
            <p><?= h(site_content_get('footer_text')) ?></p>
            <?php if ($showPrivacyPolicy): ?>
                <p><a href="#" id="privacyPolicyLink">Privacy Policy</a></p>
            <?php endif; ?>
        </div>
    </footer>

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

    <script src="js/script.js?v=20260423a"></script>
</body>
</html>
