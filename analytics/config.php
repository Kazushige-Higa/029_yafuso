<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/runtime_storage.php';

function yafuso_load_private_analytics_config(string $filename): array
{
    if ($filename === '' || $filename[0] !== '/') {
        return [];
    }

    $realFile = realpath($filename);
    if ($realFile === false || !is_file($realFile) || !is_readable($realFile)) {
        return [];
    }

    $documentRoot = trim((string)($_SERVER['DOCUMENT_ROOT'] ?? ''));
    $documentRoot = $documentRoot !== '' ? realpath($documentRoot) : realpath(dirname(__DIR__));
    if ($documentRoot === false) {
        return [];
    }

    if (
        $realFile === $documentRoot
        || strpos($realFile, rtrim($documentRoot, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR) === 0
    ) {
        return [];
    }

    $loaded = require $realFile;
    return is_array($loaded) ? $loaded : [];
}

$privateConfigFile = trim((string)(getenv('ANALYTICS_CONFIG_FILE') ?: ''));
$local = yafuso_load_private_analytics_config($privateConfigFile);

$configuredSiteUrl = trim((string) (getenv('SITE_URL') ?: ($local['site_url'] ?? '')));
$searchConsoleSiteUrl = trim((string) (
    getenv('SEARCH_CONSOLE_SITE_URL')
    ?: ($local['search_console_site_url'] ?? '')
));

$analyticsStorageDir = trim((string) (
    getenv('ANALYTICS_STORAGE_DIR')
    ?: ($local['analytics_storage_dir'] ?? '')
));

return [
    'site_name' => trim((string) (getenv('ANALYTICS_SITE_NAME') ?: ($local['site_name'] ?? ''))),
    'site_url' => $configuredSiteUrl !== '' ? rtrim($configuredSiteUrl, '/') . '/' : '',
    'ga4_measurement_id' => trim((string) (getenv('GA4_MEASUREMENT_ID') ?: ($local['ga4_measurement_id'] ?? ''))),
    'ga4_property_id' => trim((string) (getenv('GA4_PROPERTY_ID') ?: ($local['ga4_property_id'] ?? ''))),
    'search_console_site_url' => trim((string) (
        getenv('SEARCH_CONSOLE_SITE_URL')
        ?: $searchConsoleSiteUrl
    )),
    'analytics_username' => trim((string) (
        getenv('ANALYTICS_USERNAME')
        ?: ($local['analytics_username'] ?? '')
    )),
    'analytics_password_hash' => trim((string) (
        getenv('ANALYTICS_PASSWORD_HASH')
        ?: ($local['analytics_password_hash'] ?? '')
    )),
    // サービスアカウント鍵・トークンを置くため、公開root配下は拒否する。
    'analytics_storage_dir' => yafuso_validate_storage_dir($analyticsStorageDir),
];
