<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/admin_layout.php';

if (admin_count_users() > 0) {
    redirect(admin_is_logged_in() ? 'dashboard.php' : 'login.php');
}

$errors = [];
$fullName = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim((string)($_POST['full_name'] ?? ''));
    $email = strtolower(trim((string)($_POST['email'] ?? '')));
    $password = (string)($_POST['password'] ?? '');
    $confirmPassword = (string)($_POST['confirm_password'] ?? '');

    if ($fullName === '' || $email === '' || $password === '' || $confirmPassword === '') {
        $errors[] = 'All fields are required.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }

    if (strlen($password) < 10) {
        $errors[] = 'Use a password with at least 10 characters.';
    }

    if ($password !== $confirmPassword) {
        $errors[] = 'Password confirmation does not match.';
    }

    if (!$errors) {
        $stmt = app_pdo()->prepare(
            'INSERT INTO admin_users (full_name, email, password_hash)
             VALUES (:full_name, :email, :password_hash)'
        );
        $stmt->execute([
            'full_name' => $fullName,
            'email' => $email,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
        ]);

        admin_set_flash('success', 'Admin account created. Please sign in.');
        redirect('login.php');
    }
}

admin_render_header('Initial Admin Setup', 'setup');
?>
<div class="admin-auth-shell">
    <div class="contact-form admin-auth-card">
        <h2>Create Your First Admin User</h2>
        <p class="form-meta">This setup page is available only until the first admin account is created.</p>

        <?php if ($errors): ?>
            <div class="admin-alert error"><?= h(implode(' ', $errors)) ?></div>
        <?php endif; ?>

        <form method="post" class="admin-form-grid">
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
