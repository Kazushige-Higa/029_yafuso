<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: Thu, 01 Jan 1970 00:00:00 GMT");
?>
<?php require_once './common.php'; ?>
<?php $visitor_tracker_token = yafuso_visitor_tracker_token(); ?>
<!DOCTYPE html>
<html lang="ja">

<head prefix="og:http://ogp.me/ns#">
  <?php if ($ga4_measurement_id !== '') : ?>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo rawurlencode($ga4_measurement_id); ?>"></script>
    <script>
      window.dataLayer = window.dataLayer || [];

      function gtag() {
        dataLayer.push(arguments);
      }
      gtag('js', new Date());

      gtag('config', <?php echo json_encode($ga4_measurement_id, JSON_UNESCAPED_SLASHES); ?>);
    </script>
  <?php endif; ?>
  <script src="/js/visitor_tracker.js" defer></script>
  <meta charset="UTF-8">
  <meta name="yafuso-visitor-token" content="<?= htmlspecialchars($visitor_tracker_token, ENT_QUOTES, 'UTF-8') ?>">
  <?php if (!empty($visitor_form_submit_token)) : ?>
    <meta name="yafuso-form-submit-token" content="<?= htmlspecialchars((string)$visitor_form_submit_token, ENT_QUOTES, 'UTF-8') ?>">
  <?php endif; ?>
  <?php
  $current_script = basename((string)($_SERVER['SCRIPT_NAME'] ?? ''));
  $is_entry_page = $current_script === 'entry.php' || (isset($url) && (bool) preg_match('/^entry\d*\.php$/', (string)$url));
  $is_entry_list_page = $current_script === 'entry_list.php' || (isset($url) && $url === 'entry_list.php');
  $is_top_page   = $current_script === 'index.php' || (isset($url) && $url === 'index.php');
  $clean_meta_value = static function ($value) {
    return trim((string)($value ?? ''));
  };
  $to_absolute_url = static function ($value) {
    $value = trim((string)$value);
    if ($value === '' || preg_match('/^(https?:)?\/\//i', $value) || preg_match('/^data:/i', $value)) {
      return $value;
    }

    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? '';
    if ($host === '') {
      return $value;
    }

    if (substr($value, 0, 1) === '/') {
      return $scheme . '://' . $host . $value;
    }

    $base_path = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
    $base_path = ($base_path === '/' || $base_path === '.') ? '' : rtrim($base_path, '/');
    return $scheme . '://' . $host . $base_path . '/' . preg_replace('/^\.\//', '', $value);
  };

  $site_title = $clean_meta_value($title ?? '');
  $site_description = $clean_meta_value($description ?? '');
  $entry_display_title = $clean_meta_value($entry_title ?? $blog_title ?? '');
  $entry_display_description = $clean_meta_value($entry_description ?? '');
  $entry_display_image = $clean_meta_value($entry_og_image ?? '');
  $display_title = $is_entry_page ? $entry_display_title : $clean_meta_value($page_title ?? '');
  $page_meta_title_value = $clean_meta_value($page_meta_title ?? '');
  $page_meta_description_value = $clean_meta_value($page_meta_description ?? '');
  $page_meta_image_value = $clean_meta_value($page_meta_image ?? '');
  $default_meta_image = $clean_meta_value($ogp_image ?? (($img ?? '') . '/ogp_image.webp'));
  $request_path = parse_url((string)($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH);
  $canonical_path = is_string($request_path) && $request_path !== '' ? $request_path : '/';
  if ($is_entry_page && $requested_eid !== '') {
    $canonical_path = ($requested_entry_type === 'works' ? '/works/' : '/news/') . rawurlencode($requested_eid);
  } elseif ($is_entry_list_page) {
    $list_type_for_canonical = isset($list_type) && in_array($list_type, ['blog', 'works', 'all'], true)
      ? $list_type
      : ((string)($_GET['type'] ?? '') === 'works' ? 'works' : 'blog');
    $archive_for_canonical = isset($archive_month) && preg_match('/^\d{4}-\d{2}$/', (string)$archive_month)
      ? (string)$archive_month
      : '';

    if ($list_type_for_canonical === 'works') {
      $canonical_path = '/works/';
    } elseif ($list_type_for_canonical === 'blog') {
      $canonical_path = '/news/';
    } else {
      $canonical_path = '/entry_list.php';
    }

    $canonical_query = [];
    if ($list_type_for_canonical === 'all') {
      $canonical_query['type'] = 'all';
    }
    if ($archive_for_canonical !== '') {
      $canonical_query['archive'] = $archive_for_canonical;
    }
    if ($canonical_query) {
      $canonical_path .= '?' . http_build_query($canonical_query, '', '&', PHP_QUERY_RFC3986);
    }
  }
  $canonical_url = $to_absolute_url($canonical_path);

  if ($page_meta_title_value !== '') {
    $full_title = $page_meta_title_value;
  } elseif (!$is_top_page && $display_title !== '' && $site_title !== '') {
    $full_title = $display_title . '｜' . $site_title;
  } else {
    $full_title = $site_title;
  }

  if ($page_meta_description_value !== '') {
    $full_description = $page_meta_description_value;
  } elseif ($is_entry_page && $entry_display_description !== '') {
    $full_description = $entry_display_description;
  } else {
    $full_description = $site_description;
  }

  $meta_image = $to_absolute_url(
    $page_meta_image_value !== '' ? $page_meta_image_value : ($entry_display_image !== '' ? $entry_display_image : $default_meta_image)
  );
  ?>
  <title><?= htmlspecialchars($full_title, ENT_QUOTES, 'UTF-8') ?></title>
  <meta name="description" content="<?= htmlspecialchars($full_description, ENT_QUOTES, 'UTF-8') ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
  <meta name="format-detection" content="telephone=no">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="canonical" href="<?= htmlspecialchars($canonical_url, ENT_QUOTES, 'UTF-8') ?>">
  <link href="https://cdn.rs-sys.jp/lib/reset/reset.css" rel="stylesheet">
  <link href="/css/reset.css" rel="stylesheet">
  <link href="/css/setting.css" rel="stylesheet">
  <link href="/css/style.css" rel="stylesheet">
  <link href="/css/animation_scroll.css" rel="stylesheet">
  <link href="https://use.fontawesome.com/releases/v6.1.2/css/all.css" rel="stylesheet">
  <?php echo $page_style ?? ''; ?>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Zen+Maru+Gothic&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=RocknRoll+One&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Reggae+One&display=swap" rel="stylesheet">

  <!-- OGP -->
  <meta property="og:url" content="<?= htmlspecialchars($canonical_url, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:type" content="<?php if ($is_entry_page) {
                                      echo 'article';
                                    } else {
                                      echo 'website';
                                    } ?>">
  <meta property="og:title" content="<?= htmlspecialchars($full_title, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:description" content="<?= htmlspecialchars($full_description, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:site_name" content="<?= htmlspecialchars($site_title, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:image" content="<?= htmlspecialchars($meta_image, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:image:secure_url" content="<?= htmlspecialchars($meta_image, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="630">
  <meta property="og:image:alt" content="<?= htmlspecialchars($full_title, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:locale" content="ja_JP">

  <!-- Twitter / X -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="<?= htmlspecialchars($full_title, ENT_QUOTES, 'UTF-8') ?>">
  <meta name="twitter:description" content="<?= htmlspecialchars($full_description, ENT_QUOTES, 'UTF-8') ?>">
  <meta name="twitter:image" content="<?= htmlspecialchars($meta_image, ENT_QUOTES, 'UTF-8') ?>">
  <meta name="twitter:image:alt" content="<?= htmlspecialchars($full_title, ENT_QUOTES, 'UTF-8') ?>">
  <link href="<?php echo $img; ?>/favicon.webp" rel="shortcut icon" sizes="16x16">
  <link href="<?php echo $img; ?>/favicon.webp" rel="apple-touch-icon" sizes="192x192">
  <link href="<?php echo $img; ?>/favicon.webp" rel="shortcut icon" sizes="192x192">
</head>

<body id="top">
  <?php if (!empty($use_yafuso_layout)) : ?>
    <div class="yafuso_page_029">
      <header>
        <div class="yafuso_header_029">
          <div class="yafuso_header_inner_029">
            <a class="yafuso_logo_029" href="/" aria-label="やふそ屋台村 ちょうちん横丁 トップ">
              <img src="<?php echo $img; ?>/logo.webp" alt="やふそ屋台村 ちょうちん横丁" loading="eager">
            </a>

            <nav class="yafuso_nav_029 pconly" aria-label="メインナビゲーション">
              <ul>
                <li><a href="/">トップページ</a></li>
                <li><a href="/concept.php">コンセプト</a></li>
                <li><a href="/market_stalls.php">屋台のご紹介</a></li>
                <li><a href="/karaoke.php">カラオケワールド ももたろう</a></li>
                <li><a href="/vendors.php">出店をご検討の方へ</a></li>
              </ul>
            </nav>

            <div id="nav_slide_toggle" class="nav_slide_toggle yafuso_menu_toggle_029 sponly">
              <div class="hamburger_icon" aria-hidden="true">
                <span></span>
                <span></span>
                <span></span>
              </div>
              <span class="menu_text">MENU</span>
            </div>

            <div id="nav_slide_right" class="nav_slide_right yafuso_slide_nav_029">
              <div class="nav_overlay"></div>
              <div class="nav_slide_container">
                <div class="nav_menu_area">
                  <nav class="nav_menu_content" aria-label="スマートフォンナビゲーション">
                    <a class="yafuso_slide_nav_logo_029" href="/" aria-label="やふそ屋台村 ちょうちん横丁 トップ">
                      <img src="<?php echo $img; ?>/logo.webp" alt="やふそ屋台村 ちょうちん横丁" loading="lazy">
                    </a>
                    <a href="/">トップページ</a>
                    <a href="/concept.php">コンセプト</a>
                    <a href="/market_stalls.php">屋台のご紹介</a>
                    <a href="/karaoke.php">カラオケワールド ももたろう</a>
                    <a href="/vendors.php">出店をご検討の方へ</a>
                    <div class="yafuso_slide_nav_sns_029">
                      <p><i class="fa-brands fa-instagram" aria-hidden="true"></i>SNS・インスタグラム</p>
                      <ul>
                        <li>
                          <a href="https://www.instagram.com/karaoke.momotaro/" target="_blank" rel="noopener noreferrer">
                            <img src="<?php echo $img; ?>/sns_momotaro.webp" alt="カラオケワールドももたろうのInstagramアイコン" loading="lazy">
                            <span>カラオケワールド<br>ももたろう</span>
                            <small>@karaoke.momotaro</small>
                          </a>
                        </li>
                        <li>
                          <a href="https://www.instagram.com/horumon_okada/" target="_blank" rel="noopener noreferrer">
                            <img src="<?php echo $img; ?>/sns_okada.webp" alt="西成ホルモンおか田のInstagramアイコン" loading="lazy">
                            <span>西成ホルモン<br>おか田</span>
                            <small>@horumon_okada</small>
                          </a>
                        </li>
                        <li>
                          <a href="https://www.instagram.com/tokunobuyatairamen/" target="_blank" rel="noopener noreferrer">
                            <img src="<?php echo $img; ?>/sns_tokunobu.webp" alt="屋台ラーメンとくのぶのInstagramアイコン" loading="lazy">
                            <span>屋台ラーメン<br>とくのぶ</span>
                            <small>@tokunobuyatairamen</small>
                          </a>
                        </li>
                        <li>
                          <a href="https://www.instagram.com/ta_tan0301/" target="_blank" rel="noopener noreferrer">
                            <img src="<?php echo $img; ?>/sns_tatan.webp" alt="居酒屋たーたんのInstagramアイコン" loading="lazy">
                            <span>居酒屋<br>たーたん</span>
                            <small>@ta_tan0301</small>
                          </a>
                        </li>
                        <li>
                          <a href="https://www.instagram.com/tebachu.tebasaki/" target="_blank" rel="noopener noreferrer">
                            <img src="<?php echo $img; ?>/sns_tebachu.webp" alt="手羽先居酒屋てばちゅ〜のInstagramアイコン" loading="lazy">
                            <span>手羽先居酒屋<br>てばちゅ〜</span>
                            <small>@tebachu.tebasaki</small>
                          </a>
                        </li>
                      </ul>
                    </div>
                  </nav>
                </div>
              </div>
            </div>
          </div>
        </div>
      </header>
      <div class='space_0 space_sp4'></div>
    <?php endif; ?>
