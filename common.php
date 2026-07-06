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
$img = "images"; ///images
$ogp_image = $img . "/ogp_image.webp";

$weblink = "";
$instagram = "";
$line = "";
$mail = "";
$karaoke_reservation_mail_to = $mail; // カラオケ予約フォームの送信先メールアドレス
$karaoke_reservation_mail_from = $karaoke_reservation_mail_to; // 送信元メールアドレス
$youtube = "";
$tiktok = "";
$facebook = "";
$x = "";

ini_set('display_errors', "Off");

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
$microcms_service_id = "";
$microcms_api_key    = "";
$microcms_base_url   = "https://" . $microcms_service_id . ".microcms.io/api/v1";

/**
 * microCMS API fetch function
 *
 * @param string $endpoint  API endpoint (e.g. "/blog", "/blog/article-id", "/news?limit=5")
 * @return object|null      Decoded JSON response or null on failure
 */
function microcms_get($endpoint)
{
    global $microcms_base_url, $microcms_api_key;

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
 * Get microCMS blog entry by Content ID.
 *
 * @param string $eid  Content ID from URL parameter
 * @return object|null Blog entry object or null
 */
function microcms_get_blog_entry($eid)
{
    $eid = trim((string)$eid);
    if ($eid === '') return null;
    return microcms_get("/blog/" . rawurlencode($eid));
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

// microCMS blog meta (used for entry pages)
$microcms_blog_entry = microcms_get_blog_entry($requested_eid);
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
