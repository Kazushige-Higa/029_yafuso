<?php
declare(strict_types=1);

require_once __DIR__ . '/common.php';
require_once __DIR__ . '/runtime_storage.php';

header('Content-Type: application/xml; charset=UTF-8');
header('Cache-Control: public, max-age=900');

/**
 * Return the public origin for the current environment.
 *
 * SITE_URL can be set on the server to override the production URL.
 */
function yafuso_sitemap_base_url(): string
{
    $configured = trim((string) getenv('SITE_URL'));
    if ($configured !== '' && filter_var($configured, FILTER_VALIDATE_URL)) {
        return rtrim($configured, '/');
    }

    $host = trim((string) ($_SERVER['HTTP_HOST'] ?? ''));
    if (!preg_match('/^(?:[A-Za-z0-9.-]+|\[[0-9A-Fa-f:]+\])(?::[0-9]+)?$/', $host)) {
        $host = '';
    }

    $isLocal = preg_match('/^(?:localhost|127\.0\.0\.1|\[::1\])(?::\d+)?$/i', $host) === 1;
    if (!$isLocal) {
        return 'https://yafuso-yataimura.com';
    }

    $forwardedProto = strtolower(trim((string) ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '')));
    $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || $forwardedProto === 'https';

    return ($isHttps ? 'https://' : 'http://') . ($host ?: 'localhost');
}

/**
 * Convert a file modification time or API date into W3C datetime format.
 */
function yafuso_sitemap_lastmod($value): ?string
{
    if ($value === null || $value === '') {
        return null;
    }

    try {
        if (is_int($value)) {
            return gmdate(DATE_ATOM, $value);
        }

        return (new DateTimeImmutable((string) $value))->format(DATE_ATOM);
    } catch (Throwable $exception) {
        return null;
    }
}

/**
 * Return the newer of two W3C datetime values.
 */
function yafuso_sitemap_newer(?string $current, ?string $candidate): ?string
{
    if ($candidate === null) {
        return $current;
    }
    if ($current === null) {
        return $candidate;
    }

    return strtotime($candidate) > strtotime($current) ? $candidate : $current;
}

/**
 * Add a URL while keeping its most recent lastmod value.
 *
 * @param array<string, ?string> $urls
 */
function yafuso_sitemap_add(array &$urls, string $path, ?string $lastmod = null): void
{
    $baseUrl = yafuso_sitemap_base_url();
    $location = $path === '/' ? $baseUrl . '/' : $baseUrl . '/' . ltrim($path, '/');
    $urls[$location] = yafuso_sitemap_newer($urls[$location] ?? null, $lastmod);
}

/**
 * Read a local file's modification time.
 */
function yafuso_sitemap_file_lastmod(string $filename): ?string
{
    $path = __DIR__ . '/' . $filename;
    $modified = is_file($path) ? filemtime($path) : false;

    return $modified === false ? null : yafuso_sitemap_lastmod($modified);
}

$urls = [];
$staticPages = [
    '/' => 'index.php',
    '/concept.php' => 'concept.php',
    '/market_stalls.php' => 'market_stalls.php',
    '/karaoke.php' => 'karaoke.php',
    '/vendors.php' => 'vendors.php',
];

foreach ($staticPages as $urlPath => $filename) {
    yafuso_sitemap_add($urls, $urlPath, yafuso_sitemap_file_lastmod($filename));
}

// 一覧ページはmicroCMSの有効化・API応答に関係なく公開されている。
yafuso_sitemap_add($urls, '/news/');
yafuso_sitemap_add($urls, '/works/');

$microcmsEnabled = (bool) ($microcms_enabled ?? false);
$microcmsConfigured = trim((string) ($microcms_service_id ?? '')) !== ''
    && trim((string) ($microcms_api_key ?? '')) !== '';
$runtimeStorageDir = yafuso_runtime_storage_dir();
$sitemapCacheFile = $runtimeStorageDir !== '' ? $runtimeStorageDir . '/sitemap.xml' : '';
$canCacheSitemap = $sitemapCacheFile !== '';
$cmsFetchFailed = false;

if ($microcmsEnabled && !$microcmsConfigured) {
    if ($sitemapCacheFile !== '' && is_file($sitemapCacheFile)) {
        header('X-Sitemap-Cache: stale');
        readfile($sitemapCacheFile);
        exit;
    }

    // 資格情報が未投入の初期状態でも、静的ページだけで有効なXMLを返す。
    $microcmsEnabled = false;
}

if ($microcmsEnabled && $microcmsConfigured) {
    $contentTypes = [
        'blog' => '/blog',
        'works' => '/works',
    ];
    $latestCmsLastmod = null;

    foreach ($contentTypes as $type => $endpoint) {
        $offset = 0;
        $limit = 100;
        $latestTypeLastmod = null;

        do {
            $query = http_build_query(
                [
                    'limit' => $limit,
                    'offset' => $offset,
                    'orders' => '-revisedAt',
                    'fields' => 'id,publishedAt,revisedAt,updatedAt',
                ],
                '',
                '&',
                PHP_QUERY_RFC3986
            );
            $response = microcms_get($endpoint . '?' . $query);

            if (
                $response === null
                || !isset($response->contents)
                || !is_array($response->contents)
            ) {
                $cmsFetchFailed = true;
                break 2;
            }

            foreach ($response->contents as $content) {
                $contentId = trim((string) ($content->id ?? ''));
                if ($contentId === '') {
                    continue;
                }

                $lastmod = yafuso_sitemap_lastmod(
                    $content->revisedAt
                    ?? $content->publishedAt
                    ?? $content->updatedAt
                    ?? null
                );
                $entryPath = ($type === 'works' ? '/works/' : '/news/') . rawurlencode($contentId);

                yafuso_sitemap_add($urls, $entryPath, $lastmod);
                $latestTypeLastmod = yafuso_sitemap_newer($latestTypeLastmod, $lastmod);
                $latestCmsLastmod = yafuso_sitemap_newer($latestCmsLastmod, $lastmod);
            }

            $received = count($response->contents);
            $offset += $received;
            $totalCount = isset($response->totalCount) ? (int) $response->totalCount : $offset;
        } while ($received === $limit && $offset < $totalCount);

        yafuso_sitemap_add(
            $urls,
            $type === 'works' ? '/works/' : '/news/',
            $latestTypeLastmod
        );
    }

    // トップページに新着を掲載する場合も最新記事の更新日を反映する
    if (!$cmsFetchFailed) {
        yafuso_sitemap_add($urls, '/', $latestCmsLastmod);
    }
}

if ($cmsFetchFailed) {
    if (is_file($sitemapCacheFile)) {
        header('X-Sitemap-Cache: stale');
        readfile($sitemapCacheFile);
        exit;
    }

    // キャッシュ保存先が未設定なら、静的URLを返して公開sitemapを停止させない。
    if ($sitemapCacheFile === '') {
        $cmsFetchFailed = false;
    } else {
        http_response_code(503);
        header('Retry-After: 900');
        exit;
    }
}

$xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
$xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

foreach ($urls as $location => $lastmod) {
    $xml .= "  <url>\n";
    $xml .= '    <loc>' . htmlspecialchars($location, ENT_XML1 | ENT_QUOTES, 'UTF-8') . "</loc>\n";
    if ($lastmod !== null) {
        $xml .= '    <lastmod>' . htmlspecialchars($lastmod, ENT_XML1 | ENT_QUOTES, 'UTF-8') . "</lastmod>\n";
    }
    $xml .= "  </url>\n";
}

$xml .= "</urlset>\n";

if ($microcmsEnabled && $microcmsConfigured && $canCacheSitemap) {
    $cacheDir = dirname($sitemapCacheFile);
    if (!is_dir($cacheDir)) {
        @mkdir($cacheDir, 0755, true);
    }
    $temporaryFile = @tempnam($cacheDir, 'sitemap-');
    if ($temporaryFile !== false && @file_put_contents($temporaryFile, $xml, LOCK_EX) !== false) {
        @chmod($temporaryFile, 0640);
        @rename($temporaryFile, $sitemapCacheFile);
    } elseif ($temporaryFile !== false) {
        @unlink($temporaryFile);
    }
}

echo $xml;
