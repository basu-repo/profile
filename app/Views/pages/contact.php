<?php
layout_start('Contact', 'contact');
?>
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
        <?php view('partials/contact-form'); ?>
    </div>
</div>
<?php
layout_end();
