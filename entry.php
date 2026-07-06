<?php
$entry_type = isset($_GET["type"]) ? trim((string)$_GET["type"]) : 'blog';
if ($entry_type !== 'works') {
  $entry_type = 'blog';
}

$page_title = ($entry_type === 'works') ? "制作実績" : "新着情報";
$page_title_eng = ($entry_type === 'works') ? "Portfolio" : "News";
?>
<?php include_once './header.php'; ?>

<?php
// Get article ID from URL parameter
$eid = isset($_GET["eid"]) ? trim($_GET["eid"]) : '';
$entry_endpoint = ($entry_type === 'works') ? "/works" : "/blog";
$list_back_link = "entry_list.php?type=" . urlencode($entry_type);
$list_back_text = ($entry_type === 'works') ? "制作実績一覧へ戻る" : "記事一覧へ戻る";
$related_heading = ($entry_type === 'works') ? "関連制作実績" : "関連記事";
$post = !empty($eid) ? microcms_get($entry_endpoint . "/" . rawurlencode($eid)) : null;
?>

<div class="blog_wrap">
  <div class="column">
    <main class="mainwrap">

      <?php if ($post): ?>
        <?php
        if ($entry_type === 'works') {
          $category_name = '制作実績';
          $category_link = 'entry_list.php?type=works';
        } else {
          $primary_tag = (isset($post->tags) && !empty($post->tags)) ? $post->tags[0] : null;
          $category_name = ($primary_tag && isset($primary_tag->name)) ? $primary_tag->name : 'カテゴリーなし';
          $category_link = ($primary_tag && isset($primary_tag->id))
            ? 'entry_list.php?type=blog&tag=' . urlencode($primary_tag->id)
            : 'entry_list.php?type=blog';
        }

        $writer_name = isset($post->writer->name) ? $post->writer->name : 'ライター';
        $writer_image = isset($post->writer->image->url) ? $post->writer->image->url : '';
        $writer_profile = '';
        if (isset($post->writer)) {
          foreach (['profile', 'description', 'text', 'body', 'content'] as $writer_profile_key) {
            if (isset($post->writer->{$writer_profile_key})) {
              $writer_profile_candidate = trim((string)$post->writer->{$writer_profile_key});
              if ($writer_profile_candidate !== '') {
                $writer_profile = $writer_profile_candidate;
                break;
              }
            }
          }
        }
        if ($writer_profile === '') {
          $writer_profile = 'プロフィール情報は準備中です。';
        }
        $writer_profile_has_html = preg_match('/<[^>]+>/', $writer_profile) === 1;

        $view_count = '-';
        foreach (['viewCount', 'accessCount', 'pv', 'views'] as $view_key) {
          if (isset($post->{$view_key}) && $post->{$view_key} !== '') {
            $view_count = $post->{$view_key};
            break;
          }
        }

        $published_timestamp = isset($post->publishedAt) ? strtotime($post->publishedAt) : (isset($post->createdAt) ? strtotime($post->createdAt) : false);
        $updated_timestamp = isset($post->updatedAt) ? strtotime($post->updatedAt) : $published_timestamp;
        $published_date_display = $published_timestamp ? date('Y.m.d', $published_timestamp) : '-';
        $updated_date_display = $updated_timestamp ? date('Y.m.d', $updated_timestamp) : '-';
        $published_date_attr = $published_timestamp ? date('Y-m-d', $published_timestamp) : '';
        $updated_date_attr = $updated_timestamp ? date('Y-m-d', $updated_timestamp) : '';

        $summary = '';
        foreach (['description', 'summary', 'excerpt'] as $summary_key) {
          if (isset($post->{$summary_key}) && trim(strip_tags((string)$post->{$summary_key})) !== '') {
            $summary = trim(strip_tags((string)$post->{$summary_key}));
            break;
          }
        }
        if ($summary === '') {
          $plain_content = trim(preg_replace('/\s+/u', ' ', strip_tags((string)($post->content ?? ''))));
          if (function_exists('mb_substr') && function_exists('mb_strlen')) {
            $summary = mb_substr($plain_content, 0, 120, 'UTF-8');
            if (mb_strlen($plain_content, 'UTF-8') > 120) {
              $summary .= '...';
            }
          } else {
            $summary = substr($plain_content, 0, 120);
            if (strlen($plain_content) > 120) {
              $summary .= '...';
            }
          }
        }
        ?>

        <article class="blog_article">
          <header>
            <div class="blog_category">
              <a href="<?php echo htmlspecialchars($category_link, ENT_QUOTES, 'UTF-8'); ?>" rel="category tag"><?php echo htmlspecialchars($category_name, ENT_QUOTES, 'UTF-8'); ?></a>
            </div>
            <div>
              <ul class="breadcrumb">
                <li><a href="./"><i class="fas fa-home"></i></a></li>
                <li><a href="entry.php?type=<?php echo urlencode($entry_type); ?>&eid=<?php echo urlencode($post->id); ?>"><?php echo htmlspecialchars($post->title, ENT_QUOTES, 'UTF-8'); ?></a></li>
              </ul>
            </div>
            <h1>
              <?php echo htmlspecialchars($post->title, ENT_QUOTES, 'UTF-8'); ?>
            </h1>
            <address>
              <author class="author-box">
                <div class="author-img">
                  <?php if ($writer_image !== ''): ?>
                    <img src="<?php echo htmlspecialchars($writer_image, ENT_QUOTES, 'UTF-8'); ?>?w=96" alt="<?php echo htmlspecialchars($writer_name, ENT_QUOTES, 'UTF-8'); ?>の画像" loading="lazy">
                  <?php else: ?>
                    <img src="<?php echo $img; ?>/no-img.jpg" alt="ライターの画像" loading="lazy">
                  <?php endif; ?>
                </div>
                <div class="author-name">
                  <a href="/profile" rel="author">
                    <?php echo htmlspecialchars($writer_name, ENT_QUOTES, 'UTF-8'); ?>
                  </a>
                </div>
              </author>
            </address>
            <div>
              <button id="like-button" class="like-button" data-post-id="<?php echo htmlspecialchars($post->id, ENT_QUOTES, 'UTF-8'); ?>">
                <i class="fas fa-heart"></i><span id="like-count">0</span><span id="like-text"></span>
              </button>
              <div class="access-count">
                <i class="fas fa-eye"></i><span id="access-count"><?php echo htmlspecialchars((string)$view_count, ENT_QUOTES, 'UTF-8'); ?></span>
              </div>
              <time class="date-article" itemprop="datePublished" datetime="<?php echo $published_date_attr; ?>"><i class="far fa-clock"></i><?php echo htmlspecialchars($published_date_display, ENT_QUOTES, 'UTF-8'); ?></time>
              <time class="date-updated" itemprop="dateModified" datetime="<?php echo $updated_date_attr; ?>"><i class="fas fa-undo-alt"></i><?php echo htmlspecialchars($updated_date_display, ENT_QUOTES, 'UTF-8'); ?></time>
            </div>

            <div class="thumbnail">
              <?php if (isset($post->thumbnail->url)): ?>
                <img src="<?php echo htmlspecialchars($post->thumbnail->url, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($post->title, ENT_QUOTES, 'UTF-8'); ?>" loading="lazy">
              <?php else: ?>
                <img src="<?php echo $img; ?>/no-img.jpg" alt="イメージ画像" loading="lazy">
              <?php endif; ?>
            </div>

            <div class="description">
              <p>
                <?php echo htmlspecialchars($summary, ENT_QUOTES, 'UTF-8'); ?>
              </p>
            </div>
          </header>

          <div data-anchors='h2,h3,h4,h5,h6' data-collapsable='true'>
            <div class="table_of_contents">
              <div class="table_of_contents_header">
                <div class="table_of_contents_title">目次</div>
                <div class="table_of_contents_icon">
                  <span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true" focusable="false">
                      <path d="M10 15c-.3 0-.5-.1-.7-.3L3.3 8.7a1 1 0 1 1 1.4-1.4L10 12.59l5.3-5.3a1 1 0 0 1 1.4 1.42l-6 6A1 1 0 0 1 10 15z" />
                    </svg>
                  </span>
                </div>
              </div>
              <div class="table_of_contents_body">
                <ol class="table_of_contents_list"></ol>
              </div>
            </div>
          </div>

          <div id="countPost">
            <?php echo $post->content; ?>
          </div>


          <div class='space_5 space_sp2'></div>

          <footer>
            <div class="article_writer_card">
              <div class="writer_side">
                <div class="writer_label">この記事を書いた人</div>
                <figure class="writer_avatar">
                  <?php if ($writer_image !== ''): ?>
                    <img src="<?php echo htmlspecialchars($writer_image, ENT_QUOTES, 'UTF-8'); ?>?w=160" alt="<?php echo htmlspecialchars($writer_name, ENT_QUOTES, 'UTF-8'); ?>の画像" loading="lazy">
                  <?php else: ?>
                    <img src="<?php echo $img; ?>/no-img.jpg" alt="ライターの画像" loading="lazy">
                  <?php endif; ?>
                </figure>
                <p class="writer_name"><?php echo htmlspecialchars($writer_name, ENT_QUOTES, 'UTF-8'); ?></p>
              </div>
              <div class="writer_profile">
                <?php if ($writer_profile_has_html): ?>
                  <?php echo $writer_profile; ?>
                <?php else: ?>
                  <p><?php echo nl2br(htmlspecialchars($writer_profile, ENT_QUOTES, 'UTF-8')); ?></p>
                <?php endif; ?>
              </div>
            </div>
          </footer>
        </article>

        <div class='flex a_center base_color line_height_12 p5 bg_white j_center b_m10'>
          <span class='r_m5'>
            <img width='40px' src='<?php echo $img; ?>/favicon.png' alt='favicon' loading='lazy'>
          </span>
          <span class='fs_30 fs_sp25'>
            <?php echo htmlspecialchars($related_heading, ENT_QUOTES, 'UTF-8'); ?>
          </span>
        </div>

        <?php
        // Fetch latest 3 posts from selected microCMS endpoint
        $sidebar_posts = microcms_get($entry_endpoint . "?limit=3&orders=-publishedAt");
        ?>

        <ul class="post_list_card grid set2 sp2 gap1">
          <?php if ($sidebar_posts && !empty($sidebar_posts->contents)): ?>
            <?php foreach ($sidebar_posts->contents as $side_post): ?>
              <?php
              $side_date_source = !empty($side_post->publishedAt) ? $side_post->publishedAt : (!empty($side_post->createdAt) ? $side_post->createdAt : '');
              $side_timestamp = ($side_date_source !== '') ? strtotime($side_date_source) : false;
              $side_date_attr = $side_timestamp ? date('Y-m-d', $side_timestamp) : '';
              $side_date_text = $side_timestamp ? date('Y.m.d', $side_timestamp) : '-';
              ?>
              <li>
                <a href='entry.php?type=<?php echo urlencode($entry_type); ?>&eid=<?php echo urlencode($side_post->id); ?>'>
                  <figure class="img">
                    <?php if (isset($side_post->thumbnail->url)): ?>
                      <img src="<?php echo htmlspecialchars($side_post->thumbnail->url, ENT_QUOTES, 'UTF-8'); ?>?w=200" alt="<?php echo htmlspecialchars($side_post->title, ENT_QUOTES, 'UTF-8'); ?>" loading="lazy">
                    <?php else: ?>
                      <img src="<?php echo $img; ?>/no-img.jpg" alt="<?php echo htmlspecialchars($side_post->title, ENT_QUOTES, 'UTF-8'); ?>" loading="lazy">
                    <?php endif; ?>
                  </figure>
                  <div class="detail">
                    <div class="title">
                      <?php echo htmlspecialchars($side_post->title, ENT_QUOTES, 'UTF-8'); ?>
                    </div>
                    <time class="date" datetime="<?php echo $side_date_attr; ?>">
                      <?php echo htmlspecialchars($side_date_text, ENT_QUOTES, 'UTF-8'); ?>
                    </time>
                  </div>
                </a>
              </li>
            <?php endforeach; ?>
          <?php endif; ?>
        </ul>

      <?php else: ?>
        <article class="blog_article">
          <p class="tcenter"><?php echo ($entry_type === 'works') ? '制作実績が見つかりませんでした。' : '記事が見つかりませんでした。'; ?></p>
          <div class='space_3 space_sp1'></div>
          <button class='btn_mini radius center'><a href='<?php echo htmlspecialchars($list_back_link, ENT_QUOTES, 'UTF-8'); ?>'><?php echo htmlspecialchars($list_back_text, ENT_QUOTES, 'UTF-8'); ?></a></button>
        </article>
      <?php endif; ?>

    </main>

    <!-- side -->
    <?php include 'entry_sidebar.php'; ?>
  </div>
</div>

<script src="js/blog_cms.js" defer></script>
<?php include_once './footer.php'; ?>
