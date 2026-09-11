<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/site_content.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - <?= h(site_content_get('site_name')) ?></title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <div class="logo"></div>
            <ul class="nav-menu">
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php" class="active">Contact</a></li>
            </ul>
        </div>
    </nav>

    <section class="page-content">
        <div class="container">
            <h1><?= h(site_content_get('contact_heading')) ?></h1>
            <div class="contact-section">
                <div class="contact-info">
                    <h2>Contact Information</h2>
                    <p><strong>Email:</strong> <a href="mailto:<?= h(site_content_get('contact_email')) ?>"><?= h(site_content_get('contact_email')) ?></a></p>
                    <p><strong>Phone:</strong> <?= h(site_content_get('contact_phone')) ?></p>
                    <p><strong>Location:</strong> <?= h(site_content_get('contact_location')) ?></p>
                    <p><strong>LinkedIn:</strong> <a href="<?= h(site_content_get('linkedin_url')) ?>" target="_blank" rel="noopener noreferrer"><?= h(site_content_get('contact_linkedin_label')) ?></a></p>
                </div>

                <div class="contact-form">
                    <h2>Send Me a Message</h2>
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
        </div>
    </section>

    <footer>
        <div class="container">
            <p><?= h(site_content_get('footer_text')) ?></p>
            <p><a href="index.php">Back to Home</a></p>
        </div>
    </footer>

    <script src="js/script.js?v=20260423a"></script>
</body>
</html>
