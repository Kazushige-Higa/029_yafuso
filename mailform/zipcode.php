<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=UTF-8');
header('Cache-Control: public, max-age=86400');

$rawZip = $_GET['zip'] ?? '';
$zip = is_scalar($rawZip) ? preg_replace('/[^0-9]/', '', (string)$rawZip) : '';
if (!is_string($zip) || !preg_match('/^\d{7}$/', $zip)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid postal code'], JSON_UNESCAPED_UNICODE);
    exit;
}

$prefix = substr($zip, 0, 3);
$upstream = 'https://yubinbango.github.io/yubinbango-data/data/' . rawurlencode($prefix) . '.js';
$context = stream_context_create([
    'http' => [
        'method' => 'GET',
        'timeout' => 5,
        'header' => "Accept: application/javascript, application/json\r\n",
    ],
]);
$response = @file_get_contents($upstream, false, $context);
if ($response === false) {
    http_response_code(502);
    echo json_encode(['error' => 'Postal lookup unavailable'], JSON_UNESCAPED_UNICODE);
    exit;
}

$payload = trim((string)$response);
if (preg_match('/^[^(]+\((.*)\);?\s*$/s', $payload, $matches)) {
    $payload = trim($matches[1]);
}
$data = json_decode($payload, true);
if (!is_array($data)) {
    http_response_code(502);
    echo json_encode(['error' => 'Postal data invalid'], JSON_UNESCAPED_UNICODE);
    exit;
}

echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
