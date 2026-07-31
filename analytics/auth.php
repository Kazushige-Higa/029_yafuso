<?php
declare(strict_types=1);

$authConfig = require __DIR__ . '/config.php';
$expectedUser = (string)($authConfig['analytics_username'] ?? '');
$expectedHash = (string)($authConfig['analytics_password_hash'] ?? '');
$providedUser = (string)($_SERVER['PHP_AUTH_USER'] ?? '');
$providedPassword = (string)($_SERVER['PHP_AUTH_PW'] ?? '');

if ($providedUser === '' && !empty($_SERVER['HTTP_AUTHORIZATION'])) {
    $authorization = trim((string)$_SERVER['HTTP_AUTHORIZATION']);
    if (stripos($authorization, 'Basic ') === 0) {
        $decoded = base64_decode(substr($authorization, 6), true);
        if ($decoded !== false && strpos($decoded, ':') !== false) {
            [$providedUser, $providedPassword] = explode(':', $decoded, 2);
        }
    }
}

$authorized = $expectedUser !== ''
    && $expectedHash !== ''
    && hash_equals($expectedUser, $providedUser)
    && password_verify($providedPassword, $expectedHash);

if ($expectedUser === '' || $expectedHash === '') {
    header('Cache-Control: private, no-store');
    http_response_code(503);
    exit('Analytics authentication is not configured');
}

if (!$authorized) {
    $analyticsStorageDir = trim((string)($authConfig['analytics_storage_dir'] ?? ''));
    if ($analyticsStorageDir === '') {
        http_response_code(503);
        exit('Analytics rate-limit storage is not configured');
    }

    $rateDir = $analyticsStorageDir . '/auth-rate';
    if (!is_dir($rateDir) && !mkdir($rateDir, 0750, true) && !is_dir($rateDir)) {
        http_response_code(503);
        exit('Analytics rate-limit storage is unavailable');
    }

    $remoteAddress = trim((string)($_SERVER['REMOTE_ADDR'] ?? 'unknown'));
    $ratePath = $rateDir . '/' . hash('sha256', $remoteAddress) . '.json';
    $rateLock = fopen($ratePath . '.lock', 'c');
    if ($rateLock === false || !flock($rateLock, LOCK_EX)) {
        if (is_resource($rateLock)) fclose($rateLock);
        http_response_code(503);
        exit('Analytics rate-limit storage is unavailable');
    }

    $now = time();
    $attempts = array_values(array_filter(
        (array)(is_file($ratePath) ? json_decode((string)file_get_contents($ratePath), true) : []),
        static fn($timestamp) => is_int($timestamp) && $timestamp > $now - 300
    ));
    $attempts[] = $now;
    $rateLimited = count($attempts) > 10;
    if (!$rateLimited) {
        $temporaryFile = tempnam($rateDir, 'auth-');
        $encoded = json_encode($attempts);
        if ($temporaryFile === false || $encoded === false || file_put_contents($temporaryFile, $encoded, LOCK_EX) === false || !rename($temporaryFile, $ratePath)) {
            if (is_string($temporaryFile) && file_exists($temporaryFile)) unlink($temporaryFile);
            $rateLimited = true;
        }
    }
    flock($rateLock, LOCK_UN);
    fclose($rateLock);

    if ($rateLimited) {
        header('Retry-After: 300');
        header('Cache-Control: private, no-store');
        http_response_code(429);
        exit('Too many authentication attempts');
    }
}

if (!$authorized) {
    header('WWW-Authenticate: Basic realm="Yafuso Analytics", charset="UTF-8"');
    header('Cache-Control: private, no-store');
    http_response_code(401);
    exit('Authentication required');
}
