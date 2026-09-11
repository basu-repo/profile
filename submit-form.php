<?php
declare(strict_types=1);

header('Content-Type: application/json');

function write_log(string $message): void
{
    $logFile = __DIR__ . '/mail_debug.log';
    $time = date('Y-m-d H:i:s');
    @file_put_contents($logFile, "[$time] $message" . PHP_EOL, FILE_APPEND | LOCK_EX);
}

function respond(int $statusCode, array $payload): never
{
    http_response_code($statusCode);
    echo json_encode($payload);
    exit;
}

// Visitor input is stored verbatim and escaped with h() at every display site
// (admin/messages.php, admin/message.php, admin/dashboard.php). Stripping tags
// here used to silently truncate any message from the first "<" onward, so a
// budget of "<5000 EUR" arrived as an empty promise. Normalise whitespace and
// drop control characters only.
function normalize_control_characters(string $value): string
{
    return preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $value) ?? $value;
}

function normalize_single_line(string $value): string
{
    $value = normalize_control_characters($value);
    $value = preg_replace('/\s+/u', ' ', $value) ?? $value;
    return trim($value);
}

function normalize_message(string $value): string
{
    $value = preg_replace("/\r\n?/", "\n", $value) ?? $value;
    $value = normalize_control_characters($value);
    $value = preg_replace('/[ \t]+/u', ' ', $value) ?? $value;
    $value = preg_replace("/\n{3,}/", "\n\n", $value) ?? $value;
    return trim($value);
}

function text_length(string $value): int
{
    return function_exists('mb_strlen') ? mb_strlen($value) : strlen($value);
}

function is_same_origin_request(): bool
{
    $host = $_SERVER['HTTP_HOST'] ?? '';
    $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
    $referer = $_SERVER['HTTP_REFERER'] ?? '';

    if ($origin !== '') {
        $originHost = parse_url($origin, PHP_URL_HOST) ?? '';
        if ($originHost !== '' && strcasecmp($originHost, $host) !== 0) {
            return false;
        }
    }

    if ($referer !== '') {
        $refererHost = parse_url($referer, PHP_URL_HOST) ?? '';
        if ($refererHost !== '' && strcasecmp($refererHost, $host) !== 0) {
            return false;
        }
    }

    return true;
}

function is_rate_limited(string $clientIp, int $windowSeconds, int $maxRequests): bool
{
    $rateFile = sys_get_temp_dir() . '/contact_rate_' . md5($clientIp);
    $now = time();
    $bucket = ['start' => $now, 'count' => 0];

    if (file_exists($rateFile)) {
        $existing = json_decode((string)file_get_contents($rateFile), true);
        if (is_array($existing) && isset($existing['start'], $existing['count'])) {
            $bucket = $existing;
        }
    }

    if (($now - (int)$bucket['start']) > $windowSeconds) {
        $bucket = ['start' => $now, 'count' => 0];
    }

    $bucket['count'] = (int)$bucket['count'] + 1;
    @file_put_contents($rateFile, json_encode($bucket), LOCK_EX);

    return (int)$bucket['count'] > $maxRequests;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond(405, ['success' => false, 'message' => 'Method not allowed']);
}

if (!is_same_origin_request()) {
    respond(403, ['success' => false, 'message' => 'Forbidden request origin']);
}

$configFile = __DIR__ . '/config.php';
if (!file_exists($configFile)) {
    write_log('Missing config.php');
    respond(500, ['success' => false, 'message' => 'Server configuration is incomplete.']);
}

$config = require $configFile;
$dbConfig = $config['db'] ?? [];
$securityConfig = $config['security'] ?? [];

$rawBody = file_get_contents('php://input');
$input = json_decode($rawBody ?: '', true);

if (!is_array($input)) {
    respond(400, ['success' => false, 'message' => 'Invalid request payload']);
}

$name = normalize_single_line((string)($input['name'] ?? ''));
$email = strtolower(normalize_single_line((string)($input['email'] ?? '')));
$message = normalize_message((string)($input['message'] ?? ''));
$website = trim((string)($input['website'] ?? ''));
$submittedAt = (int)($input['submittedAt'] ?? 0);
$autoDeleteConsent = filter_var($input['autoDeleteConsent'] ?? false, FILTER_VALIDATE_BOOLEAN);
$deleteAfterDaysRaw = trim((string)($input['deleteAfterDays'] ?? ''));
$allowedRetentionDays = [7, 14, 30, 60, 90];
$deleteAfterDays = null;

$clientIp = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$userAgent = substr((string)($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 255);
$referrer = substr((string)($_SERVER['HTTP_REFERER'] ?? ''), 0, 255);

$maxRequests = (int)($securityConfig['max_requests'] ?? 5);
$windowSeconds = (int)($securityConfig['window_seconds'] ?? 900);
$minSubmitMs = (int)($securityConfig['min_submit_ms'] ?? 3000);
$maxSubmitAgeMs = (int)($securityConfig['max_submit_age_ms'] ?? 7200000);

if (is_rate_limited($clientIp, $windowSeconds, $maxRequests)) {
    write_log("Rate limit exceeded for IP {$clientIp}");
    respond(429, ['success' => false, 'message' => 'Too many requests. Please wait and try again.']);
}

if ($website !== '') {
    write_log("Honeypot triggered from IP {$clientIp}");
    respond(400, ['success' => false, 'message' => 'Spam check failed']);
}

$nowMs = (int)round(microtime(true) * 1000);
if ($submittedAt <= 0 || ($nowMs - $submittedAt) < $minSubmitMs || ($nowMs - $submittedAt) > $maxSubmitAgeMs) {
    write_log("Invalid submittedAt timing from IP {$clientIp}");
    respond(400, ['success' => false, 'message' => 'Invalid form submission timing']);
}

if ($name === '' || $email === '' || $message === '') {
    respond(400, ['success' => false, 'message' => 'All fields are required']);
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    write_log("Invalid email format rejected: {$email}");
    respond(400, ['success' => false, 'message' => 'Invalid email address']);
}

if ($autoDeleteConsent) {
    $deleteAfterDaysCandidate = filter_var($deleteAfterDaysRaw, FILTER_VALIDATE_INT);
    if ($deleteAfterDaysCandidate === false || !in_array((int)$deleteAfterDaysCandidate, $allowedRetentionDays, true)) {
        respond(400, ['success' => false, 'message' => 'Invalid auto-delete period selected']);
    }
    $deleteAfterDays = (int)$deleteAfterDaysCandidate;
}

if (text_length($name) > 120 || text_length($email) > 254 || text_length($message) > 500) {
    respond(400, ['success' => false, 'message' => 'Input exceeds allowed length']);
}

$emailDomain = strtolower((string)substr(strrchr($email, '@') ?: '', 1));
if ($emailDomain === '') {
    respond(400, ['success' => false, 'message' => 'Invalid email domain']);
}

$blockedDomains = [
    '10minutemail.com',
    'guerrillamail.com',
    'mailinator.com',
    'temp-mail.org',
    'yopmail.com',
];

if (in_array($emailDomain, $blockedDomains, true)) {
    respond(400, ['success' => false, 'message' => 'Disposable email addresses are not allowed']);
}

if (function_exists('checkdnsrr')) {
    $hasDns = checkdnsrr($emailDomain, 'MX') || checkdnsrr($emailDomain, 'A') || checkdnsrr($emailDomain, 'AAAA');
    if (!$hasDns) {
        write_log("Rejected non-resolving domain: {$emailDomain}");
        respond(400, ['success' => false, 'message' => 'Email domain does not appear valid']);
    }
}

$dsn = sprintf(
    'mysql:host=%s;port=%d;dbname=%s;charset=%s',
    (string)($dbConfig['host'] ?? '127.0.0.1'),
    (int)($dbConfig['port'] ?? 3306),
    (string)($dbConfig['database'] ?? ''),
    (string)($dbConfig['charset'] ?? 'utf8mb4')
);

$username = (string)($dbConfig['username'] ?? '');
$password = (string)($dbConfig['password'] ?? '');
$messageHash = hash('sha256', $email . '|' . $message);
$deleteAfterAt = $deleteAfterDays !== null
    ? (new DateTimeImmutable('now'))->modify('+' . $deleteAfterDays . ' days')->format('Y-m-d H:i:s')
    : null;

try {
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    $duplicateStmt = $pdo->prepare(
        'SELECT id
         FROM contact_messages
         WHERE message_hash = :message_hash
           AND created_at >= (NOW() - INTERVAL 10 MINUTE)
         LIMIT 1'
    );
    $duplicateStmt->execute(['message_hash' => $messageHash]);

    if ($duplicateStmt->fetch()) {
        respond(429, ['success' => false, 'message' => 'This message was already received recently. Please wait before sending it again.']);
    }

    $insertStmt = $pdo->prepare(
        'INSERT INTO contact_messages (name, email, message, message_hash, ip_address, user_agent, referrer, delete_after_days, delete_after_at)
         VALUES (:name, :email, :message, :message_hash, :ip_address, :user_agent, :referrer, :delete_after_days, :delete_after_at)'
    );

    $insertStmt->execute([
        'name' => $name,
        'email' => $email,
        'message' => $message,
        'message_hash' => $messageHash,
        'ip_address' => $clientIp,
        'user_agent' => $userAgent,
        'referrer' => $referrer,
        'delete_after_days' => $deleteAfterDays,
        'delete_after_at' => $deleteAfterAt,
    ]);
} catch (Throwable $exception) {
    write_log('Database save failed: ' . $exception->getMessage());
    respond(500, ['success' => false, 'message' => 'Unable to save message right now. Please try again later.']);
}

respond(200, ['success' => true, 'message' => 'Thank you! Your message has been saved successfully.']);
