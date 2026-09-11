<?php
/**
 * @var string $error
 * @var string $email
 */
admin_render_header('Admin Sign In', 'login');
?>
<div class="admin-auth-shell">
    <div class="contact-form admin-auth-card">
        <h2>Sign In</h2>
        <p class="form-meta">Use your admin account to manage profile content and review saved messages.</p>

        <?php if ($error !== ''): ?>
            <div class="admin-alert error"><?= h($error) ?></div>
        <?php endif; ?>

        <form method="post" action="/admin/login" class="admin-form-grid">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= h($email) ?>" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="btn btn-primary">Sign In</button>
        </form>
    </div>
</div>
<?php
admin_render_footer();
