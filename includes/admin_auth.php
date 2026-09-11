<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

function admin_session_start(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
}

function admin_set_flash(string $type, string $message): void
{
    admin_session_start();
    $_SESSION['admin_flash'] = ['type' => $type, 'message' => $message];
}

function admin_get_flash(): ?array
{
    admin_session_start();
    if (!isset($_SESSION['admin_flash'])) {
        return null;
    }

    $flash = $_SESSION['admin_flash'];
    unset($_SESSION['admin_flash']);
    return is_array($flash) ? $flash : null;
}

function admin_user(): ?array
{
    admin_session_start();
    return isset($_SESSION['admin_user']) && is_array($_SESSION['admin_user'])
        ? $_SESSION['admin_user']
        : null;
}

function admin_is_logged_in(): bool
{
    return admin_user() !== null;
}

function admin_require_login(): void
{
    if (!admin_is_logged_in()) {
        admin_set_flash('error', 'Please sign in to continue.');
        redirect('login.php');
    }
}

function admin_login(string $email, string $password): bool
{
    $stmt = app_pdo()->prepare('SELECT id, full_name, email, password_hash FROM admin_users WHERE email = :email LIMIT 1');
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, (string)$user['password_hash'])) {
        return false;
    }

    $update = app_pdo()->prepare('UPDATE admin_users SET last_login_at = CURRENT_TIMESTAMP WHERE id = :id');
    $update->execute(['id' => $user['id']]);

    admin_session_start();
    session_regenerate_id(true);
    $_SESSION['admin_user'] = [
        'id' => (int)$user['id'],
        'full_name' => (string)$user['full_name'],
        'email' => (string)$user['email'],
    ];

    return true;
}

function admin_logout(): void
{
    admin_session_start();
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }
    session_destroy();
}

function admin_count_users(): int
{
    try {
        return (int)app_pdo()->query('SELECT COUNT(*) FROM admin_users')->fetchColumn();
    } catch (Throwable $exception) {
        return 0;
    }
}

function admin_csrf_token(): string
{
    admin_session_start();
    if (empty($_SESSION['admin_csrf_token'])) {
        $_SESSION['admin_csrf_token'] = bin2hex(random_bytes(32));
    }

    return (string)$_SESSION['admin_csrf_token'];
}

function admin_verify_csrf(?string $token): bool
{
    admin_session_start();
    $sessionToken = $_SESSION['admin_csrf_token'] ?? '';

    return is_string($token) && is_string($sessionToken) && $sessionToken !== '' && hash_equals($sessionToken, $token);
}
