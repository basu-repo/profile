<?php
declare(strict_types=1);

/**
 * The public contact endpoint. It answers JSON only and is the one place a
 * visitor can write to the database, so the spam and abuse checks all live
 * here, ahead of the insert.
 */

function contact_log(string $message): void
{
    $logFile = base_path('storage/mail_debug.log');
    $time = date('Y-m-d H:i:s');
    @file_put_contents($logFile, "[$time] $message" . PHP_EOL, FILE_APPEND | LOCK_EX);
}

/**
 * Visitor input is stored verbatim and escaped with h() at every display site
 * (the admin message screens). Stripping tags here used to silently truncate
 * any message from the first "<" onward, so a budget of "<5000 EUR" arrived as
 * an empty promise. Normalise whitespace and drop control characters only.
 */
function contact_normalize_control_characters(string $value): string
{
    return preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $value) ?? $value;
}

function contact_normalize_single_line(string $value): string
{
    $value = contact_normalize_control_characters($value);
    $value = preg_replace('/\s+/u', ' ', $value) ?? $value;

    return trim($value);
}

function contact_normalize_message(string $value): string
{
    $value = preg_replace("/\r\n?/", "\n", $value) ?? $value;
    $value = contact_normalize_control_characters($value);
    $value = preg_replace('/[ \t]+/u', ' ', $value) ?? $value;
    $value = preg_replace("/\n{3,}/", "\n\n", $value) ?? $value;

    return trim($value);
}

function contact_text_length(string $value): int
{
    return function_exists('mb_strlen') ? mb_strlen($value) : strlen($value);
}

/**
 * "host" or "host:port" for a URL, in the same shape as the Host header. The
 * port matters: comparing the bare host against "localhost:8000" rejected every
 * submission on a server that runs on a non-default port.
 */
function contact_url_authority(string $url): string
{
    $host = parse_url($url, PHP_URL_HOST);
    if (!is_string($host) || $host === '') {
        return '';
    }

    $port = parse_url($url, PHP_URL_PORT);

    return is_int($port) ? $host . ':' . $port : $host;
}

function contact_is_same_origin_request(): bool
{
    $host = $_SERVER['HTTP_HOST'] ?? '';
    $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
    $referer = $_SERVER['HTTP_REFERER'] ?? '';

    if ($origin !== '') {
        $originAuthority = contact_url_authority($origin);
        if ($originAuthority !== '' && strcasecmp($originAuthority, $host) !== 0) {
            return false;
        }
    }

    if ($referer !== '') {
        $refererAuthority = contact_url_authority($referer);
        if ($refererAuthority !== '' && strcasecmp($refererAuthority, $host) !== 0) {
            return false;
        }
    }

    return true;
}

function contact_is_rate_limited(string $clientIp, int $windowSeconds, int $maxRequests): bool
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

function contact_blocked_email_domains(): array
{
    return [
        '10minutemail.com',
        'guerrillamail.com',
        'mailinator.com',
        'temp-mail.org',
        'yopmail.com',
    ];
}

function contact_submit(): void
{
    header('Content-Type: application/json');

    // Routed for both verbs so a stray GET still gets JSON back rather than
    // the HTML error page.
    if (strtoupper((string)($_SERVER['REQUEST_METHOD'] ?? 'GET')) !== 'POST') {
        json_response(405, ['success' => false, 'message' => 'Method not allowed']);
    }

    if (!contact_is_same_origin_request()) {
        json_response(403, ['success' => false, 'message' => 'Forbidden request origin']);
    }

    try {
        $securityConfig = app_config()['security'] ?? [];
    } catch (Throwable $exception) {
        contact_log('Missing configuration: ' . $exception->getMessage());
        json_response(500, ['success' => false, 'message' => 'Server configuration is incomplete.']);
    }

    $input = json_decode((string)file_get_contents('php://input'), true);
    if (!is_array($input)) {
        json_response(400, ['success' => false, 'message' => 'Invalid request payload']);
    }

    $name = contact_normalize_single_line((string)($input['name'] ?? ''));
    $email = strtolower(contact_normalize_single_line((string)($input['email'] ?? '')));
    $message = contact_normalize_message((string)($input['message'] ?? ''));
    $website = trim((string)($input['website'] ?? ''));
    $submittedAt = (int)($input['submittedAt'] ?? 0);
    $autoDeleteConsent = filter_var($input['autoDeleteConsent'] ?? false, FILTER_VALIDATE_BOOLEAN);
    $deleteAfterDaysRaw = trim((string)($input['deleteAfterDays'] ?? ''));
    $deleteAfterDays = null;

    $clientIp = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $userAgent = substr((string)($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 255);
    $referrer = substr((string)($_SERVER['HTTP_REFERER'] ?? ''), 0, 255);

    $maxRequests = (int)($securityConfig['max_requests'] ?? 5);
    $windowSeconds = (int)($securityConfig['window_seconds'] ?? 900);
    $minSubmitMs = (int)($securityConfig['min_submit_ms'] ?? 3000);
    $maxSubmitAgeMs = (int)($securityConfig['max_submit_age_ms'] ?? 7200000);

    if (contact_is_rate_limited($clientIp, $windowSeconds, $maxRequests)) {
        contact_log("Rate limit exceeded for IP {$clientIp}");
        json_response(429, ['success' => false, 'message' => 'Too many requests. Please wait and try again.']);
    }

    if ($website !== '') {
        contact_log("Honeypot triggered from IP {$clientIp}");
        json_response(400, ['success' => false, 'message' => 'Spam check failed']);
    }

    $nowMs = (int)round(microtime(true) * 1000);
    if ($submittedAt <= 0 || ($nowMs - $submittedAt) < $minSubmitMs || ($nowMs - $submittedAt) > $maxSubmitAgeMs) {
        contact_log("Invalid submittedAt timing from IP {$clientIp}");
        json_response(400, ['success' => false, 'message' => 'Invalid form submission timing']);
    }

    if ($name === '' || $email === '' || $message === '') {
        json_response(400, ['success' => false, 'message' => 'All fields are required']);
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        contact_log("Invalid email format rejected: {$email}");
        json_response(400, ['success' => false, 'message' => 'Invalid email address']);
    }

    if ($autoDeleteConsent) {
        $candidate = filter_var($deleteAfterDaysRaw, FILTER_VALIDATE_INT);
        if ($candidate === false || !in_array((int)$candidate, message_retention_days(), true)) {
            json_response(400, ['success' => false, 'message' => 'Invalid auto-delete period selected']);
        }
        $deleteAfterDays = (int)$candidate;
    }

    if (contact_text_length($name) > 120 || contact_text_length($email) > 254 || contact_text_length($message) > 500) {
        json_response(400, ['success' => false, 'message' => 'Input exceeds allowed length']);
    }

    $emailDomain = strtolower((string)substr(strrchr($email, '@') ?: '', 1));
    if ($emailDomain === '') {
        json_response(400, ['success' => false, 'message' => 'Invalid email domain']);
    }

    if (in_array($emailDomain, contact_blocked_email_domains(), true)) {
        json_response(400, ['success' => false, 'message' => 'Disposable email addresses are not allowed']);
    }

    if (function_exists('checkdnsrr')) {
        $hasDns = checkdnsrr($emailDomain, 'MX') || checkdnsrr($emailDomain, 'A') || checkdnsrr($emailDomain, 'AAAA');
        if (!$hasDns) {
            contact_log("Rejected non-resolving domain: {$emailDomain}");
            json_response(400, ['success' => false, 'message' => 'Email domain does not appear valid']);
        }
    }

    $messageHash = hash('sha256', $email . '|' . $message);
    $deleteAfterAt = $deleteAfterDays !== null
        ? (new DateTimeImmutable('now'))->modify('+' . $deleteAfterDays . ' days')->format('Y-m-d H:i:s')
        : null;

    try {
        if (message_is_recent_duplicate($messageHash)) {
            json_response(429, ['success' => false, 'message' => 'This message was already received recently. Please wait before sending it again.']);
        }

        message_create([
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
        contact_log('Database save failed: ' . $exception->getMessage());
        json_response(500, ['success' => false, 'message' => 'Unable to save message right now. Please try again later.']);
    }

    json_response(200, ['success' => true, 'message' => 'Thank you! Your message has been saved successfully.']);
}
