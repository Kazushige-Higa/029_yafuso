<?php
/**
 * Core Web Vitals 取得 API (cwv_api.php)
 *
 * Google PageSpeed Insights API (v5) から Lighthouse のパフォーマンス
 * 計測結果を取得して JSON で返します。APIキー不要・無料。
 * ga4_api.php と同様、cURL のみで動作します（共有レンタルサーバー対応）。
 *
 * PSI の計測は1URLあたり20〜30秒かかるため、結果はURL単位で
 * 24時間キャッシュし、未取得分だけを都度計測します。
 * （タイムアウトで途中終了しても、取得済み分は保存されます）
 *
 * 使い方:
 *   GET cwv_api.php            … キャッシュ優先。未取得URLのみ計測
 *   GET cwv_api.php?refresh=1  … 全URLを強制再計測
 */

ob_start();
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
require __DIR__ . '/auth.php';

/* ===================== 設定 ===================== */
$analyticsConfig = require __DIR__ . '/config.php';
$siteUrl = trim((string)($analyticsConfig['site_url'] ?? ''));
if (
    $siteUrl === ''
    || filter_var($siteUrl, FILTER_VALIDATE_URL) === false
    || !preg_match('/^https?:\/\//i', $siteUrl)
) {
    http_response_code(503);
    echo json_encode(['error' => '分析対象サイトURLが未設定または不正です。'], JSON_UNESCAPED_UNICODE);
    exit;
}
$siteUrl = rtrim($siteUrl, '/');
$TARGETS = [
    ['url' => $siteUrl . '/',                   'label' => 'トップページ'],
    ['url' => $siteUrl . '/karaoke.php',        'label' => 'カラオケ大会'],
    ['url' => $siteUrl . '/vendors.php',        'label' => '出店者募集'],
];
$STRATEGY    = 'mobile';   // 'mobile' または 'desktop'（Googleの評価はモバイル基準）
$PSI_API_KEY = '';         // 任意。Google Cloud でキーを発行すると呼び出し制限が緩和される
$CACHE_TTL   = 86400;      // キャッシュ有効秒数(24時間)
$storageDir  = $analyticsConfig['analytics_storage_dir'];
$CACHE_FILE  = $storageDir . '/cwv_cache.json';
/* ================================================ */

if ($storageDir === '' || !is_dir($storageDir)) {
    http_response_code(503);
    echo json_encode(['error' => '分析データ保存先が未設定です。'], JSON_UNESCAPED_UNICODE);
    exit;
}

@set_time_limit(300);

$force = isset($_GET['refresh']) && $_GET['refresh'] == '1';

/* ---- キャッシュ読み込み（URLごとに fetched_at を持つ） ---- */
$cache = [];
if (file_exists($CACHE_FILE)) {
    $c = json_decode(file_get_contents($CACHE_FILE), true);
    if (is_array($c)) $cache = $c;
}

$results = [];
$now = time();

foreach ($TARGETS as $t) {
    $key = $STRATEGY . ' ' . $t['url'];
    $entry = $cache[$key] ?? null;

    // 有効なキャッシュがあればそれを使う（エラー結果は1時間で再試行）
    if (!$force && is_array($entry) && isset($entry['fetched_at'])) {
        $ttl = isset($entry['error']) ? 3600 : $CACHE_TTL;
        if ($now - $entry['fetched_at'] < $ttl) {
            $results[] = $entry['result'];
            continue;
        }
    }

    // PSI 計測
    $result = cwv_fetch_psi($t['url'], $t['label'], $STRATEGY, $PSI_API_KEY);
    $cache[$key] = [
        'fetched_at' => $now,
        'result'     => $result,
    ];
    if (isset($result['error'])) {
        $cache[$key]['error'] = true;
    }
    // 1件取得するごとに書き込み（タイムアウト対策）
    cwv_atomic_write($CACHE_FILE, json_encode($cache, JSON_UNESCAPED_UNICODE));

    $results[] = $result;
}

echo json_encode([
    'strategy'     => $STRATEGY,
    'generated_at' => date('c'),
    'results'      => $results,
], JSON_UNESCAPED_UNICODE);
exit;

function cwv_atomic_write($path, $contents)
{
    $directory = dirname($path);
    $temporary = @tempnam($directory, 'cwv-');
    if ($temporary === false) {
        return false;
    }
    if (@file_put_contents($temporary, $contents, LOCK_EX) === false) {
        @unlink($temporary);
        return false;
    }
    @chmod($temporary, 0600);
    if (!@rename($temporary, $path)) {
        @unlink($temporary);
        return false;
    }
    return true;
}


/* ==================================================================
 * PageSpeed Insights API 呼び出し
 * ================================================================== */
function cwv_fetch_psi($url, $label, $strategy, $api_key)
{
    $endpoint = 'https://www.googleapis.com/pagespeedonline/v5/runPagespeed'
        . '?url=' . urlencode($url)
        . '&strategy=' . urlencode($strategy)
        . '&category=performance'
        . '&locale=ja';
    if ($api_key !== '') {
        $endpoint .= '&key=' . urlencode($api_key);
    }

    $base = ['url' => $url, 'label' => $label, 'strategy' => $strategy];

    $ch = curl_init($endpoint);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 90,
        CURLOPT_CONNECTTIMEOUT => 10,
    ]);
    $res  = curl_exec($ch);
    if ($res === false) {
        $err = curl_error($ch);
        curl_close($ch);
        return $base + ['error' => '通信エラー: ' . $err];
    }
    curl_close($ch);

    $j = json_decode($res, true);
    if (json_last_error() !== JSON_ERROR_NONE || !is_array($j)) {
        return $base + ['error' => 'JSON解析失敗'];
    }
    if (isset($j['error'])) {
        return $base + ['error' => ($j['error']['message'] ?? 'APIエラー')];
    }

    $lh     = $j['lighthouseResult'] ?? [];
    $audits = $lh['audits'] ?? [];
    $score  = (int)round((float)($lh['categories']['performance']['score'] ?? 0) * 100);

    $result = $base + [
        'score' => $score,
        'lcp'   => (float)($audits['largest-contentful-paint']['numericValue'] ?? 0), // ms
        'cls'   => round((float)($audits['cumulative-layout-shift']['numericValue'] ?? 0), 3),
        'tbt'   => (float)($audits['total-blocking-time']['numericValue'] ?? 0),      // ms
        'fcp'   => (float)($audits['first-contentful-paint']['numericValue'] ?? 0),   // ms
    ];

    // CrUX フィールドデータ（実ユーザー計測）があれば付与。少トラフィックのサイトでは通常 null
    if (!empty($j['loadingExperience']['metrics'])) {
        $m = $j['loadingExperience']['metrics'];
        $result['field'] = [
            'lcp_category' => $m['LARGEST_CONTENTFUL_PAINT_MS']['category'] ?? null,
            'inp_category' => $m['INTERACTION_TO_NEXT_PAINT']['category'] ?? null,
            'cls_category' => $m['CUMULATIVE_LAYOUT_SHIFT_SCORE']['category'] ?? null,
        ];
    }

    return $result;
}
