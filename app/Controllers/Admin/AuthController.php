<?php
declare(strict_types=1);

/**
 * Entering and leaving the admin area, plus the one-time first-user setup.
 */
function admin_index(): void
{
    if (admin_count_users() === 0) {
        redirect('/admin/setup');
    }

    redirect(admin_is_logged_in() ? '/admin/dashboard' : '/admin/login');
}

function admin_login_page(): void
{
    if (admin_count_users() === 0) {
        redirect('/admin/setup');
    }

    if (admin_is_logged_in()) {
        redirect('/admin/dashboard');
    }

    $error = '';
    $email = '';

    if (route_method() === 'POST') {
        $email = strtolower(trim((string)($_POST['email'] ?? '')));
        $password = (string)($_POST['password'] ?? '');

        if (admin_login($email, $password)) {
            admin_set_flash('success', 'Welcome back.');
            redirect('/admin/dashboard');
        }

        $error = 'Invalid email or password.';
    }

    view('admin/login', ['error' => $error, 'email' => $email]);
}

function admin_logout_page(): void
{
    admin_logout();
    admin_set_flash('success', 'You have been signed out.');
    redirect('/admin/login');
}

function admin_setup_page(): void
{
    if (admin_count_users() > 0) {
        redirect(admin_is_logged_in() ? '/admin/dashboard' : '/admin/login');
    }

    $errors = [];
    $fullName = '';
    $email = '';

    if (route_method() === 'POST') {
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
            admin_create_user($fullName, $email, $password);
            admin_set_flash('success', 'Admin account created. Please sign in.');
            redirect('/admin/login');
        }
    }

    view('admin/setup', ['errors' => $errors, 'fullName' => $fullName, 'email' => $email]);
}
