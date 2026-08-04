<?php

$title = "やふそ屋台村 ちょうちん横丁｜浦添・屋富祖の屋内型屋台村";
$description = "浦添市屋富祖にある屋内型屋台村「ちょうちん横丁」。ホルモン、ラーメン、手羽先、沖縄料理、ワインバー、カラオケを天候を気にせず快適に楽しめる、にぎわいと安心の空間です。";
$abbreviation = "当社";

$company = "やふそ屋台村 ちょうちん横丁";
$copyright = "やふそやふそ屋台村 ちょうちん横丁｜浦添・屋富祖の屋内型屋台村";
$name = "やふそ屋台村 ちょうちん横丁";
$product_name = "";
$telNo = "098-879-1055";
$mobile = "";
$faxNo = "";
$postalCode = "〒901-2127";
$address = "沖縄県浦添市屋富祖3丁目33-6";
$addressRegion = "沖縄県"; // 都道府県
$addressLocality = "浦添市屋富祖"; // 市区町村
$streetAddress = "3丁目33-6"; // 番地
$maplink = "https://maps.app.goo.gl/PPueDjRBqKaKAmZJ7";
$gmap = '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3578.2825376627247!2d127.70024539678953!3d26.2524921!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x34e56b542adcf563%3A0x8b080ecfa2ff528f!2z44KE44G144Gd5bGL5Y-w5p2R44Gh44KH44GG44Gh44KT5qiq5LiB!5e0!3m2!1sja!2sjp!4v1781582967368!5m2!1sja!2sjp" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>';

$cms = "";
$cmsID = "";
$categoryID01 = "";
$categoryID02 = "";
$categoryID03 = "";
$categoryID04 = "";
$categoryID05 = "";
$categoryID06 = "";
$page_images = "../images/images.webp"; //../images/images.webp

// 本番はドメイン直下、localhostの1階層目ではMAMPのサブフォルダを自動で補います。
// 必要な場合は YAFUSO_BASE_PATH（例: /029_yafuso）で明示指定できます。
$configured_base_path = getenv('YAFUSO_BASE_PATH');
$site_base_path = $configured_base_path !== false
    ? trim((string)$configured_base_path)
    : '';
if ($site_base_path === '') {
    $request_host = strtolower((string)($_SERVER['HTTP_HOST'] ?? ''));
    $request_host = preg_replace('/:\d+$/', '', $request_host);
    $request_script = str_replace('\\', '/', (string)($_SERVER['SCRIPT_NAME'] ?? ''));
    if (in_array($request_host, ['localhost', '127.0.0.1', '::1'], true)) {
        $script_segments = array_values(array_filter(explode('/', trim($request_script, '/')), 'strlen'));
        if (count($script_segments) >= 2) {
            $site_base_path = '/' . $script_segments[0];
        }
    }
}
$site_base_path = trim($site_base_path, '/');
$site_base_path = $site_base_path === '' ? '' : '/' . $site_base_path;

function yafuso_url($path = '/')
{
    global $site_base_path;

    $path = '/' . ltrim((string)$path, '/');
    if ($site_base_path !== '' && ($path === $site_base_path || strpos($path, $site_base_path . '/') === 0)) {
        return $path;
    }
    return ($site_base_path !== '' ? $site_base_path : '') . $path;
}

function yafuso_mailform_routes($form_type)
{
    $routes = [
        'karaoke_reservation' => [
            'thanks' => yafuso_url('/thanks.php') . '?type=karaoke_reservation',
            'failure' => yafuso_url('/karaoke.php') . '?form_error=1#karaoke_reservation',
            'guard' => yafuso_url('/karaoke.php') . '#karaoke_reservation',
        ],
        'vendor_inquiry' => [
            'thanks' => yafuso_url('/thanks.php') . '?type=vendor_inquiry',
            'failure' => yafuso_url('/vendors.php') . '?form_error=1#vendors_contact_form',
            'guard' => yafuso_url('/vendors.php') . '#vendors_contact_form',
        ],
    ];

    return $routes[(string)$form_type] ?? [
        'thanks' => yafuso_url('/'),
        'failure' => yafuso_url('/'),
        'guard' => yafuso_url('/'),
    ];
}

$img = yafuso_url('/images');
$ogp_image = $img . "/ogp_image.jpg";

$weblink = "";
$instagram = "";
$line = "";
$site_config = [];
$site_config_file = __DIR__ . '/site.config.php';
if (is_file($site_config_file)) {
    $loaded_site_config = require $site_config_file;
    if (is_array($loaded_site_config)) {
        $site_config = $loaded_site_config;
    }
}
$configured_mail = getenv('YAFUSO_MAIL_FROM');
if ($configured_mail === false || trim((string)$configured_mail) === '') {
    $configured_mail = $site_config['mail_from'] ?? '';
}
if (trim((string)$configured_mail) === '') {
    // フォーム通知と自動返信の送信元。
    $configured_mail = 'truth@d-neko.com';
}
$mailRecipients = [];
$mailRecipientsValid = true;
foreach (explode(',', (string)$configured_mail) as $recipient) {
    $recipient = trim($recipient);
    if ($recipient === '' || !filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
        $mailRecipientsValid = false;
        break;
    }
    $mailRecipients[] = $recipient;
}
$mail = $mailRecipientsValid && $mailRecipients !== []
    ? implode(',', $mailRecipients)
    : '';
$configured_karaoke_reservation_mail_to = getenv('YAFUSO_KARAOKE_RESERVATION_MAIL_TO');
if ($configured_karaoke_reservation_mail_to === false || trim((string)$configured_karaoke_reservation_mail_to) === '') {
    $configured_karaoke_reservation_mail_to = $site_config['karaoke_reservation_mail_to'] ?? '';
}
if (trim((string)$configured_karaoke_reservation_mail_to) === '') {
    $configured_karaoke_reservation_mail_to = 'karaoke-momotarou@marushin-v.co.jp';
}
$configured_vendor_inquiry_mail_to = getenv('YAFUSO_VENDOR_INQUIRY_MAIL_TO');
if ($configured_vendor_inquiry_mail_to === false || trim((string)$configured_vendor_inquiry_mail_to) === '') {
    $configured_vendor_inquiry_mail_to = $site_config['vendor_inquiry_mail_to'] ?? '';
}
if (trim((string)$configured_vendor_inquiry_mail_to) === '') {
    $configured_vendor_inquiry_mail_to = 'sm09021717224@yahoo.co.jp';
}
$karaoke_reservation_mail_to = trim((string)$configured_karaoke_reservation_mail_to);
$vendor_inquiry_mail_to = trim((string)$configured_vendor_inquiry_mail_to);
$karaoke_reservation_mail_from = $mail;
$vendor_inquiry_mail_from = $mail;
$youtube = "";
$tiktok = "";
$facebook = "";
$x = "";

// GA4測定IDは環境変数またはgit管理外のサイト設定から読み込みます。
$configured_ga4_measurement_id = getenv('GA4_MEASUREMENT_ID');
if ($configured_ga4_measurement_id === false || trim((string)$configured_ga4_measurement_id) === '') {
    $configured_ga4_measurement_id = $site_config['ga4_measurement_id'] ?? '';
}
$ga4_measurement_id = preg_match('/^G-[A-Z0-9]+$/', trim((string)$configured_ga4_measurement_id))
    ? trim((string)$configured_ga4_measurement_id)
    : '';

ini_set('display_errors', "Off");

function yafuso_session_start()
{
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
}

function yafuso_mailform_context($form_type)
{
    yafuso_session_start();

    $form_type = (string)$form_type;
    $started_at = (int)($_SESSION['yafuso_mailform_started_at'][$form_type] ?? 0);
    if (
        empty($_SESSION['yafuso_mailform_tokens'][$form_type])
        || $started_at <= 0
        || $started_at < time() - 7200
    ) {
        $_SESSION['yafuso_mailform_tokens'][$form_type] = bin2hex(random_bytes(32));
        $_SESSION['yafuso_mailform_started_at'][$form_type] = time();
    }

    return [
        'token' => (string)$_SESSION['yafuso_mailform_tokens'][$form_type],
        'started_at' => (int)($_SESSION['yafuso_mailform_started_at'][$form_type] ?? time()),
    ];
}

function yafuso_mailform_csrf_token($form_type)
{
    $context = yafuso_mailform_context($form_type);
    return $context['token'];
}

function yafuso_visitor_tracker_token()
{
    yafuso_session_start();
    if (empty($_SESSION['yafuso_visitor_tracker_token'])) {
        $_SESSION['yafuso_visitor_tracker_token'] = bin2hex(random_bytes(32));
    }
    return (string)$_SESSION['yafuso_visitor_tracker_token'];
}

// blog CMS (ros-cp.com)
$requested_eid = isset($_GET["eid"]) ? trim((string)$_GET["eid"]) : '';
$blog_title = '';
if ($requested_eid !== '' && !empty($cmsID)) {
    $ros_blog_title = @file_get_contents(
        "https://admin.ros-cp.com/output/output_blog_entry_detail.php?user_id=" . $cmsID . "&eid=" . urlencode($requested_eid) . "&c=entry_title"
    );
    if ($ros_blog_title !== false) {
        $blog_title = trim((string)$ros_blog_title);
    }
}
if ($blog_title === '記事が見当たりません') {
    $blog_title = '';
}

// microCMS Settings
// 本番値は環境変数、またはgit管理外の microcms.config.php から読み込みます。
$microcms_service_id = trim((string) getenv('MICROCMS_SERVICE_ID'));
$microcms_api_key    = trim((string) getenv('MICROCMS_API_KEY'));
$microcms_enabled_value = getenv('MICROCMS_ENABLED');
$microcms_enabled_explicit = $microcms_enabled_value !== false && trim((string)$microcms_enabled_value) !== '';
$microcms_enabled = $microcms_enabled_explicit
    ? filter_var($microcms_enabled_value, FILTER_VALIDATE_BOOLEAN)
    : false;
$microcms_local_config = __DIR__ . '/microcms.config.php';
if (is_file($microcms_local_config)) {
    $microcms_config = require $microcms_local_config;
    if (is_array($microcms_config)) {
        $microcms_service_id = trim((string) ($microcms_config['service_id'] ?? $microcms_service_id));
        $microcms_api_key = trim((string) ($microcms_config['api_key'] ?? $microcms_api_key));
        if (array_key_exists('enabled', $microcms_config)) {
            $microcms_enabled = (bool) $microcms_config['enabled'];
            $microcms_enabled_explicit = true;
        }
    }
}
if (!$microcms_enabled_explicit) {
    $microcms_enabled = $microcms_service_id !== '' && $microcms_api_key !== '';
}
$microcms_base_url   = "https://" . $microcms_service_id . ".microcms.io/api/v1";

/**
 * microCMS API fetch function
 *
 * @param string $endpoint  API endpoint (e.g. "/blog", "/blog/article-id", "/news?limit=5")
 * @return object|null      Decoded JSON response or null on failure
 */
function microcms_get($endpoint)
{
    global $microcms_base_url, $microcms_api_key, $microcms_service_id, $microcms_enabled;

    if (!$microcms_enabled || $microcms_service_id === '' || $microcms_api_key === '') {
        return null;
    }

    $url = $microcms_base_url . $endpoint;
    $options = [
        'http' => [
            'header'  => "X-MICROCMS-API-KEY: " . $microcms_api_key,
            'method'  => 'GET',
            'timeout' => 10,
        ],
    ];
    $context = stream_context_create($options);
    $response = @file_get_contents($url, false, $context);

    if ($response === false) {
        return null;
    }
    return json_decode($response);
}

/**
 * Get a microCMS entry by content ID and supported content type.
 *
 * @param string $eid  Content ID from URL parameter
 * @return object|null Blog entry object or null
 */
function microcms_get_entry($eid, $type = 'blog')
{
    $eid = trim((string)$eid);
    if ($eid === '') return null;
    $endpoint = $type === 'works' ? '/works/' : '/blog/';
    return microcms_get($endpoint . rawurlencode($eid));
}

/**
 * Extract blog title from microCMS entry object.
 *
 * @param object|null $entry  microCMS blog entry
 * @return string             Blog title or empty string
 */
function microcms_extract_blog_title($entry)
{
    return ($entry && isset($entry->title)) ? trim((string)$entry->title) : '';
}

/**
 * Extract blog summary for meta description.
 *
 * @param object|null $entry  microCMS blog entry
 * @return string             Summary text or empty string
 */
function microcms_extract_blog_description($entry)
{
    if (!$entry) return '';

    foreach (['description', 'summary', 'excerpt'] as $summary_key) {
        if (isset($entry->{$summary_key})) {
            $summary = trim(strip_tags((string)$entry->{$summary_key}));
            if ($summary !== '') {
                return $summary;
            }
        }
    }

    $content = isset($entry->content) ? (string)$entry->content : '';
    $plain_content = trim(preg_replace('/\s+/u', ' ', strip_tags($content)));
    if ($plain_content === '') {
        return '';
    }

    if (function_exists('mb_substr') && function_exists('mb_strlen')) {
        $summary = mb_substr($plain_content, 0, 120, 'UTF-8');
        if (mb_strlen($plain_content, 'UTF-8') > 120) {
            $summary .= '...';
        }
        return $summary;
    }

    $summary = substr($plain_content, 0, 120);
    if (strlen($plain_content) > 120) {
        $summary .= '...';
    }
    return $summary;
}

/**
 * Extract OGP image URL from microCMS entry object.
 *
 * @param object|null $entry  microCMS blog entry
 * @return string             Image URL or empty string
 */
function microcms_extract_blog_image($entry)
{
    if (!$entry) return '';

    foreach (['thumbnail', 'image', 'ogImage'] as $image_key) {
        if (
            isset($entry->{$image_key}) &&
            is_object($entry->{$image_key}) &&
            isset($entry->{$image_key}->url)
        ) {
            $image_url = trim((string)$entry->{$image_key}->url);
            if ($image_url !== '') {
                return $image_url;
            }
        }
    }

    return '';
}

// microCMS meta (used for news and works entry pages)
$requested_entry_type = (string)($_GET['type'] ?? 'blog') === 'works' ? 'works' : 'blog';
$microcms_blog_entry = microcms_get_entry($requested_eid, $requested_entry_type);
$microcms_blog_title = microcms_extract_blog_title($microcms_blog_entry);
$microcms_blog_description = microcms_extract_blog_description($microcms_blog_entry);
$microcms_blog_image = microcms_extract_blog_image($microcms_blog_entry);

$entry_title = $microcms_blog_title !== '' ? $microcms_blog_title : $blog_title;
$entry_description = $microcms_blog_description;
$entry_og_image = $microcms_blog_image;

$url = basename($_SERVER['SCRIPT_NAME']);
function nowUrl()
{
    $url = '';
    if (isset($_SERVER['HTTPS'])) {
        $url .= 'https://';
    } else {
        $url .= 'http://';
    }
    $url .= $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    return $url;
}

function breadcrumbs()
{
    $file_path = $_SERVER['SCRIPT_NAME'];
    $dirs = explode("/", $file_path);
    $dirs = array_values(array_filter($dirs, "strlen"));
    $html = '<li><a href="./"><i class="fas fa-home"></i></a></li>';;
    $url = "";
    foreach ($dirs as $dir) {
        $url .= "/" . $dir;
        if (strtolower($dir) !== 'index.php') {
            $html .= "<li><a href=" . $url . ">" . strtoupper($dir) . "</a></li>";
        }
    }
    echo $html;
}
