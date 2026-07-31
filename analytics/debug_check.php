<?php
declare(strict_types=1);

/**
 * TEMPORARY diagnostic script — confirms whether ANALYTICS_CONFIG_FILE is
 * being read correctly. Delete this file once the issue is resolved.
 */

header('Content-Type: text/plain; charset=utf-8');
header('Cache-Control: no-store');

$expectedToken = 'wPSit0JNLHQbQGSHxbn8FQ';
$providedToken = (string)($_GET['token'] ?? '');
if (!hash_equals($expectedToken, $providedToken)) {
    http_response_code(404);
    exit('Not Found');
}

$envValue = trim((string) (getenv('ANALYTICS_CONFIG_FILE') ?: ''));
echo "ANALYTICS_CONFIG_FILE (getenv): " . ($envValue !== '' ? $envValue : '(empty)') . "\n";
echo "\$_SERVER['ANALYTICS_CONFIG_FILE']: " . (isset($_SERVER['ANALYTICS_CONFIG_FILE']) ? (string)$_SERVER['ANALYTICS_CONFIG_FILE'] : '(not set)') . "\n\n";

if ($envValue !== '') {
    echo "starts with '/': " . ($envValue[0] === '/' ? 'yes' : 'no') . "\n";
    $real = realpath($envValue);
    echo "realpath(): " . ($real === false ? '(false = file not found, or a parent directory is not traversable)' : $real) . "\n";
    if ($real !== false) {
        echo "is_file(): " . (is_file($real) ? 'yes' : 'no') . "\n";
        echo "is_readable(): " . (is_readable($real) ? 'yes' : 'no') . "\n";
        echo "filesize(): " . (string)@filesize($real) . " bytes\n";
    }
}

echo "\n";
$docRoot = trim((string) ($_SERVER['DOCUMENT_ROOT'] ?? ''));
echo "\$_SERVER['DOCUMENT_ROOT']: " . ($docRoot !== '' ? $docRoot : '(empty)') . "\n";
echo "realpath(DOCUMENT_ROOT): " . ($docRoot !== '' ? (realpath($docRoot) ?: '(false)') : '(n/a)') . "\n";

echo "\n";
if ($envValue !== '' && $envValue[0] === '/') {
    $real = realpath($envValue);
    if ($real !== false) {
        $documentRoot = $docRoot !== '' ? realpath($docRoot) : realpath(dirname(__DIR__));
        if ($documentRoot !== false) {
            $inside = $real === $documentRoot
                || strpos($real, rtrim($documentRoot, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR) === 0;
            echo "Is the config file INSIDE document root (would be rejected)?: " . ($inside ? 'YES - this is the problem' : 'no') . "\n";
        } else {
            echo "Could not resolve a document root to compare against.\n";
        }
    }
}

echo "\nmod_env active (SetEnv worked) if the first line above is not '(empty)'.\n";

echo "\n--- extra diagnostics ---\n";
echo "open_basedir: " . (ini_get('open_basedir') ?: '(not set)') . "\n";

$candidates = [
    '/home/users/2/main.jp-d-neko',
    '/home/users/2/main.jp-d-neko/web',
    '/home/users/2/main.jp-d-neko/web/029_yafuso',
    '/home/users/2/main.jp-d-neko/analytics.config.php',
];
foreach ($candidates as $path) {
    if (is_dir($path)) {
        $entries = @scandir($path);
        echo "\nscandir($path):\n";
        if ($entries === false) {
            echo "  (scandir failed - likely blocked by open_basedir)\n";
        } else {
            foreach ($entries as $e) {
                if ($e === '.' || $e === '..') continue;
                echo "  - $e\n";
            }
        }
    } elseif (is_file($path)) {
        echo "\n$path is a FILE (exists, " . filesize($path) . " bytes, readable=" . (is_readable($path) ? 'yes' : 'no') . ")\n";
    } else {
        echo "\n$path : not accessible (is_dir/is_file both false - either missing, or blocked by open_basedir)\n";
    }
}
