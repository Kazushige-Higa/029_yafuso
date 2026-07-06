<?php
$requested_list_type = isset($_GET['type']) ? trim((string)$_GET['type']) : '';
$archive_month = isset($_GET['archive']) ? trim((string)$_GET['archive']) : '';
if (!preg_match('/^\d{4}-\d{2}$/', $archive_month)) {
  $archive_month = '';
}

if ($requested_list_type === 'blog' || $requested_list_type === 'works' || $requested_list_type === 'all') {
  $list_type = $requested_list_type;
} elseif ($archive_month !== '') {
  $list_type = 'all';
} else {
  $list_type = 'blog';
}

if ($list_type === 'works') {
  $page_title = "制作実績";
  $page_title_eng = "Portfolio";
} elseif ($list_type === 'all') {
  $page_title = "アーカイブ";
  $page_title_eng = "Archive";
} else {
  $page_title = "新着情報";
  $page_title_eng = "News";
}
?>
<?php include_once './header.php'; ?>
<?php include_once './page_title.php'; ?>

<div class="blog_wrap">
  <div class="column">

    <!-- main -->
    <main class="mainwrap">

      <section>
        <?php
        if ($archive_month !== '') {
          $archive_timestamp = strtotime($archive_month . '-01');
          if ($archive_timestamp !== false) {
            if ($list_type === 'works') {
              $archive_title = date('Y年n月', $archive_timestamp) . 'の制作実績一覧';
            } elseif ($list_type === 'all') {
              $archive_title = date('Y年n月', $archive_timestamp) . 'のアーカイブ一覧';
            } else {
              $archive_title = date('Y年n月', $archive_timestamp) . 'の記事一覧';
            }
          } else {
            $archive_title = ($list_type === 'works') ? '制作実績一覧' : (($list_type === 'all') ? 'アーカイブ一覧' : '記事一覧');
          }
        } else {
          $archive_title = ($list_type === 'works') ? '制作実績一覧' : (($list_type === 'all') ? 'アーカイブ一覧' : '記事一覧');
        }
        ?>

        <div class='flex a_center base_color line_height_12 p5 bg_f2 j_center'>
          <span class='fs_30 fs_sp25'>
            <?php echo htmlspecialchars($archive_title, ENT_QUOTES, 'UTF-8'); ?>
          </span>
        </div>
        <div class='space_3 space_sp1'></div>

        <?php
        // Fetch list from microCMS endpoint (archive filter is applied in PHP)
        $sidebar_posts = [];
        $list_limit = 100;
        $list_targets = ($list_type === 'all')
          ? ['blog' => '/blog', 'works' => '/works']
          : [$list_type => (($list_type === 'works') ? '/works' : '/blog')];

        foreach ($list_targets as $entry_type_key => $list_endpoint) {
          $list_offset = 0;
          $list_total = 0;

          do {
            $list_response = microcms_get($list_endpoint . "?limit=" . $list_limit . "&offset=" . $list_offset . "&orders=-publishedAt");
            if (!$list_response || empty($list_response->contents)) {
              break;
            }

            foreach ($list_response->contents as $list_post) {
              $list_date_source = '';
              if (!empty($list_post->publishedAt)) {
                $list_date_source = $list_post->publishedAt;
              } elseif (!empty($list_post->createdAt)) {
                $list_date_source = $list_post->createdAt;
              }

              if ($archive_month !== '' && strpos($list_date_source, $archive_month) !== 0) {
                continue;
              }

              $list_post->_entry_type = $entry_type_key;
              $list_post->_sort_timestamp = ($list_date_source !== '' && strtotime($list_date_source) !== false) ? strtotime($list_date_source) : 0;
              $sidebar_posts[] = $list_post;
            }

            $list_offset += $list_limit;
            $list_total = isset($list_response->totalCount) ? (int)$list_response->totalCount : $list_offset;
          } while ($list_offset < $list_total);
        }

        if ($list_type === 'all' && !empty($sidebar_posts)) {
          usort($sidebar_posts, function ($a, $b) {
            $a_sort = isset($a->_sort_timestamp) ? (int)$a->_sort_timestamp : 0;
            $b_sort = isset($b->_sort_timestamp) ? (int)$b->_sort_timestamp : 0;
            if ($a_sort === $b_sort) {
              return 0;
            }
            return ($a_sort > $b_sort) ? -1 : 1;
          });
        }
        ?>

        <?php if (!empty($sidebar_posts)): ?>
          <ul class="post_list">
            <?php foreach ($sidebar_posts as $side_post): ?>
              <?php
              $side_date_source = !empty($side_post->publishedAt) ? $side_post->publishedAt : (!empty($side_post->createdAt) ? $side_post->createdAt : '');
              $side_timestamp = ($side_date_source !== '') ? strtotime($side_date_source) : false;
              $side_date_attr = $side_timestamp ? date('Y-m-d', $side_timestamp) : '';
              $side_date_text = $side_timestamp ? date('Y.m.d', $side_timestamp) : '-';

              $side_description = '';
              foreach (['description', 'summary', 'excerpt'] as $summary_key) {
                if (isset($side_post->{$summary_key}) && trim(strip_tags((string)$side_post->{$summary_key})) !== '') {
                  $side_description = trim(strip_tags((string)$side_post->{$summary_key}));
                  break;
                }
              }
              if ($side_description === '') {
                $side_plain_content = trim(preg_replace('/\s+/u', ' ', strip_tags((string)($side_post->content ?? ''))));
                if (function_exists('mb_substr') && function_exists('mb_strlen')) {
                  $side_description = mb_substr($side_plain_content, 0, 80, 'UTF-8');
                  if (mb_strlen($side_plain_content, 'UTF-8') > 80) {
                    $side_description .= '...';
                  }
                } else {
                  $side_description = substr($side_plain_content, 0, 80);
                  if (strlen($side_plain_content) > 80) {
                    $side_description .= '...';
                  }
                }
              }

              $side_post_type = ($list_type === 'all' && !empty($side_post->_entry_type))
                ? $side_post->_entry_type
                : $list_type;
              ?>
              <li>
                <a href='entry.php?type=<?php echo urlencode($side_post_type); ?>&eid=<?php echo urlencode($side_post->id); ?>'>
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
                    <div class="text">
                      <?php echo htmlspecialchars($side_description, ENT_QUOTES, 'UTF-8'); ?>
                    </div>
                    <time class="date" datetime="<?php echo $side_date_attr; ?>">
                      <?php echo htmlspecialchars($side_date_text, ENT_QUOTES, 'UTF-8'); ?>
                    </time>
                  </div>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php else: ?>
          <p>
            <?php
            if ($list_type === 'works') {
              echo '該当する制作実績がありません。';
            } elseif ($list_type === 'all') {
              echo '該当するアーカイブデータがありません。';
            } else {
              echo '該当する記事がありません。';
            }
            ?>
          </p>
        <?php endif; ?>

      </section>
    </main>

    <!-- side -->
    <?php include 'entry_sidebar.php'; ?>

  </div>
</div>

<?php include_once './footer.php'; ?>
