<?php
/**
 * @var array $errors
 * @var string $fullName
 * @var string $email
 */
admin_render_header('Initial Admin Setup', 'setup');
?>
<div class="admin-auth-shell">
    <div class="contact-form admin-auth-card">
        <h2>Create Your First Admin User</h2>
        <p class="form-meta">This setup page is available only until the first admin account is created.</p>

        <?php if ($errors): ?>
            <div class="admin-alert error"><?= h(implode(' ', $errors)) ?></div>
        <?php endif; ?>

        <form method="post" action="/admin/setup" class="admin-form-grid">
            <div class="form-group">
                <label for="full_name">Full Name</label>
                <input type="text" id="full_name" name="full_name" value="<?= h($fullName) ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= h($email) ?>" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>

            <button type="submit" class="btn btn-primary">Create Admin Account</button>
        </form>
    </div>
</div>
<?php
admin_render_footer();
