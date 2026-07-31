<?php
/**
 * visitor_tracker.php — 個別訪問者トラッキング API
 *
 * POST  { visitor_id, event, page, value }  → イベントを記録してスコアを返す
 * GET   ?action=stats                        → ダッシュボード用集計データを返す（内部のみ）
 *
 * データ: 公開ディレクトリ外のruntime storage
 */

require_once __DIR__ . '/runtime_storage.php';

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');

if (session_status() !== PHP_SESSION_ACTIVE) {
    $is_https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || strtolower((string)($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '')) === 'https';
    session_start([
        'cookie_httponly' => true,
        'cookie_secure' => $is_https,
        'cookie_samesite' => 'Lax',
        'use_strict_mode' => true,
    ]);
}

/* ---- 設定 ---- */
$runtimeStorageDir = yafuso_runtime_storage_dir();
if ($runtimeStorageDir === '') {
    http_response_code(503);
    echo json_encode(['error' => 'Visitor storage is not configured']);
    exit;
}
define('VT_DIR',        $runtimeStorageDir . '/visitors');
define('VT_LEGACY_FILE', $runtimeStorageDir . '/visitors.json');
define('VT_MAX_EVENTS', 200);   // 1訪問者あたりのイベント上限
define('VT_EXPIRE_DAYS', 90);   // この日数以上アクセスなしで削除
define('VT_MAX_VISITORS', 5000);
define('VT_RATE_LIMIT', 30);    // 1セッション・1分あたりのイベント上限
define('VT_IP_RATE_LIMIT', 120); // 1IP・1分あたりのイベント上限
define('VT_CAPACITY_LOCK_FILE', $runtimeStorageDir . '/visitors.capacity.lock');
define('VT_IP_RATE_DIR', $runtimeStorageDir . '/visitor-rate');

/* ---- スコア配点 ---- */
const SCORE_MAP = [
    'pageview'          => 1,
    'concept_page'      => 6,
    'market_page'       => 8,
    'karaoke_page'      => 12,
    'vendors_page'      => 12,
    'scroll_50'         => 3,
    'scroll_90'         => 5,
    'stay_60'           => 5,    // 60秒以上滞在
    'stay_120'          => 8,    // 120秒以上
    'revisit_7d'        => 10,   // 7日以内再訪
    'form_start'        => 8,    // フォーム入力開始
    'application_click' => 15,
    'form_submit'       => 40,
];

/* ---- ティア判定 ---- */
function score_tier(int $score): array {
    if ($score >= 80) return ['tier' => 'hot',  'label' => '🔥 Hot',  'color' => '#ef4444'];
    if ($score >= 50) return ['tier' => 'warm', 'label' => '☀️ Warm', 'color' => '#f59e0b'];
    if ($score >= 20) return ['tier' => 'cool', 'label' => '🌡️ Cool', 'color' => '#3b82f6'];
    return              ['tier' => 'cold', 'label' => '❄️ Cold', 'color' => '#94a3b8'];
}

/* ---- 訪問者ごとのファイル読み書き ---- */
function vt_ensure_dir(): void {
    if (!is_dir(VT_DIR) && !mkdir(VT_DIR, 0750, true) && !is_dir(VT_DIR)) {
        throw new RuntimeException('Failed to create visitor data directory.');
    }
}

function vt_visitor_path(string $vid): string {
    return VT_DIR . '/' . hash('sha256', $vid) . '.json';
}

function vt_lock(string $vid, int $operation) {
    vt_ensure_dir();
    $handle = fopen(vt_visitor_path($vid) . '.lock', 'c');
    if ($handle === false || !flock($handle, $operation)) {
        if (is_resource($handle)) fclose($handle);
        throw new RuntimeException('Failed to lock visitor data.');
    }
    return $handle;
}

function vt_unlock($handle): void {
    if (is_resource($handle)) {
        flock($handle, LOCK_UN);
        fclose($handle);
    }
}

function vt_capacity_lock()
{
    vt_ensure_dir();
    $handle = fopen(VT_CAPACITY_LOCK_FILE, 'c');
    if ($handle === false || !flock($handle, LOCK_EX)) {
        if (is_resource($handle)) fclose($handle);
        throw new RuntimeException('Failed to lock visitor capacity.');
    }
    return $handle;
}

function vt_legacy_lock()
{
    vt_ensure_dir();
    $handle = fopen(VT_LEGACY_FILE . '.lock', 'c');
    if ($handle === false || !flock($handle, LOCK_EX)) {
        if (is_resource($handle)) fclose($handle);
        throw new RuntimeException('Failed to lock legacy visitor data.');
    }
    return $handle;
}

function vt_ip_rate_allowed(int $now, string $sessionToken): bool
{
    if (!is_dir(VT_IP_RATE_DIR) && !mkdir(VT_IP_RATE_DIR, 0750, true) && !is_dir(VT_IP_RATE_DIR)) {
        return false;
    }

    $remoteAddress = trim((string)($_SERVER['REMOTE_ADDR'] ?? 'unknown'));
    // IP単独では共有NATを巻き込むため、サーバー発行セッションと組み合わせる。
    $ratePath = VT_IP_RATE_DIR . '/' . hash('sha256', $remoteAddress . '|' . $sessionToken) . '.json';
    $lockHandle = fopen($ratePath . '.lock', 'c');
    if ($lockHandle === false || !flock($lockHandle, LOCK_EX)) {
        if (is_resource($lockHandle)) fclose($lockHandle);
        return false;
    }

    try {
        $timestamps = array_values(array_filter(
            vt_decode_file($ratePath),
            static fn($timestamp) => is_int($timestamp) && $timestamp > $now - 60
        ));
        if (count($timestamps) >= VT_IP_RATE_LIMIT) {
            return false;
        }

        $timestamps[] = $now;
        $json = json_encode($timestamps, JSON_UNESCAPED_UNICODE);
        $temporaryFile = $json === false ? false : tempnam(VT_IP_RATE_DIR, 'rate-');
        if ($temporaryFile === false || file_put_contents($temporaryFile, $json, LOCK_EX) === false || !rename($temporaryFile, $ratePath)) {
            if (is_string($temporaryFile) && file_exists($temporaryFile)) unlink($temporaryFile);
            return false;
        }
        @chmod($ratePath, 0640);
        return true;
    } finally {
        flock($lockHandle, LOCK_UN);
        fclose($lockHandle);
    }
}

function vt_decode_file(string $filename): array {
    if (!is_file($filename)) return [];
    $json = file_get_contents($filename);
    $data = $json === false ? null : json_decode($json, true);
    return is_array($data) ? $data : [];
}

function vt_load(string $vid): array {
    $current = vt_decode_file(vt_visitor_path($vid));
    if ($current !== []) return $current;

    // 旧版の単一JSONからは読み取りのみ行い、次回更新時に個別ファイルへ移行する。
    $legacy = vt_decode_file(VT_LEGACY_FILE);
    return is_array($legacy[$vid] ?? null) ? $legacy[$vid] : [];
}

function vt_save(string $vid, array $data): void {
    vt_ensure_dir();
    $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    if ($json === false) {
        throw new RuntimeException('Failed to encode visitor data.');
    }

    $path = vt_visitor_path($vid);
    $tempFile = tempnam(VT_DIR, 'visitor-');
    if ($tempFile === false || file_put_contents($tempFile, $json, LOCK_EX) === false || !rename($tempFile, $path)) {
        if (is_string($tempFile) && file_exists($tempFile)) unlink($tempFile);
        throw new RuntimeException('Failed to save visitor data.');
    }
    @chmod($path, 0640);
}

function vt_load_all(): array {
    vt_ensure_dir();
    $data = [];
    foreach (glob(VT_DIR . '/*.json') ?: [] as $filename) {
        $visitor = vt_decode_file($filename);
        $visitorId = (string)($visitor['visitor_id'] ?? '');
        if ($visitorId !== '') {
            $data[$visitorId] = $visitor;
        }
    }

    // 既存の単一JSONも表示対象に含める（個別ファイル側を優先）。
    foreach (vt_decode_file(VT_LEGACY_FILE) as $visitorId => $visitor) {
        if (!isset($data[$visitorId]) && is_array($visitor)) {
            $data[$visitorId] = $visitor;
        }
    }
    return $data;
}

function vt_is_same_origin_request(): bool {
    $host = strtolower(trim((string)($_SERVER['HTTP_HOST'] ?? '')));
    if ($host === '' || !preg_match('/^[a-z0-9.\-:\[\]]+$/', $host)) {
        return false;
    }

    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || strtolower((string)($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '')) === 'https'
        ? 'https'
        : 'http';
    $expected = $scheme . '://' . $host;

    $source = trim((string)($_SERVER['HTTP_ORIGIN'] ?? ''));
    if ($source === '') {
        $source = trim((string)($_SERVER['HTTP_REFERER'] ?? ''));
    }
    if ($source === '') {
        return false;
    }

    $sourceParts = parse_url($source);
    $expectedParts = parse_url($expected);
    if (!is_array($sourceParts) || !is_array($expectedParts)) {
        return false;
    }

    $sourceScheme = strtolower((string)($sourceParts['scheme'] ?? ''));
    $sourceHost = strtolower((string)($sourceParts['host'] ?? ''));
    $expectedScheme = strtolower((string)($expectedParts['scheme'] ?? ''));
    $expectedHost = strtolower((string)($expectedParts['host'] ?? ''));
    $sourcePort = (int)($sourceParts['port'] ?? ($sourceScheme === 'https' ? 443 : 80));
    $expectedPort = (int)($expectedParts['port'] ?? ($expectedScheme === 'https' ? 443 : 80));

    return $sourceScheme === $expectedScheme
        && $sourceHost === $expectedHost
        && $sourcePort === $expectedPort;
}

/* ---- 期限切れ訪問者を削除 ---- */
function vt_cleanup(array &$data): void {
    $threshold = time() - (VT_EXPIRE_DAYS * 86400);
    $legacyLock = vt_legacy_lock();
    try {
    $legacy = vt_decode_file(VT_LEGACY_FILE);
    $legacyChanged = false;
    foreach ($data as $vid => $v) {
        if (($v['last_seen_ts'] ?? 0) < $threshold) {
            $visitorId = (string) $vid;
            if ($visitorId === '') {
                unset($data[$vid]);
                continue;
            }

            // 削除直前に訪問者単位で再読込し、同時更新を消さない。
            $lockHandle = vt_lock($visitorId, LOCK_EX);
            try {
                $current = vt_load($visitorId);
                if (($current['last_seen_ts'] ?? 0) >= $threshold) {
                    $data[$vid] = $current;
                    continue;
                }

                $visitorFile = vt_visitor_path($visitorId);
                if (is_file($visitorFile)) {
                    @unlink($visitorFile);
                }
                if (array_key_exists($visitorId, $legacy)) {
                    unset($legacy[$visitorId]);
                    $legacyChanged = true;
                }
                unset($data[$vid]);
            } finally {
                vt_unlock($lockHandle);
            }
        }
    }

    if ($legacyChanged) {
        $json = json_encode($legacy, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        $temporaryFile = $json === false ? false : tempnam(dirname(VT_LEGACY_FILE), 'legacy-');
        if ($temporaryFile !== false && file_put_contents($temporaryFile, $json, LOCK_EX) !== false) {
            @chmod($temporaryFile, 0640);
            @rename($temporaryFile, VT_LEGACY_FILE);
        } elseif ($temporaryFile !== false) {
            @unlink($temporaryFile);
        }
    }
    } finally {
        vt_unlock($legacyLock);
    }
}

/* リファラ → 流入元ラベル（初回のみ有効） */
function vt_classify_source(string $ref): string {
    if ($ref === '') return '直接/ブックマーク';
    $host = strtolower(parse_url($ref, PHP_URL_HOST) ?: '');
    if ($host === '' ) return '直接/ブックマーク';
    $current_host = strtolower(preg_replace('/:\d+$/', '', (string) ($_SERVER['HTTP_HOST'] ?? '')));
    if ($current_host !== '' && $host === $current_host) return '直接/ブックマーク'; // 内部遷移
    $map = [
        'google.'    => 'Google検索', 'bing.' => 'Bing検索', 'yahoo.' => 'Yahoo検索',
        'duckduckgo' => 'DuckDuckGo検索',
        'instagram.' => 'Instagram', 'cktrack' => 'Instagram',
        'facebook.'  => 'Facebook', 'fb.'     => 'Facebook',
        't.co'       => 'X(Twitter)', 'twitter.' => 'X(Twitter)', '//x.com' => 'X(Twitter)',
        'youtube.'   => 'YouTube', 'youtu.be' => 'YouTube',
        'line.'      => 'LINE', 'lin.ee'   => 'LINE',
        'tiktok.'    => 'TikTok',
        'note.com'   => 'note', 'ameblo'   => 'アメブロ',
    ];
    foreach ($map as $needle => $label) {
        if (strpos($host, ltrim($needle, '/')) !== false) return $label;
    }
    return 'その他サイト';
}

/* User-Agent → デバイス種別 */
function vt_device(string $ua): string {
    if ($ua === '') return '不明';
    if (preg_match('/iPad|Tablet|Nexus 7|Nexus 10/i', $ua)) return 'タブレット';
    if (preg_match('/Mobile|Android|iPhone|iPod|Windows Phone/i', $ua)) return 'モバイル';
    return 'PC';
}

/* 閲覧履歴 → 行動ラベル（購入検討度の傾向を要約） */
function vt_behavior_label(array $v): string {
    $joined = implode(' ', array_column($v['top_pages'] ?? [], 'p'));
    $tags = [];
    if (preg_match('#/karaoke#', $joined))                   $tags[] = 'カラオケ参加検討';
    if (preg_match('#/vendors#', $joined))                   $tags[] = '出店検討';
    if (preg_match('#/market_stalls#', $joined))             $tags[] = '屋台情報確認';
    if (preg_match('#/concept#', $joined))                   $tags[] = 'コンセプト確認';
    return $tags ? implode('・', array_slice($tags, 0, 3)) : '閲覧中心';
}

/* ================================================================
 * GET ?action=stats  — 認証済みダッシュボードからの内部呼び出しのみ
 * ================================================================ */
if (
    $_SERVER['REQUEST_METHOD'] === 'GET'
    && ($_GET['action'] ?? '') === 'stats'
    && defined('YAFUSO_ANALYTICS_INTERNAL')
    && YAFUSO_ANALYTICS_INTERNAL === true
) {
    // 個別ファイルをatomic renameで読むため、全訪問者を単一ロックで直列化しない。
    $data = vt_load_all();
    vt_cleanup($data);

    $dist  = ['hot' => 0, 'warm' => 0, 'cool' => 0, 'cold' => 0];
    $hot_list = [];
    $total_score = 0;
    $total_count = 0;

    foreach ($data as $vid => $v) {
        $score = (int)($v['score'] ?? 0);
        $t     = score_tier($score)['tier'];
        $dist[$t]++;
        $total_score += $score;
        $total_count++;
        if ($t === 'hot' || $t === 'warm') {
            $hot_list[] = [
                'id'        => substr($vid, 0, 8) . '…',   // IDを短縮表示
                'score'     => $score,
                'tier'      => $t,
                'sessions'  => (int)($v['sessions'] ?? 1),
                'last_seen' => $v['last_seen'] ?? '',
                'top_pages' => array_slice($v['top_pages'] ?? [], 0, 3),
                // ── 行動属性（匿名のまま「どんな見込み客か」を把握）──
                'source'     => $v['source'] ?? '不明',
                'device'     => $v['device'] ?? '不明',
                'first_seen' => $v['first_seen'] ?? '',
                'label'      => vt_behavior_label($v),
            ];
        }
    }

    usort($hot_list, fn($a, $b) => $b['score'] <=> $a['score']);
    $hot_list = array_slice($hot_list, 0, 15);

    echo json_encode([
        'total'      => $total_count,
        'avg_score'  => $total_count ? round($total_score / $total_count, 1) : 0,
        'dist'       => $dist,
        'hot_list'   => $hot_list,
    ]);
    exit;
}

/* ================================================================
 * POST — イベント記録
 * ================================================================ */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(404);
    echo json_encode(['error' => 'Not found']);
    exit;
}

if (!vt_is_same_origin_request()) {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden']);
    exit;
}

$fetchSite = strtolower((string)($_SERVER['HTTP_SEC_FETCH_SITE'] ?? ''));
if ($fetchSite !== '' && !in_array($fetchSite, ['same-origin', 'same-site'], true)) {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden']);
    exit;
}

$rawBody = file_get_contents('php://input');
if ($rawBody === false || strlen($rawBody) > 4096) {
    http_response_code(413);
    echo json_encode(['error' => 'Payload too large']);
    exit;
}

$body = json_decode($rawBody, true);
if (!is_array($body)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON']);
    exit;
}

$rawVid = $body['visitor_id'] ?? '';
$rawEvent = $body['event'] ?? '';
$rawPage = $body['page'] ?? '';
$rawRef = $body['ref'] ?? '';
$rawToken = $body['token'] ?? '';
$rawFormToken = $body['form_token'] ?? '';
if (
    !is_scalar($rawVid)
    || !is_scalar($rawEvent)
    || !is_scalar($rawPage)
    || !is_scalar($rawRef)
    || !is_scalar($rawToken)
    || !is_scalar($rawFormToken)
) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid fields']);
    exit;
}

$vid   = substr(preg_replace('/[^a-zA-Z0-9\-_]/', '', (string)$rawVid), 0, 64);
$event = preg_replace('/[^a-zA-Z0-9_]/', '', (string)$rawEvent);
$pagePath = parse_url((string)$rawPage, PHP_URL_PATH);
$page  = is_string($pagePath) ? substr($pagePath, 0, 200) : '';
$ref   = substr(strip_tags((string)$rawRef), 0, 300);
$token = (string)$rawToken;
$formToken = (string)$rawFormToken;
$ua    = $_SERVER['HTTP_USER_AGENT'] ?? '';
$now   = time();
$today = date('Y-m-d', $now);

$sessionToken = (string)($_SESSION['yafuso_visitor_tracker_token'] ?? '');
if ($sessionToken === '' || !hash_equals($sessionToken, $token)) {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden']);
    exit;
}

if ($event === 'form_submit') {
    $sessionFormToken = (string)($_SESSION['yafuso_visitor_form_submit_token'] ?? '');
    if ($sessionFormToken === '' || !hash_equals($sessionFormToken, $formToken)) {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }
    unset($_SESSION['yafuso_visitor_form_submit_token']);
}

if ($vid === '' || !array_key_exists($event, SCORE_MAP) || $page === '' || $page[0] !== '/') {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid visitor_id, event or page']);
    exit;
}

$rateWindow = array_values(array_filter(
    (array)($_SESSION['yafuso_visitor_tracker_events'] ?? []),
    static fn($timestamp) => is_int($timestamp) && $timestamp > $now - 60
));
if (count($rateWindow) >= VT_RATE_LIMIT) {
    http_response_code(429);
    header('Retry-After: 60');
    echo json_encode(['error' => 'Too many requests']);
    exit;
}
$ipRateAllowed = vt_ip_rate_allowed($now, $token);
if (!$ipRateAllowed) {
    http_response_code(429);
    header('Retry-After: 60');
    echo json_encode(['error' => 'Too many requests']);
    exit;
}
$rateWindow[] = $now;
$_SESSION['yafuso_visitor_tracker_events'] = $rateWindow;
session_write_close();

// アクセス解析ダッシュボード自身の閲覧はPV・熱量スコアに含めない。
if (strpos($page, '/analytics') === 0) {
    echo json_encode(['ignored' => true]);
    exit;
}

/* ---- スコア加算量を決定 ---- */
$add = SCORE_MAP[$event] ?? 0;

/* ---- 訪問者単位の排他ロック内で読み込み・更新・保存 ---- */
$capacityHandle = null;
$capacityCount = null;
if (!is_file(vt_visitor_path($vid))) {
    // 新規訪問者の容量判定だけを全体ロックで直列化する。
    $capacityHandle = vt_capacity_lock();
}
if ($capacityHandle !== null) {
    // legacy単一JSONと個別ファイルを重複排除した論理件数で上限判定する。
    $existing = vt_load_all();
    $capacityCount = count($existing);
    if ($capacityCount >= VT_MAX_VISITORS) {
        // 上限判定前に期限切れデータを物理削除する。
        vt_cleanup($existing);
        $capacityCount = count($existing);
    }
}

$lockHandle = vt_lock($vid, LOCK_EX);
$storageFull = false;
try {
    $visitor = vt_load($vid);
    if ($visitor === [] && $capacityHandle === null) {
        // cleanupとの競合で初回ファイルが消えた場合も、容量判定を全体ロック下で再実行する。
        vt_unlock($lockHandle);
        $lockHandle = null;
        $capacityHandle = vt_capacity_lock();
        $existing = vt_load_all();
        $capacityCount = count($existing);
        if ($capacityCount >= VT_MAX_VISITORS) {
            vt_cleanup($existing);
            $capacityCount = count($existing);
        }
        $lockHandle = vt_lock($vid, LOCK_EX);
        $visitor = vt_load($vid);
    }
    if ($visitor === []) {
        if (($capacityCount ?? VT_MAX_VISITORS) >= VT_MAX_VISITORS) {
            throw new RuntimeException('Visitor storage is full.');
        }
        $visitor = [
            'visitor_id'    => $vid,
            'first_seen'    => date('c', $now),
            'first_seen_ts' => $now,
            'last_seen'     => date('c', $now),
            'last_seen_ts'  => $now,
            'sessions'      => 1,
            'last_session'  => $today,
            'score'         => 0,
            'events'        => [],
            'top_pages'     => [],
            // 個人を特定しない範囲で初回訪問時に確定
            'source'        => vt_classify_source($ref),
            'device'        => vt_device($ua),
        ];
    } else {
        // 既存訪問者の行動属性を補完（旧データ・未取得分のバックフィル）
        if (empty($visitor['device'])) $visitor['device'] = vt_device($ua);
        if (!isset($visitor['source']) || $visitor['source'] === '') {
            $visitor['source'] = vt_classify_source($ref);
        }
        // 再訪問判定
        $last_session = $visitor['last_session'] ?? '';
        if ($last_session !== $today) {
            $visitor['sessions'] = ($visitor['sessions'] ?? 1) + 1;
            $visitor['last_session'] = $today;

            $last_ts = $visitor['last_seen_ts'] ?? 0;
            if ($now - $last_ts <= 7 * 86400 && $event === 'pageview') {
                $add += SCORE_MAP['revisit_7d'];
            }
        }
        $visitor['last_seen']    = date('c', $now);
        $visitor['last_seen_ts'] = $now;
    }

    /* ---- イベント記録 ---- */
    $visitor['score'] = ($visitor['score'] ?? 0) + $add;

    $events = $visitor['events'] ?? [];
    $events[] = [
        'e'  => $event,
        'p'  => $page,
        'ts' => $now,
        's'  => $add,
    ];
    if (count($events) > VT_MAX_EVENTS) {
        $events = array_slice($events, -VT_MAX_EVENTS);
    }
    $visitor['events'] = $events;

    /* ---- よく見たページ TOP5 更新 ---- */
    if ($event === 'pageview') {
        $tp = $visitor['top_pages'] ?? [];
        $found = false;
        foreach ($tp as &$entry) {
            if ($entry['p'] === $page) {
                $entry['n']++;
                $found = true;
                break;
            }
        }
        unset($entry);
        if (!$found) $tp[] = ['p' => $page, 'n' => 1];
        usort($tp, fn($a, $b) => $b['n'] <=> $a['n']);
        $visitor['top_pages'] = array_slice($tp, 0, 5);
    }

    vt_save($vid, $visitor);
    $score = (int)$visitor['score'];
} catch (RuntimeException $exception) {
    if ($exception->getMessage() === 'Visitor storage is full.') {
        $storageFull = true;
    } else {
        throw $exception;
    }
} finally {
    vt_unlock($lockHandle);
    if (is_resource($capacityHandle)) {
        vt_unlock($capacityHandle);
    }
}

if ($storageFull) {
    http_response_code(503);
    echo json_encode(['error' => 'Visitor storage is full']);
    exit;
}

/* ---- レスポンス ---- */
$tier_info = score_tier($score);
echo json_encode([
    'visitor_id' => $vid,
    'score'      => $score,
    'tier'       => $tier_info['tier'],
    'label'      => $tier_info['label'],
    'added'      => $add,
]);
