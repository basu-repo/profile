<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/admin_layout.php';

if (admin_count_users() === 0) {
    redirect('setup.php');
}

if (admin_is_logged_in()) {
    redirect('dashboard.php');
}

$error = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = strtolower(trim((string)($_POST['email'] ?? '')));
    $password = (string)($_POST['password'] ?? '');

    if (admin_login($email, $password)) {
        admin_set_flash('success', 'Welcome back.');
        redirect('dashboard.php');
    }

    $error = 'Invalid email or password.';
}

admin_render_header('Admin Sign In', 'login');
?>
<div class="admin-auth-shell">
    <div class="contact-form admin-auth-card">
        <h2>Sign In</h2>
        <p class="form-meta">Use your admin account to manage profile content and review saved messages.</p>

        <?php if ($error !== ''): ?>
            <div class="admin-alert error"><?= h($error) ?></div>
        <?php endif; ?>

        <form method="post" class="admin-form-grid">
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
