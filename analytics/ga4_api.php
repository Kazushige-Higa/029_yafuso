<?php
ob_start(); // 意図しない出力をバッファして headers already sent を防ぐ
/**
 * GA4 ダッシュボード用 データ取得 API (ga4_api.php)
 *
 * Google Analytics 4 Data API (REST) からレポートデータを取得して JSON で返します。
 * Composer / gRPC 不要。PHP の openssl + cURL のみで動作するため、
 * ロリポップ等の共有レンタルサーバーでも利用できます。
 *
 * 仕組み:
 *   1. サービスアカウントの秘密鍵(JSON)で JWT を作成し RS256 署名
 *   2. JWT を Google のトークンendpointに送り、アクセストークンを取得(キャッシュ)
 *   3. GA4 Data API (batchRunReports) を呼び出してレポートを取得
 *   4. 整形した結果を data/ga4/cache.json にキャッシュして JSON 返却
 *
 * 使い方:
 *   GET ga4_api.php            … キャッシュがあればキャッシュ、無ければAPI取得
 *   GET ga4_api.php?refresh=1  … キャッシュを無視して強制再取得
 *
 * 事前準備:
 *   - analytics_storage_dir/service_account.json（公開root外）にサービスアカウント鍵を配置
 *   - 公開root外の ANALYTICS_CONFIG_FILE（またはサーバー環境変数）を設定
 */

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
require __DIR__ . '/auth.php';

/* ===================== 設定 ===================== */
$analyticsConfig = require_once __DIR__ . '/config.php';
$GA4_PROPERTY_ID = $analyticsConfig['ga4_property_id'];            // GA4 プロパティ ID
$GA4_CACHE_DIR   = $analyticsConfig['analytics_storage_dir'];      // web公開領域外
$GA4_KEY_FILE    = $GA4_CACHE_DIR . '/service_account.json';       // サービスアカウント鍵
$CACHE_TTL       = 3600;                                          // キャッシュ有効秒数(1時間)
$SC_SITE_URL     = $analyticsConfig['search_console_site_url'];    // Search Console サイトURL

// コンバージョン(CV)として数えるイベント名。
// GA4管理画面のキーイベント設定に依存せず、ここで定義したイベントの
// 発生数をCV・CVRとして集計する（GA4側でもキーイベント登録を推奨）。
// CV = 実際の申込み導線（フォーム送信＋LINE相談クリック）。
// ※ contact_page_view（ページ到達のみ）はCVから除外し、送信率を正しく反映する。
$KEY_EVENTS = ['form_submit'];

// レポートから除外するページパスの接頭辞（自社ツール・プロトタイプ・テスト階層）
$EXCLUDE_PATH_PREFIXES = ['/analytics'];
/* ================================================ */

if ($GA4_PROPERTY_ID === '') {
    http_response_code(503);
    echo json_encode([
        'error' => 'GA4プロパティIDが未設定です。GA4_PROPERTY_IDまたは公開root外のANALYTICS_CONFIG_FILEを設定してください。',
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

if ($GA4_CACHE_DIR === '' || !is_dir($GA4_CACHE_DIR)) {
    http_response_code(503);
    echo json_encode([
        'error' => '分析データ保存先が未設定です。',
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

if (!is_file($GA4_KEY_FILE)) {
    http_response_code(503);
    echo json_encode([
        'error' => 'GA4サービスアカウント鍵が未設置です。',
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$cache_file = $GA4_CACHE_DIR . '/cache.json';
$token_file = $GA4_CACHE_DIR . '/token.json';

// data/ga4 ディレクトリが無ければ作成
if (!is_dir($GA4_CACHE_DIR)) {
    @mkdir($GA4_CACHE_DIR, 0755, true);
}

/* ---------- キャッシュ応答 ---------- */
$force = isset($_GET['refresh']) && $_GET['refresh'] == '1';
if (!$force && file_exists($cache_file) && (time() - filemtime($cache_file) < $CACHE_TTL)) {
    $cachedJson = file_get_contents($cache_file);
    if ($cachedJson !== false && is_array(json_decode($cachedJson, true))) {
        echo $cachedJson;
        exit;
    }
}

/* ---------- メイン処理 ---------- */
try {
    $token    = ga4_get_access_token($GA4_KEY_FILE, $token_file);
    $sc_token = ga4_get_access_token(
        $GA4_KEY_FILE,
        $GA4_CACHE_DIR . '/sc_token.json',
        'https://www.googleapis.com/auth/webmasters.readonly'
    );
    $data = ga4_build_dashboard($GA4_PROPERTY_ID, $token, $KEY_EVENTS, $EXCLUDE_PATH_PREFIXES);

    // Search Console キーワード・ページ別取得（失敗してもダッシュボードは返す）
    try {
        $data['sc_keywords'] = sc_get_keywords($SC_SITE_URL, $sc_token);
        $data['sc_pages']    = sc_get_pages($SC_SITE_URL, $sc_token);
    } catch (Exception $e) {
        $data['sc_keywords'] = $data['sc_keywords'] ?? [];
        $data['sc_pages']    = $data['sc_pages'] ?? [];
        $data['sc_error']    = $e->getMessage();
    }

    // Search Console のURLに、GA4のページタイトルを付与（ぱっと見で識別しやすく）
    $title_map = $data['_title_map'] ?? [];
    foreach ($data['sc_pages'] as &$sp) {
        $sp['title'] = sc_resolve_title($sp['path'] ?? '', $title_map);
    }
    unset($sp);
    unset($data['_title_map']);   // 内部用のため出力しない

    $data['generated_at'] = date('c');
    $json = json_encode($data, JSON_UNESCAPED_UNICODE);

    // キャッシュ書き込み(失敗してもレスポンスは返す)
    ga4_atomic_write($cache_file, $json);

    echo $json;
} catch (\Throwable $e) {
    ob_clean();
    http_response_code(500);
    error_log('GA4 analytics error: ' . $e->getMessage());
    // エラー時、古いキャッシュがあればそれを返す(フォールバック)
    if (!empty($cache_file) && file_exists($cache_file)) {
        $stale = json_decode(file_get_contents($cache_file), true);
        if (is_array($stale)) {
            $stale['stale'] = true;
            $stale['error'] = '最新データを取得できなかったため、キャッシュを表示しています。';
            ob_end_clean();
            echo json_encode($stale, JSON_UNESCAPED_UNICODE);
            exit;
        }
    }
    ob_end_clean();
    echo json_encode([
        'error' => '分析データを取得できませんでした。',
    ], JSON_UNESCAPED_UNICODE);
}
exit;


/* ==================================================================
 * 認証: サービスアカウント鍵 → アクセストークン
 * ================================================================== */
function ga4_get_access_token($key_file, $token_file, $scope = 'https://www.googleapis.com/auth/analytics.readonly')
{
    // 有効なキャッシュ済みトークンがあれば使う
    if (file_exists($token_file)) {
        $t = json_decode(file_get_contents($token_file), true);
        if (is_array($t) && isset($t['access_token'], $t['expires_at']) && $t['expires_at'] > time() + 60) {
            return $t['access_token'];
        }
    }

    if (!file_exists($key_file)) {
        throw new Exception('サービスアカウント鍵が見つかりません: ' . $key_file);
    }
    $key = json_decode(file_get_contents($key_file), true);
    if (!is_array($key) || empty($key['client_email']) || empty($key['private_key'])) {
        throw new Exception('サービスアカウント鍵の形式が不正です');
    }

    $now = time();
    $header = ['alg' => 'RS256', 'typ' => 'JWT'];
    $claim  = [
        'iss'   => $key['client_email'],
        'scope' => $scope,
        'aud'   => 'https://oauth2.googleapis.com/token',
        'iat'   => $now,
        'exp'   => $now + 3600,
    ];

    $segments = [
        ga4_b64url(json_encode($header)),
        ga4_b64url(json_encode($claim)),
    ];
    $signing_input = implode('.', $segments);

    $signature = '';
    if (!openssl_sign($signing_input, $signature, $key['private_key'], OPENSSL_ALGO_SHA256)) {
        $ssl_err = openssl_error_string() ?: 'unknown';
        throw new Exception('JWTの署名に失敗しました(openssl): ' . $ssl_err);
    }
    $jwt = $signing_input . '.' . ga4_b64url($signature);

    // トークン交換
    $res = ga4_http_post(
        'https://oauth2.googleapis.com/token',
        http_build_query([
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion'  => $jwt,
        ]),
        ['Content-Type: application/x-www-form-urlencoded']
    );
    $tok = json_decode($res, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('トークンレスポンスのJSON解析失敗: ' . $res);
    }
    if (!is_array($tok) || empty($tok['access_token'])) {
        $detail = isset($tok['error_description']) ? $tok['error_description'] : $res;
        throw new Exception('アクセストークンの取得に失敗しました: ' . $detail);
    }

    // トークンをキャッシュ
    ga4_atomic_write($token_file, json_encode([
        'access_token' => $tok['access_token'],
        'expires_at'   => $now + (int)($tok['expires_in'] ?? 3600),
    ]));

    return $tok['access_token'];
}

function ga4_atomic_write($path, $contents)
{
    $directory = dirname($path);
    if (!is_dir($directory)) {
        @mkdir($directory, 0700, true);
    }
    $temporary = @tempnam($directory, 'ga4-');
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
 * GA4 Data API を呼び出してダッシュボード用データを組み立てる
 * ================================================================== */
function ga4_build_dashboard($property_id, $token, $key_events = [], $exclude_prefixes = ['/analytics'])
{
    $base = 'https://analyticsdata.googleapis.com/v1beta/properties/' . $property_id;
    $auth = ['Authorization: Bearer ' . $token, 'Content-Type: application/json'];

    $cur  = [['startDate' => '30daysAgo', 'endDate' => 'yesterday']];
    // 年齢・性別は Google シグナル由来の同意済みユーザーのみが対象になり、
    // 少数データにはプライバシーしきい値が適用されるため、集計期間を広げる。
    $demographics_range = [['startDate' => '364daysAgo', 'endDate' => 'yesterday']];
    $comp = [
        ['startDate' => '30daysAgo', 'endDate' => 'yesterday', 'name' => 'current'],
        ['startDate' => '60daysAgo', 'endDate' => '31daysAgo', 'name' => 'previous'],
    ];
    // ダッシュボード・プロトタイプ・テスト階層のPVをレポートに混入させない。
    $exclude_analytics_pages = ga4_not_prefix_filters('pagePath', $exclude_prefixes);
    $exclude_landing_pages   = ga4_not_prefix_filters('landingPage', $exclude_prefixes);
    // コンバージョン対象イベントの絞り込み（除外パス上のイベントは数えない）
    $key_event_filter = ['andGroup' => ['expressions' => [
        ['filter' => ['fieldName' => 'eventName', 'inListFilter' => ['values' => $key_events]]],
        $exclude_analytics_pages,
    ]]];

    /* ---- バッチ1: KPI合計(当月/前月), PV推移, 流入元, デバイス, 人気ページ ---- */
    $batch1 = ['requests' => [
        // 0: KPI(2期間)
        [
            'dateRanges' => $comp,
            'metrics' => [
                ['name' => 'screenPageViews'],
                ['name' => 'totalUsers'],
                ['name' => 'sessions'],
                ['name' => 'averageSessionDuration'],
                ['name' => 'conversions'],
            ],
            'dimensionFilter' => $exclude_analytics_pages,
        ],
        // 1: PV推移(日別)
        [
            'dateRanges' => $cur,
            'dimensions' => [['name' => 'date']],
            'metrics' => [['name' => 'screenPageViews']],
            'dimensionFilter' => $exclude_analytics_pages,
            'orderBys' => [['dimension' => ['dimensionName' => 'date']]],
        ],
        // 2: 流入元
        [
            'dateRanges' => $cur,
            'dimensions' => [['name' => 'sessionDefaultChannelGroup']],
            'metrics' => [['name' => 'sessions']],
            'dimensionFilter' => $exclude_analytics_pages,
            'orderBys' => [['metric' => ['metricName' => 'sessions'], 'desc' => true]],
            'limit' => 6,
        ],
        // 3: デバイス
        [
            'dateRanges' => $cur,
            'dimensions' => [['name' => 'deviceCategory']],
            'metrics' => [['name' => 'totalUsers']],
            'dimensionFilter' => $exclude_analytics_pages,
            'orderBys' => [['metric' => ['metricName' => 'totalUsers'], 'desc' => true]],
        ],
        // 4: 人気ページ
        [
            'dateRanges' => $cur,
            'dimensions' => [['name' => 'pagePath'], ['name' => 'pageTitle']],
            'metrics' => [['name' => 'screenPageViews']],
            'dimensionFilter' => $exclude_analytics_pages,
            'orderBys' => [['metric' => ['metricName' => 'screenPageViews'], 'desc' => true]],
            'limit' => 10,
        ],
    ]];

    /* ---- バッチ2: 男女比, 年齢, 地域, 検索キーワード, 新規vs継続 ---- */
    $batch2 = ['requests' => [
        // 0: 男女比
        [
            'dateRanges' => $demographics_range,
            'dimensions' => [['name' => 'userGender']],
            'metrics' => [['name' => 'activeUsers']],
        ],
        // 1: 年齢層
        [
            'dateRanges' => $demographics_range,
            'dimensions' => [['name' => 'userAgeBracket']],
            'metrics' => [['name' => 'activeUsers']],
            'orderBys' => [['dimension' => ['dimensionName' => 'userAgeBracket']]],
        ],
        // 2: 地域(日本の都道府県 TOP10)
        [
            'dateRanges' => $cur,
            'dimensions' => [['name' => 'region']],
            'metrics' => [['name' => 'totalUsers']],
            'dimensionFilter' => ['andGroup' => ['expressions' => [
                ['filter' => [
                    'fieldName' => 'country',
                    'stringFilter' => ['value' => 'Japan'],
                ]],
                $exclude_analytics_pages,
            ]]],
            'orderBys' => [['metric' => ['metricName' => 'totalUsers'], 'desc' => true]],
            'limit' => 10,
        ],
        // 3: 検索キーワード（サイト内検索）
        [
            'dateRanges' => $cur,
            'dimensions' => [['name' => 'searchTerm']],
            'metrics'    => [['name' => 'sessions']],
            'orderBys'   => [['metric' => ['metricName' => 'sessions'], 'desc' => true]],
            'limit'      => 10,
        ],
        // 4: 新規vs継続ユーザー
        [
            'dateRanges' => $cur,
            'dimensions' => [['name' => 'newVsReturning']],
            'metrics'    => [['name' => 'totalUsers']],
            'dimensionFilter' => $exclude_analytics_pages,
        ],
    ]];

    /* ---- バッチ3: ページ指標, 流入元×CV, イベント, 週別推移, 月別推移 ---- */
    $batch3 = ['requests' => [
        // 0: ページ別エンゲージメント指標
        [
            'dateRanges' => $cur,
            'dimensions' => [['name' => 'pagePath'], ['name' => 'pageTitle']],
            'metrics'    => [
                ['name' => 'screenPageViews'],
                ['name' => 'engagementRate'],
                ['name' => 'bounceRate'],
                ['name' => 'userEngagementDuration'],
            ],
            'dimensionFilter' => $exclude_analytics_pages,
            'orderBys' => [['metric' => ['metricName' => 'screenPageViews'], 'desc' => true]],
            'limit' => 10,
        ],
        // 1: 流入元×コンバージョン（CV数はバッチ5のキーイベント集計で上書きする）
        [
            'dateRanges' => $cur,
            'dimensions' => [['name' => 'sessionDefaultChannelGroup']],
            'metrics'    => [['name' => 'sessions'], ['name' => 'conversions']],
            'dimensionFilter' => $exclude_analytics_pages,
            'orderBys'   => [['metric' => ['metricName' => 'sessions'], 'desc' => true]],
            'limit' => 8,
        ],
        // 2: イベント別集計
        [
            'dateRanges' => $cur,
            'dimensions' => [['name' => 'eventName']],
            'metrics'    => [['name' => 'eventCount']],
            'dimensionFilter' => $exclude_analytics_pages,
            'orderBys'   => [['metric' => ['metricName' => 'eventCount'], 'desc' => true]],
            'limit' => 15,
        ],
        // 3: 週別PV推移(過去12週)
        [
            'dateRanges' => [['startDate' => '83daysAgo', 'endDate' => 'yesterday']],
            'dimensions' => [['name' => 'year'], ['name' => 'week']],
            'metrics'    => [['name' => 'screenPageViews']],
            'dimensionFilter' => $exclude_analytics_pages,
            'orderBys'   => [
                ['dimension' => ['dimensionName' => 'year']],
                ['dimension' => ['dimensionName' => 'week']],
            ],
        ],
        // 4: 月別PV推移(過去12ヶ月)
        [
            'dateRanges' => [['startDate' => '364daysAgo', 'endDate' => 'yesterday']],
            'dimensions' => [['name' => 'yearMonth']],
            'metrics'    => [['name' => 'screenPageViews']],
            'dimensionFilter' => $exclude_analytics_pages,
            'orderBys'   => [['dimension' => ['dimensionName' => 'yearMonth']]],
        ],
    ]];

    /* ---- バッチ4: オーガニックランディング + ユーザー熱量ファネル ---- */
    $batch4 = ['requests' => [
        // 0: オーガニック検索のランディングページ TOP10（batch1が5件上限のため移動）
        [
            'dateRanges' => $cur,
            'dimensions' => [['name' => 'landingPage']],
            'metrics'    => [['name' => 'sessions']],
            'dimensionFilter' => ['andGroup' => ['expressions' => [
                ['filter' => [
                    'fieldName'    => 'sessionDefaultChannelGroup',
                    'stringFilter' => ['value' => 'Organic Search', 'matchType' => 'EXACT'],
                ]],
                $exclude_landing_pages,
            ]]],
            'orderBys' => [['metric' => ['metricName' => 'sessions'], 'desc' => true]],
            'limit' => 10,
        ],
        // 1: Stage2 - 会場・コンセプトページ閲覧ユーザー
        [
            'dateRanges' => $cur,
            'metrics'    => [['name' => 'totalUsers']],
            'dimensionFilter' => [
                'orGroup' => [
                    'expressions' => [
                        ['filter' => ['fieldName' => 'pagePath', 'stringFilter' => ['value' => '/concept.php', 'matchType' => 'EXACT']]],
                        ['filter' => ['fieldName' => 'pagePath', 'stringFilter' => ['value' => '/market_stalls.php', 'matchType' => 'EXACT']]],
                    ],
                ],
            ],
        ],
        // 2: Stage3 - 申込み対象ページ閲覧ユーザー
        [
            'dateRanges' => $cur,
            'metrics'    => [['name' => 'totalUsers']],
            'dimensionFilter' => ['orGroup' => ['expressions' => [
                ['filter' => ['fieldName' => 'pagePath', 'stringFilter' => ['value' => '/karaoke.php', 'matchType' => 'EXACT']]],
                ['filter' => ['fieldName' => 'pagePath', 'stringFilter' => ['value' => '/vendors.php', 'matchType' => 'EXACT']]],
            ]]],
        ],
        // 3: Stage4 - フォーム入力開始ユーザー
        [
            'dateRanges' => $cur,
            'metrics'    => [['name' => 'totalUsers']],
            'dimensionFilter' => [
                'filter' => ['fieldName' => 'eventName', 'stringFilter' => ['value' => 'form_start', 'matchType' => 'EXACT']],
            ],
        ],
        // 4: Stage5 - フォーム送信ユーザー（転換）
        [
            'dateRanges' => $cur,
            'metrics'    => [['name' => 'totalUsers']],
            'dimensionFilter' => [
                'filter' => ['fieldName' => 'eventName', 'stringFilter' => ['value' => 'form_submit', 'matchType' => 'EXACT']],
            ],
        ],
    ]];

    /* ---- バッチ5: 沖縄市区町村別 + キーイベント(CV)集計 ---- */
    $batch5 = ['requests' => [
        // 0: 沖縄県内 市区町村別（180日間／プライバシーしきい値対策）
        [
            'dateRanges' => [['startDate' => '179daysAgo', 'endDate' => 'yesterday']],
            'dimensions' => [['name' => 'city']],
            'metrics'    => [['name' => 'totalUsers']],
            'dimensionFilter' => ['andGroup' => ['expressions' => [
                ['filter' => [
                    'fieldName'    => 'region',
                    'stringFilter' => ['value' => 'Okinawa', 'matchType' => 'EXACT'],
                ]],
                $exclude_analytics_pages,
            ]]],
            'orderBys' => [['metric' => ['metricName' => 'totalUsers'], 'desc' => true]],
            'limit' => 15,
        ],
        // 1: キーイベント合計(当月/前月) — GA4のキーイベント設定に依存しないCV集計
        [
            'dateRanges' => $comp,
            'metrics'    => [['name' => 'eventCount']],
            'dimensionFilter' => $key_event_filter,
        ],
        // 2: 流入元×キーイベント
        [
            'dateRanges' => $cur,
            'dimensions' => [['name' => 'sessionDefaultChannelGroup']],
            'metrics'    => [['name' => 'eventCount']],
            'dimensionFilter' => $key_event_filter,
        ],
    ]];

    /* ---- バッチ6: URL→ページタイトル対応表（Search Consoleのページ名解決用） ---- */
    // pagePathPlusQueryString で ?eid=... 等のクエリ付きURLもタイトル判別できるようにする。
    $batch6 = ['requests' => [
        [
            'dateRanges' => [['startDate' => '90daysAgo', 'endDate' => 'today']],
            'dimensions' => [['name' => 'pagePathPlusQueryString'], ['name' => 'pageTitle']],
            'metrics'    => [['name' => 'screenPageViews']],
            'orderBys'   => [['metric' => ['metricName' => 'screenPageViews'], 'desc' => true]],
            'limit'      => 1000,
        ],
    ]];

    $res1 = ga4_http_post($base . ':batchRunReports', json_encode($batch1), $auth);
    $res2 = ga4_http_post($base . ':batchRunReports', json_encode($batch2), $auth);
    $res3 = ga4_http_post($base . ':batchRunReports', json_encode($batch3), $auth);
    $res4 = ga4_http_post($base . ':batchRunReports', json_encode($batch4), $auth);
    $res5 = ga4_http_post($base . ':batchRunReports', json_encode($batch5), $auth);
    $res6 = ga4_http_post($base . ':batchRunReports', json_encode($batch6), $auth);

    $r1 = json_decode($res1, true);
    if (json_last_error() !== JSON_ERROR_NONE || !is_array($r1)) {
        throw new Exception('GA4 バッチ1 JSON解析失敗: ' . substr($res1, 0, 500));
    }
    $r2 = json_decode($res2, true);
    if (json_last_error() !== JSON_ERROR_NONE || !is_array($r2)) {
        throw new Exception('GA4 バッチ2 JSON解析失敗: ' . substr($res2, 0, 500));
    }

    if (isset($r1['error'])) {
        $msg = $r1['error']['message'] ?? json_encode($r1['error'], JSON_UNESCAPED_UNICODE);
        throw new Exception('GA4 APIエラー(バッチ1): ' . $msg);
    }
    if (isset($r2['error'])) {
        $msg = $r2['error']['message'] ?? json_encode($r2['error'], JSON_UNESCAPED_UNICODE);
        throw new Exception('GA4 APIエラー(バッチ2): ' . $msg);
    }
    $r3 = json_decode($res3, true);
    if (json_last_error() !== JSON_ERROR_NONE || !is_array($r3)) {
        throw new Exception('GA4 バッチ3 JSON解析失敗: ' . substr($res3, 0, 500));
    }
    if (isset($r3['error'])) {
        $msg = $r3['error']['message'] ?? json_encode($r3['error'], JSON_UNESCAPED_UNICODE);
        throw new Exception('GA4 APIエラー(バッチ3): ' . $msg);
    }

    $r4 = json_decode($res4, true);
    if (json_last_error() !== JSON_ERROR_NONE || !is_array($r4)) {
        throw new Exception('GA4 バッチ4 JSON解析失敗: ' . substr($res4, 0, 500));
    }
    if (isset($r4['error'])) {
        $msg = $r4['error']['message'] ?? json_encode($r4['error'], JSON_UNESCAPED_UNICODE);
        throw new Exception('GA4 APIエラー(バッチ4): ' . $msg);
    }

    $r5 = json_decode($res5, true);
    if (json_last_error() !== JSON_ERROR_NONE || !is_array($r5)) {
        throw new Exception('GA4 バッチ5 JSON解析失敗: ' . substr($res5, 0, 500));
    }
    if (isset($r5['error'])) {
        $msg = $r5['error']['message'] ?? json_encode($r5['error'], JSON_UNESCAPED_UNICODE);
        throw new Exception('GA4 APIエラー(バッチ5): ' . $msg);
    }

    $rep1 = $r1['reports'] ?? [];
    $rep2 = $r2['reports'] ?? [];
    $rep3 = $r3['reports'] ?? [];
    $rep4 = $r4['reports'] ?? [];
    $rep5 = $r5['reports'] ?? [];

    /* ---- KPI 合計の組み立て ---- */
    $kpi_rows = $rep1[0]['rows'] ?? [];
    // dateRange ごとに metricValues が並ぶ。dateRanges_name で判別する。
    $cur_m = ['pv' => 0, 'users' => 0, 'sessions' => 0, 'dur' => 0, 'key' => 0];
    $prev_m = $cur_m;
    foreach ($kpi_rows as $row) {
        // 比較レポートでは dimensionValues に dateRange 名が入る
        $rangeName = $row['dimensionValues'][0]['value'] ?? 'date_range_0';
        $mv = $row['metricValues'];
        $vals = [
            'pv'       => (float)($mv[0]['value'] ?? 0),
            'users'    => (float)($mv[1]['value'] ?? 0),
            'sessions' => (float)($mv[2]['value'] ?? 0),
            'dur'      => (float)($mv[3]['value'] ?? 0),
            'key'      => (float)($mv[4]['value'] ?? 0),
        ];
        if ($rangeName === 'previous' || $rangeName === 'date_range_1') {
            $prev_m = $vals;
        } else {
            $cur_m = $vals;
        }
    }

    // CVはGA4のキーイベント設定に依存せず、$KEY_EVENTS の発生数で集計する(rep5[1])
    $cur_m['key'] = 0;
    $prev_m['key'] = 0;
    foreach (($rep5[1]['rows'] ?? []) as $row) {
        $rangeName = $row['dimensionValues'][0]['value'] ?? 'date_range_0';
        $v = (float)($row['metricValues'][0]['value'] ?? 0);
        if ($rangeName === 'previous' || $rangeName === 'date_range_1') {
            $prev_m['key'] = $v;
        } else {
            $cur_m['key'] = $v;
        }
    }

    $cvr_cur  = $cur_m['sessions']  > 0 ? $cur_m['key']  / $cur_m['sessions']  * 100 : 0;
    $cvr_prev = $prev_m['sessions'] > 0 ? $prev_m['key'] / $prev_m['sessions'] * 100 : 0;

    $kpi = [
        'pv' => [
            'value'  => (int)$cur_m['pv'],
            'change' => ga4_pct_change($cur_m['pv'], $prev_m['pv']),
        ],
        'users' => [
            'value'  => (int)$cur_m['users'],
            'change' => ga4_pct_change($cur_m['users'], $prev_m['users']),
        ],
        'cvr' => [
            'value'  => round($cvr_cur, 2),
            'change' => round($cvr_cur - $cvr_prev, 2), // ポイント差
        ],
        'cv' => [
            'value'  => (int)$cur_m['key'],
            'prev'   => (int)$prev_m['key'],
        ],
        'engagement' => [
            'seconds' => $cur_m['users'] > 0 ? $cur_m['dur'] : 0, // 平均セッション時間(秒)
            'change'  => ga4_pct_change($cur_m['dur'], $prev_m['dur']),
        ],
    ];

    /* ---- PV推移 ---- */
    $trend = ['labels' => [], 'data' => []];
    foreach (($rep1[1]['rows'] ?? []) as $row) {
        $d = $row['dimensionValues'][0]['value']; // yyyymmdd
        $trend['labels'][] = (int)substr($d, 4, 2) . '/' . (int)substr($d, 6, 2);
        $trend['data'][]   = (int)($row['metricValues'][0]['value'] ?? 0);
    }

    /* ---- 流入元 (日本語変換) ---- */
    $channel_map = [
        'Direct'           => 'ダイレクト',
        'Organic Search'   => 'オーガニック検索',
        'Referral'         => '参照サイト',
        'Organic Social'   => 'SNS（自然流入）',
        'Paid Search'      => '有料検索',
        'Email'            => 'メール',
        'Affiliates'       => 'アフィリエイト',
        'Display'          => 'ディスプレイ広告',
        'Paid Social'      => 'SNS（有料）',
        'Paid Video'       => '動画広告',
        'Paid Shopping'    => 'ショッピング広告',
        'Organic Video'    => '動画（自然流入）',
        'Organic Shopping' => 'ショッピング（自然流入）',
        'Unassigned'       => '未割り当て',
        '(other)'          => 'その他',
    ];
    $source = ['labels' => [], 'data' => []];
    foreach (($rep1[2]['rows'] ?? []) as $row) {
        $raw = $row['dimensionValues'][0]['value'];
        $source['labels'][] = $channel_map[$raw] ?? $raw;
        $source['data'][]   = (int)($row['metricValues'][0]['value'] ?? 0);
    }

    /* ---- デバイス ---- */
    $device = ['labels' => [], 'data' => []];
    foreach (($rep1[3]['rows'] ?? []) as $row) {
        $device['labels'][] = ucfirst($row['dimensionValues'][0]['value']);
        $device['data'][]   = (int)($row['metricValues'][0]['value'] ?? 0);
    }

    /* ---- 人気ページ (/index.php と / を統合) ---- */
    $pages_map = [];
    foreach (($rep1[4]['rows'] ?? []) as $row) {
        $path  = ga4_normalize_path($row['dimensionValues'][0]['value']);
        $title = $row['dimensionValues'][1]['value'] ?: $path;
        $pv    = (int)($row['metricValues'][0]['value'] ?? 0);
        if (isset($pages_map[$path])) {
            $pages_map[$path]['pv'] += $pv;
        } else {
            $pages_map[$path] = ['path' => $path, 'title' => $title, 'pv' => $pv];
        }
    }
    $pages = array_values($pages_map);
    usort($pages, function ($a, $b) { return $b['pv'] - $a['pv']; });

    /* ---- オーガニック検索ランディングページ (rep4[0]) ---- */
    $organic_landing = [];
    foreach (($rep4[0]['rows'] ?? []) as $row) {
        $organic_landing[] = [
            'path'     => $row['dimensionValues'][0]['value'],
            'sessions' => (int)($row['metricValues'][0]['value'] ?? 0),
        ];
    }

    /* ---- 男女比 ---- */
    $gender_map = ['female' => '女性', 'male' => '男性', 'unknown' => '不明'];
    $gender = ['labels' => [], 'data' => []];
    $gender_unknown = 0;
    foreach (($rep2[0]['rows'] ?? []) as $row) {
        $g = $row['dimensionValues'][0]['value'];
        $label = $gender_map[$g] ?? $g;
        if ($g === 'unknown') {
            $gender_unknown += (int)($row['metricValues'][0]['value'] ?? 0);
            continue; // 不明はグラフから除外し、ステータスには件数を残す
        }
        $gender['labels'][] = $label;
        $gender['data'][]   = (int)($row['metricValues'][0]['value'] ?? 0);
    }

    /* ---- 年齢層 ---- */
    $age = ['labels' => [], 'data' => []];
    $age_unknown = 0;
    foreach (($rep2[1]['rows'] ?? []) as $row) {
        $a = $row['dimensionValues'][0]['value'];
        if ($a === 'unknown') {
            $age_unknown += (int)($row['metricValues'][0]['value'] ?? 0);
            continue;
        }
        $age['labels'][] = $a;
        $age['data'][]   = (int)($row['metricValues'][0]['value'] ?? 0);
    }

    // subjectToThresholding=true は、レポートがプライバシーしきい値の対象で、
    // 基準未満の行が返されない可能性があることを示す。
    $demographics = [
        'period' => '過去365日間',
        'gender' => [
            'available'   => count($gender['data']) > 0,
            'thresholded' => !empty($rep2[0]['metadata']['subjectToThresholding']),
            'known_users' => array_sum($gender['data']),
            'unknown_users' => $gender_unknown,
        ],
        'age' => [
            'available'   => count($age['data']) > 0,
            'thresholded' => !empty($rep2[1]['metadata']['subjectToThresholding']),
            'known_users' => array_sum($age['data']),
            'unknown_users' => $age_unknown,
        ],
    ];

    /* ---- 地域 ---- */
    $region = [];
    foreach (($rep2[2]['rows'] ?? []) as $row) {
        $region[] = [
            'name'  => $row['dimensionValues'][0]['value'],
            'count' => (int)($row['metricValues'][0]['value'] ?? 0),
        ];
    }

    /* ---- 検索キーワード（サイト内検索） ---- */
    $keywords = [];
    foreach (($rep2[3]['rows'] ?? []) as $row) {
        $term = $row['dimensionValues'][0]['value'];
        if ($term === '(not set)' || $term === '') continue;
        $keywords[] = [
            'term'     => $term,
            'sessions' => (int)($row['metricValues'][0]['value'] ?? 0),
        ];
    }

    /* ---- 新規vs継続ユーザー ---- */
    $nvr_map = ['new' => '新規ユーザー', 'returning' => 'リピーター'];
    $new_vs_returning = ['labels' => [], 'data' => []];
    foreach (($rep2[4]['rows'] ?? []) as $row) {
        $key = $row['dimensionValues'][0]['value'];
        $new_vs_returning['labels'][] = $nvr_map[$key] ?? $key;
        $new_vs_returning['data'][]   = (int)($row['metricValues'][0]['value'] ?? 0);
    }

    /* ---- ページ別エンゲージメント指標 (/index.php と / をPV加重で統合) ---- */
    $pm_map = [];
    foreach (($rep3[0]['rows'] ?? []) as $row) {
        $path    = ga4_normalize_path($row['dimensionValues'][0]['value']);
        $title   = $row['dimensionValues'][1]['value'] ?: $path;
        $pv      = (int)($row['metricValues'][0]['value'] ?? 0);
        $eng     = (float)($row['metricValues'][1]['value'] ?? 0) * 100;
        $bounce  = (float)($row['metricValues'][2]['value'] ?? 0) * 100;
        $eng_dur = (float)($row['metricValues'][3]['value'] ?? 0);
        if (isset($pm_map[$path])) {
            $m = &$pm_map[$path];
            $total_pv = $m['pv'] + $pv;
            if ($total_pv > 0) {
                $m['engagement'] = ($m['engagement'] * $m['pv'] + $eng * $pv) / $total_pv;
                $m['bounce']     = ($m['bounce'] * $m['pv'] + $bounce * $pv) / $total_pv;
            }
            $m['pv']      = $total_pv;
            $m['eng_dur'] += $eng_dur;
            unset($m);
        } else {
            $pm_map[$path] = [
                'path' => $path, 'title' => $title, 'pv' => $pv,
                'engagement' => $eng, 'bounce' => $bounce, 'eng_dur' => $eng_dur,
            ];
        }
    }
    $page_metrics = [];
    foreach ($pm_map as $m) {
        $page_metrics[] = [
            'path'        => $m['path'],
            'title'       => $m['title'],
            'pv'          => $m['pv'],
            'engagement'  => round($m['engagement'], 1),
            'bounce'      => round($m['bounce'], 1),
            'avg_time'    => $m['pv'] > 0 ? round($m['eng_dur'] / $m['pv']) : 0,
        ];
    }
    usort($page_metrics, function ($a, $b) { return $b['pv'] - $a['pv']; });

    /* ---- 流入元×コンバージョン（CV数はキーイベント集計 rep5[2] を使用） ---- */
    $cv_by_channel = [];
    foreach (($rep5[2]['rows'] ?? []) as $row) {
        $ch = $row['dimensionValues'][0]['value'];
        $cv_by_channel[$ch] = (int)($row['metricValues'][0]['value'] ?? 0);
    }
    $source_conv = [];
    foreach (($rep3[1]['rows'] ?? []) as $row) {
        $raw      = $row['dimensionValues'][0]['value'];
        $sessions = (int)($row['metricValues'][0]['value'] ?? 0);
        $conv     = $cv_by_channel[$raw] ?? 0;
        $source_conv[] = [
            'channel'     => $channel_map[$raw] ?? $raw,
            'sessions'    => $sessions,
            'conversions' => $conv,
            'cvr'         => $sessions > 0 ? round($conv / $sessions * 100, 2) : 0,
        ];
    }

    /* ---- イベント別集計 ---- */
    $events = [];
    foreach (($rep3[2]['rows'] ?? []) as $row) {
        $events[] = [
            'name'  => $row['dimensionValues'][0]['value'],
            'count' => (int)($row['metricValues'][0]['value'] ?? 0),
        ];
    }

    /* ---- 週別PV推移 ---- */
    $weekly = ['labels' => [], 'data' => []];
    foreach (($rep3[3]['rows'] ?? []) as $row) {
        $year = $row['dimensionValues'][0]['value'];
        $week = str_pad($row['dimensionValues'][1]['value'], 2, '0', STR_PAD_LEFT);
        $weekly['labels'][] = $year . '/W' . $week;
        $weekly['data'][]   = (int)($row['metricValues'][0]['value'] ?? 0);
    }

    /* ---- 月別PV推移 ---- */
    $monthly = ['labels' => [], 'data' => []];
    foreach (($rep3[4]['rows'] ?? []) as $row) {
        $ym = $row['dimensionValues'][0]['value']; // YYYYMM
        $monthly['labels'][] = (int)substr($ym, 0, 4) . '/' . (int)substr($ym, 4, 2) . '月';
        $monthly['data'][]   = (int)($row['metricValues'][0]['value'] ?? 0);
    }

    /* ---- 沖縄県内 市区町村別 ---- */
    $okinawa_city_map = [
        'Naha'             => '那覇市',
        'Urasoe'           => '浦添市',
        'Ginowan'          => '宜野湾市',
        'Okinawa'          => '沖縄市',
        'Uruma'            => 'うるま市',
        'Tomigusuku'       => '豊見城市',
        'Itoman'           => '糸満市',
        'Nanjo'            => '南城市',
        'Nago'             => '名護市',
        'Ishigaki'         => '石垣市',
        'Miyakojima'       => '宮古島市',
        'Chatan'           => '北谷町',
        'Kadena'           => '嘉手納町',
        'Yomitan'          => '読谷村',
        'Nishihara'        => '西原町',
        'Haebaru'          => '南風原町',
        'Yaese'            => '八重瀬町',
        'Yonabaru'         => '与那原町',
        'Kitanakagusuku'   => '北中城村',
        'Nakagusuku'       => '中城村',
        'Kin'              => '金武町',
        'Ginoza'           => '宜野座村',
        'Motobu'           => '本部町',
        'Nakijin'          => '今帰仁村',
        'Ogimi'            => '大宜味村',
        'Kunigami'         => '国頭村',
        'Higashi'          => '東村',
        'Ie'               => '伊江村',
        'Iheya'            => '伊平屋村',
        'Izena'            => '伊是名村',
        'Kume Island'      => '久米島町',
        'Tarama'           => '多良間村',
        'Yonaguni'         => '与那国町',
        'Taketomi'         => '竹富町',
    ];
    $okinawa_cities = [];
    foreach (($rep5[0]['rows'] ?? []) as $row) {
        $city_en = $row['dimensionValues'][0]['value'];
        if ($city_en === '(not set)' || $city_en === '') continue;
        $okinawa_cities[] = [
            'name'  => $okinawa_city_map[$city_en] ?? $city_en,
            'count' => (int)($row['metricValues'][0]['value'] ?? 0),
        ];
    }

    /* ---- ユーザー熱量ファネル ---- */
    $stage_users = [
        (int)$cur_m['users'],                                                              // Stage1: 全訪問者
        (int)(($rep4[1]['rows'][0]['metricValues'][0]['value'] ?? 0)),                     // Stage2: 会場・コンセプト
        (int)(($rep4[2]['rows'][0]['metricValues'][0]['value'] ?? 0)),                     // Stage3: 申込み対象ページ
        (int)(($rep4[3]['rows'][0]['metricValues'][0]['value'] ?? 0)),                     // Stage4: フォーム入力開始
        (int)(($rep4[4]['rows'][0]['metricValues'][0]['value'] ?? 0)),                     // Stage5: フォーム送信
    ];
    $stage_labels = [
        '認知：サイト訪問',
        '興味：会場・コンセプト閲覧',
        '検討：申込み対象ページ閲覧',
        '意向：フォーム入力開始',
        '転換：フォーム送信',
    ];
    $funnel_stages = [];
    $s1 = $stage_users[0] ?: 1;
    for ($i = 0; $i < 5; $i++) {
        $u    = $stage_users[$i];
        $prev = $i > 0 ? $stage_users[$i - 1] : $u;
        $funnel_stages[] = [
            'stage'      => $i + 1,
            'label'      => $stage_labels[$i],
            'users'      => $u,
            'rate_total' => round($u / $s1 * 100, 1),
            'rate_prev'  => $prev > 0 ? round($u / $prev * 100, 1) : 0,
            'drop'       => max(0, $prev - $u),
        ];
    }

    /* ---- URL→タイトル対応表を構築（バッチ6・失敗しても無視）---- */
    $title_map = [];
    $r6 = json_decode($res6, true);
    if (is_array($r6) && !isset($r6['error'])) {
        foreach (($r6['reports'][0]['rows'] ?? []) as $row) {
            $pp    = $row['dimensionValues'][0]['value'] ?? '';
            $title = trim($row['dimensionValues'][1]['value'] ?? '');
            if ($pp === '' || $title === '' || $title === '(not set)') continue;
            if (!isset($title_map[$pp])) $title_map[$pp] = $title;
            // クエリ除去版もフォールバックとして登録
            $noq = preg_replace('/\?.*$/', '', $pp);
            if ($noq !== $pp && !isset($title_map[$noq])) $title_map[$noq] = $title;
        }
    }

    return [
        '_title_map'       => $title_map,
        'kpi'              => $kpi,
        'trend'            => $trend,
        'weekly'           => $weekly,
        'monthly'          => $monthly,
        'source'           => $source,
        'device'           => $device,
        'gender'           => $gender,
        'age'              => $age,
        'demographics'     => $demographics,
        'region'           => $region,
        'pages'            => $pages,
        'page_metrics'     => $page_metrics,
        'keywords'         => $keywords,
        'new_vs_returning' => $new_vs_returning,
        'organic_landing'  => $organic_landing,
        'source_conv'      => $source_conv,
        'events'           => $events,
        'funnel_stages'    => $funnel_stages,
        'okinawa_cities'   => $okinawa_cities,
        'period'           => '過去30日間',
    ];
}


/* ==================================================================
 * Search Console: 検索キーワード取得
 * ================================================================== */
function sc_get_keywords($site_url, $token, $days = 28)
{
    $end_date   = date('Y-m-d', strtotime('-1 day'));
    $start_date = date('Y-m-d', strtotime("-{$days} days"));
    $encoded    = urlencode($site_url);
    $url        = 'https://searchconsole.googleapis.com/webmasters/v3/sites/' . $encoded . '/searchAnalytics/query';
    $auth       = ['Authorization: Bearer ' . $token, 'Content-Type: application/json'];

    $body = json_encode([
        'startDate'  => $start_date,
        'endDate'    => $end_date,
        'dimensions' => ['query'],
        'rowLimit'   => 20,
        'type'       => 'web',
    ]);

    $res  = ga4_http_post($url, $body, $auth);
    $data = json_decode($res, true);

    if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
        throw new Exception('Search Console JSON解析失敗: ' . substr($res, 0, 500));
    }
    if (isset($data['error'])) {
        $msg = $data['error']['message'] ?? json_encode($data['error'], JSON_UNESCAPED_UNICODE);
        throw new Exception('Search Console APIエラー: ' . $msg);
    }

    $keywords = [];
    foreach ($data['rows'] ?? [] as $row) {
        $keywords[] = [
            'query'       => $row['keys'][0],
            'clicks'      => (int)$row['clicks'],
            'impressions' => (int)$row['impressions'],
            'ctr'         => round($row['ctr'] * 100, 1),
            'position'    => round($row['position'], 1),
        ];
    }
    return $keywords;
}


/* ==================================================================
 * Search Console: ページ別 検索パフォーマンス取得
 * ページごとにクリック・表示回数・CTR・平均順位と主要クエリを集計
 * ================================================================== */
function sc_get_pages($site_url, $token, $days = 28)
{
    $end_date   = date('Y-m-d', strtotime('-1 day'));
    $start_date = date('Y-m-d', strtotime("-{$days} days"));
    $encoded    = urlencode($site_url);
    $url        = 'https://searchconsole.googleapis.com/webmasters/v3/sites/' . $encoded . '/searchAnalytics/query';
    $auth       = ['Authorization: Bearer ' . $token, 'Content-Type: application/json'];

    $body = json_encode([
        'startDate'  => $start_date,
        'endDate'    => $end_date,
        'dimensions' => ['page', 'query'],
        'rowLimit'   => 250,
        'type'       => 'web',
    ]);

    $res  = ga4_http_post($url, $body, $auth);
    $data = json_decode($res, true);

    if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
        throw new Exception('Search Console(ページ別) JSON解析失敗: ' . substr($res, 0, 500));
    }
    if (isset($data['error'])) {
        $msg = $data['error']['message'] ?? json_encode($data['error'], JSON_UNESCAPED_UNICODE);
        throw new Exception('Search Console(ページ別) APIエラー: ' . $msg);
    }

    // ページ単位に集約（順位は表示回数による加重平均）
    $pages = [];
    foreach ($data['rows'] ?? [] as $row) {
        $page  = $row['keys'][0];
        $query = $row['keys'][1];
        $path  = preg_replace('#^https?://[^/]+#', '', $page) ?: '/';
        if (!isset($pages[$path])) {
            $pages[$path] = [
                'path' => $path, 'clicks' => 0, 'impressions' => 0,
                'pos_weighted' => 0.0, 'top_query' => '', 'top_query_imp' => 0,
            ];
        }
        $p = &$pages[$path];
        $p['clicks']       += (int)$row['clicks'];
        $p['impressions']  += (int)$row['impressions'];
        $p['pos_weighted'] += $row['position'] * $row['impressions'];
        // 主要クエリ = そのページで最も表示されたクエリ
        if ((int)$row['impressions'] > $p['top_query_imp']) {
            $p['top_query']     = $query;
            $p['top_query_imp'] = (int)$row['impressions'];
        }
        unset($p);
    }

    $result = [];
    foreach ($pages as $p) {
        $result[] = [
            'path'        => $p['path'],
            'top_query'   => $p['top_query'],
            'clicks'      => $p['clicks'],
            'impressions' => $p['impressions'],
            'ctr'         => $p['impressions'] > 0 ? round($p['clicks'] / $p['impressions'] * 100, 1) : 0,
            'position'    => $p['impressions'] > 0 ? round($p['pos_weighted'] / $p['impressions'], 1) : 0,
        ];
    }
    usort($result, function ($a, $b) {
        if ($b['clicks'] !== $a['clicks']) return $b['clicks'] - $a['clicks'];
        return $b['impressions'] - $a['impressions'];
    });
    return array_slice($result, 0, 10);
}

/**
 * Search ConsoleのパスにGA4のページタイトルを解決する。
 * 完全一致 → クエリ除去一致 → /index.php ⇄ / の揺れ吸収 の順で探す。
 * 見つからなければ空文字（フロント側はURL表示にフォールバック）。
 */
function sc_resolve_title($path, array $map)
{
    if ($path === '') return '';
    if (isset($map[$path])) return $map[$path];

    $noq = preg_replace('/\?.*$/', '', $path);
    if (isset($map[$noq])) return $map[$noq];

    // トップページの /index.php ⇄ / 表記ゆれ
    $alts = [];
    if ($noq === '/' || $noq === '') { $alts[] = '/index.php'; }
    if (preg_match('#/index\.php$#', $noq)) { $alts[] = '/'; }
    foreach ($alts as $alt) {
        if (isset($map[$alt])) return $map[$alt];
    }
    return '';
}


/* ==================================================================
 * ヘルパー
 * ================================================================== */

/**
 * 指定した接頭辞のいずれにも一致しないことを表す dimensionFilter を生成
 */
function ga4_not_prefix_filters($field, $prefixes)
{
    $exprs = [];
    foreach ($prefixes as $prefix) {
        $exprs[] = ['notExpression' => ['filter' => [
            'fieldName'    => $field,
            'stringFilter' => ['value' => $prefix, 'matchType' => 'BEGINS_WITH'],
        ]]];
    }
    return count($exprs) === 1 ? $exprs[0] : ['andGroup' => ['expressions' => $exprs]];
}

/**
 * /index.php と / を同一ページとして扱うための正規化
 */
function ga4_normalize_path($path)
{
    $n = preg_replace('#/index\.php$#', '/', $path);
    return $n === '' ? '/' : $n;
}

function ga4_b64url($data)
{
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function ga4_pct_change($cur, $prev)
{
    if ($prev <= 0) return null;
    return round(($cur - $prev) / $prev * 100, 1);
}

function ga4_http_post($url, $body, $headers)
{
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $body,
        CURLOPT_HTTPHEADER     => $headers,
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_CONNECTTIMEOUT => 10,
    ]);
    $res  = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($res === false) {
        $err = curl_error($ch);
        curl_close($ch);
        throw new Exception('通信エラー: ' . $err);
    }
    curl_close($ch);
    // 4xx/5xx はそのままボディを返す（呼び出し元で json_decode してエラーを解析）
    if ($code >= 400) {
        // ボディにエラー詳細が入っているのでそのまま返す（呼び出し元で処理）
        // ただしボディが空の場合はここでスロー
        if (trim($res) === '') {
            throw new Exception('HTTPエラー ' . $code . ' (レスポンスボディなし): ' . $url);
        }
    }
    return $res;
}
