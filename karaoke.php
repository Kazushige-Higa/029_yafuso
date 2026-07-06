<?php
require_once './common.php';

$page_title = "カラオケワールド ももたろう";
$page_meta_title = "カラオケワールド ももたろう｜やふそ屋台村 ちょうちん横丁";
$page_meta_description = "屋台村に隣接するカラオケワールド ももたろう。飲み放題、屋台料理の持ち込み、大人数個室、ダーツも楽しめるカラオケ店です。";
$page_meta_image = $img . "/ogp_image_momotarou.jpg";
$page_style = "";
$use_yafuso_layout = true;
$karaoke_reservation_timezone = new DateTimeZone('Asia/Tokyo');
$karaoke_today = (new DateTimeImmutable('today', $karaoke_reservation_timezone))->format('Y-m-d');
$karaoke_reservation_time_options = [
    '19:00',
    '19:30',
    '20:00',
    '20:30',
    '21:00',
    '21:30',
    '22:00',
    '22:30',
    '23:00',
    '23:30',
    '00:00',
    '00:30',
    '01:00',
    '01:30',
];
$karaoke_reservation_plan_options = [
    'カラオケ利用',
    '飲み放題付きカラオケ',
    '宴会・二次会',
    'キッズルーム希望',
    '未定・相談したい',
];
$karaoke_reservation_room_options = [
    '指定なし',
    '4〜8名個室',
    '4〜10名個室',
    '8〜18名個室',
    '座敷個室',
    'キッズスペース希望',
];
$karaoke_reservation_values = [
    'name' => '',
    'email' => '',
    'tel' => '',
    'reservation_date' => '',
    'reservation_time' => '',
    'guests' => '',
    'plan' => '',
    'room_request' => '指定なし',
    'message' => '',
];
$karaoke_reservation_errors = [];

if (!function_exists('yafuso_karaoke_form_h')) {
    function yafuso_karaoke_form_h($value)
    {
        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('yafuso_karaoke_post_value')) {
    function yafuso_karaoke_post_value($key, $preserve_lines = false)
    {
        $value = trim((string)($_POST[$key] ?? ''));
        $value = strip_tags($value);
        if (!$preserve_lines) {
            $value = preg_replace('/[\r\n]+/', ' ', $value);
        }
        return $value;
    }
}

if (!function_exists('yafuso_karaoke_clean_mail_address')) {
    function yafuso_karaoke_clean_mail_address($mail_address)
    {
        $mail_address = trim((string)$mail_address);
        $mail_address = preg_replace('/^mailto:/i', '', $mail_address);
        $mail_address = preg_replace('/\?.*$/', '', $mail_address);
        $mail_address = preg_replace('/[\r\n].*$/', '', $mail_address);
        return trim($mail_address);
    }
}

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && (string)($_POST['form_type'] ?? '') === 'karaoke_reservation') {
    foreach ($karaoke_reservation_values as $key => $default_value) {
        $karaoke_reservation_values[$key] = yafuso_karaoke_post_value($key, $key === 'message');
    }

    if (trim((string)($_POST['website'] ?? '')) !== '') {
        $karaoke_reservation_errors[] = '送信内容を確認できませんでした。時間をおいて再度お試しください。';
    }

    if ($karaoke_reservation_values['name'] === '') {
        $karaoke_reservation_errors[] = 'お名前を入力してください。';
    }

    if (!filter_var($karaoke_reservation_values['email'], FILTER_VALIDATE_EMAIL)) {
        $karaoke_reservation_errors[] = 'メールアドレスを正しく入力してください。';
    }

    if ($karaoke_reservation_values['tel'] === '') {
        $karaoke_reservation_errors[] = '電話番号を入力してください。';
    }

    $reservation_date = DateTimeImmutable::createFromFormat('!Y-m-d', $karaoke_reservation_values['reservation_date'], $karaoke_reservation_timezone);
    $date_parse_errors = DateTimeImmutable::getLastErrors();
    $has_date_parse_error = $date_parse_errors !== false && ($date_parse_errors['warning_count'] > 0 || $date_parse_errors['error_count'] > 0);
    if (!$reservation_date || $has_date_parse_error) {
        $karaoke_reservation_errors[] = '予約希望日を正しく選択してください。';
    } elseif ($reservation_date < new DateTimeImmutable('today', $karaoke_reservation_timezone)) {
        $karaoke_reservation_errors[] = '予約希望日は本日以降の日付を選択してください。';
    }

    if (!in_array($karaoke_reservation_values['reservation_time'], $karaoke_reservation_time_options, true)) {
        $karaoke_reservation_errors[] = '予約希望時間を選択してください。';
    }

    $guest_count = filter_var(
        $karaoke_reservation_values['guests'],
        FILTER_VALIDATE_INT,
        ['options' => ['min_range' => 1, 'max_range' => 18]]
    );
    if ($guest_count === false) {
        $karaoke_reservation_errors[] = 'ご利用人数は1名から18名までで入力してください。';
    }

    if (!in_array($karaoke_reservation_values['plan'], $karaoke_reservation_plan_options, true)) {
        $karaoke_reservation_errors[] = 'ご利用内容を選択してください。';
    }

    if (!in_array($karaoke_reservation_values['room_request'], $karaoke_reservation_room_options, true)) {
        $karaoke_reservation_errors[] = 'ご希望のお部屋を選択してください。';
    }

    if ((string)($_POST['agree'] ?? '') !== '1') {
        $karaoke_reservation_errors[] = '折り返し連絡後に予約確定となることへ同意してください。';
    }

    $mail_to = yafuso_karaoke_clean_mail_address($karaoke_reservation_mail_to ?? '');
    $mail_from = yafuso_karaoke_clean_mail_address($karaoke_reservation_mail_from ?? $mail_to);
    if (!filter_var($mail_to, FILTER_VALIDATE_EMAIL)) {
        $karaoke_reservation_errors[] = '予約フォームの送信先メールアドレスが未設定です。恐れ入りますが、お電話でご予約ください。';
    }
    if (!filter_var($mail_from, FILTER_VALIDATE_EMAIL)) {
        $mail_from = $mail_to;
    }

    if (empty($karaoke_reservation_errors)) {
        $subject = '【カラオケ予約】' . $karaoke_reservation_values['name'] . '様 ' . $karaoke_reservation_values['reservation_date'] . ' ' . $karaoke_reservation_values['reservation_time'];
        $body = implode("\n", [
            'カラオケワールド ももたろうのオンライン予約フォームから送信がありました。',
            '',
            '※この時点では予約は確定していません。内容確認後、お客様へ折り返しご連絡ください。',
            '',
            '受付日時：' . (new DateTimeImmutable('now', $karaoke_reservation_timezone))->format('Y-m-d H:i:s'),
            'お名前：' . $karaoke_reservation_values['name'],
            'メールアドレス：' . $karaoke_reservation_values['email'],
            '電話番号：' . $karaoke_reservation_values['tel'],
            '予約希望日：' . $karaoke_reservation_values['reservation_date'],
            '予約希望時間：' . $karaoke_reservation_values['reservation_time'],
            'ご利用人数：' . $karaoke_reservation_values['guests'] . '名',
            'ご利用内容：' . $karaoke_reservation_values['plan'],
            'ご希望のお部屋：' . $karaoke_reservation_values['room_request'],
            '',
            'ご要望・備考：',
            $karaoke_reservation_values['message'] !== '' ? $karaoke_reservation_values['message'] : 'なし',
            '',
            '送信元ページ：' . nowUrl(),
        ]);
        $headers = implode("\r\n", [
            'From: ' . $mail_from,
            'Reply-To: ' . $karaoke_reservation_values['email'],
            'Content-Type: text/plain; charset=UTF-8',
            'X-Mailer: PHP/' . phpversion(),
        ]);

        if (function_exists('mb_language')) {
            mb_language('Japanese');
        }
        if (function_exists('mb_internal_encoding')) {
            mb_internal_encoding('UTF-8');
        }

        if (function_exists('mb_send_mail')) {
            $sent = mb_send_mail($mail_to, $subject, $body, $headers);
        } else {
            $encoded_subject = function_exists('mb_encode_mimeheader') ? mb_encode_mimeheader($subject, 'UTF-8') : '=?UTF-8?B?' . base64_encode($subject) . '?=';
            $sent = mail($mail_to, $encoded_subject, $body, $headers);
        }

        if ($sent) {
            header('Location: thanks.php?type=karaoke_reservation');
            exit;
        }

        $karaoke_reservation_errors[] = '送信に失敗しました。時間をおいて再度お試しいただくか、お電話でご予約ください。';
    }
}

$page_script = <<<'HTML'
<script src="js/gallery_thumbnail.js" defer></script>
<script src="js/slider_fullslider.js" defer></script>
<script>
    (() => {
        const initYafusoRequiredForms = function() {
            const forms = document.querySelectorAll('[data-yafuso-required-form]');
            forms.forEach(function(form) {
                const submitButton = form.querySelector('[type="submit"]');
                if (!submitButton) {
                    return;
                }
                const syncSubmitState = function() {
                    submitButton.disabled = !form.checkValidity();
                };
                form.addEventListener('input', syncSubmitState);
                form.addEventListener('change', syncSubmitState);
                syncSubmitState();
            });
        };

        const initYafusoKaraokeModal = function() {
            const modal = document.querySelector('[data-yafuso-karaoke-modal]');
            const openButton = document.querySelector('[data-yafuso-karaoke-menu-open]');
            const closeButtons = document.querySelectorAll('[data-yafuso-karaoke-menu-close]');
            if (!modal || !openButton) {
                return;
            }

            const setModal = function(isOpen) {
                modal.classList.toggle('is_open_029', isOpen);
                modal.setAttribute('aria-hidden', isOpen ? 'false' : 'true');
                document.documentElement.classList.toggle('yafuso_modal_open_029', isOpen);
            };

            const syncModalFromHash = function() {
                setModal(window.location.hash === '#karaoke_drink_menu_modal');
            };

            openButton.addEventListener('click', function() {
                setModal(true);
            });

            closeButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    setModal(false);
                });
            });

            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape' && modal.getAttribute('aria-hidden') === 'false') {
                    setModal(false);
                }
            });
            window.addEventListener('hashchange', syncModalFromHash);
            syncModalFromHash();
            window.yafusoKaraokeModalReady = true;
        };

        const initYafusoKaraokePage = function() {
            initYafusoKaraokeModal();
            initYafusoRequiredForms();
        };

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initYafusoKaraokePage);
        } else {
            initYafusoKaraokePage();
        }
    })();
</script>
HTML;
?>
<?php include_once './header.php'; ?>

<main>
    <!-- カラオケワールドももたろう karaoke.php -->
    <div class='overflow'>
        <div id="karaoke_page" class="yafuso_karaoke_029">
            <section>
                <div class="yafuso_karaoke_hero_029">
                    <div class="yafuso_karaoke_hero_photo_029 slider_fullslider_wrap" aria-hidden="true">
                        <ul class="slider_fullslider yafuso_karaoke_hero_slider_029">
                            <li class="slide active"><img src="<?php echo $img; ?>/karaoke_generated_party.webp" alt="カラオケワールドももたろうの宴会のイメージ" loading="eager"></li>
                            <li class="slide"><img src="<?php echo $img; ?>/karaoke_generated_drinks.webp" alt="カラオケワールドももたろうの飲み放題ドリンクイメージ" loading="eager"></li>
                            <li class="slide"><img src="<?php echo $img; ?>/karaoke_1.webp" alt="屋台村の料理と一緒に楽しむカラオケ利用イメージ" loading="lazy"></li>
                            <li class="slide"><img src="<?php echo $img; ?>/karaoke_4.webp" alt="カラオケワールドももたろうの大人数向けルーム" loading="lazy"></li>
                            <li class="slide"><img src="<?php echo $img; ?>/karaoke_momotaro20.webp" alt="カラオケワールドももたろうのキッズスペースと畳ルーム" loading="lazy"></li>
                        </ul>
                    </div>
                    <div class="yafuso_single_029">
                        <div class="yafuso_karaoke_hero_inner_029">
                            <p class="yafuso_karaoke_kicker_029 act blur">KARAOKE WORLD MOMOTARO</p>
                            <h2 class="act blur delay_1">カラオケワールド<br>ももたろう</h2>
                            <p class="yafuso_karaoke_copy_029 act inup delay_2">飲んで、歌って、食べて。<br>全部ここで叶う。</p>
                            <p class="yafuso_karaoke_intro_029 act inup delay_3">屋台村「ちょうちん横丁」と同じ建物にある、ここにしかないスタイルのカラオケ店。隣の屋台村から本格フードをルームに持ち込みながら歌える、ももたろうだけの体験です。</p>
                            <div class="yafuso_karaoke_hero_actions_029 act inup delay_4">
                                <a href="#karaoke_reservation"><i class="fa-solid fa-calendar-check" aria-hidden="true"></i>オンライン予約</a>
                                <a href="tel:098-879-1055"><i class="fa-solid fa-phone" aria-hidden="true"></i>098-879-1055</a>
                                <a href="#karaoke_price"><i class="fa-solid fa-yen-sign" aria-hidden="true"></i>料金を見る</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section>
                <div class="yafuso_karaoke_logo_strip_029">
                    <div class="yafuso_single_029">
                        <figure class="yafuso_karaoke_logo_029 act blur">
                            <img src="<?php echo $img; ?>/momotarou.webp" alt="カラオケするならももたろう" loading="lazy">
                        </figure>
                    </div>
                </div>
            </section>

            <section>
                <div class="yafuso_karaoke_campaign_029">
                    <div class="yafuso_single_029">
                        <div class="yafuso_karaoke_campaign_inner_029">
                            <div class="yafuso_karaoke_campaign_text_029 act blur">
                                <span>平日限定！早得キャンペーン実施中</span>
                                <h2>20時までにご来店で、<br>1時間飲み放題が無料！</h2>
                                <p>友達を誘ってちょっと早めに来るだけで、お得な時間のスタートが切れます。仕事終わりの一次会にも、夕食がてら気軽に立ち寄るのにもぴったりです。</p>
                            </div>
                            <figure class="yafuso_karaoke_campaign_photo_029 act inup delay_1">
                                <img src="<?php echo $img; ?>/karaoke_momotaro16.webp" alt="カラオケワールドももたろうの個室イメージ" loading="lazy">
                            </figure>
                        </div>
                    </div>
                </div>
            </section>

            <section>
                <div class="yafuso_karaoke_food_029">
                    <div class="yafuso_single_029">
                        <div class="yafuso_karaoke_food_grid_029">
                            <div class="yafuso_karaoke_food_text_029 act inup">
                                <span class="yafuso_lower_lantern_icon_029" aria-hidden="true">食</span>
                                <h2>なんと！屋台村の料理は<br>持ち込みOK♪</h2>
                                <p>ホルモン焼き、ラーメン、手羽先、沖縄料理。好きなものをカラオケルームに持ち込んで、歌いながら本格フードが楽しめるのはちょうちん横丁ならではです。</p>
                                <ul>
                                    <li><i class="fa-solid fa-circle-check" aria-hidden="true"></i>隣接する屋台村の料理をルームへ持ち込みOK</li>
                                    <li><i class="fa-solid fa-circle-check" aria-hidden="true"></i>食事からカラオケまで移動不要で楽しめる</li>
                                    <li><i class="fa-solid fa-circle-check" aria-hidden="true"></i>宴会・模合・誕生会・ママ会にも対応</li>
                                </ul>
                            </div>
                            <div class="yafuso_karaoke_food_photos_029 act blur delay_1">
                                <figure><img src="<?php echo $img; ?>/karaoke_generated_party.webp" alt="屋台料理を持ち込んでカラオケを楽しむイメージ" loading="lazy"></figure>
                                <figure><img src="<?php echo $img; ?>/shop_okada.webp" alt="屋台村のホルモン料理イメージ" loading="lazy"></figure>
                                <figure><img src="<?php echo $img; ?>/tokunobu_1.webp" alt="屋台ラーメンとくのぶのラーメン" loading="lazy"></figure>
                                <figure><img src="<?php echo $img; ?>/tebachu_1.webp" alt="手羽先居酒屋てばちゅ〜の手羽先" loading="lazy"></figure>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section>
                <div id="karaoke_price" class="yafuso_karaoke_price_029">
                    <div class="yafuso_single_029">
                        <div class="yafuso_karaoke_title_029 act blur">
                            <span aria-hidden="true"></span>
                            <h2>飲み放題（室料込み）</h2>
                            <p>「室料はいくら？飲み放題は別料金？」<br>そんな計算は一切不要。<br>
                                室料込みのシンプルな1本料金です。</p>
                        </div>
                        <div class="yafuso_karaoke_price_panel_029 act inup">
                            <div class="yafuso_karaoke_price_main_029">
                                <img class="yafuso_karaoke_beer_icon_029" src="<?php echo $img; ?>/karaoke_beer_mug_icon.webp" alt="ビールジョッキのイラスト" loading="eager">
                                <span>飲み放題1時間</span>
                                <strong>¥990</strong>
                                <small>税抜 / 室料込み</small>
                            </div>
                            <div class="yafuso_karaoke_price_table_029">
                                <p>火〜木・日 19:00〜翌2:00（L.O.1:00） ／ 金・土・祝前日 19:00〜翌3:00（L.O.2:00）</p>
                                <dl>
                                    <div>
                                        <dt>飲み放題1時間（室料込み）</dt>
                                        <dd>¥990</dd>
                                    </div>
                                    <div>
                                        <dt>高校生以下</dt>
                                        <dd>¥490</dd>
                                    </div>
                                    <div>
                                        <dt>飲食専用ルーム・カラオケなし</dt>
                                        <dd>¥860</dd>
                                    </div>
                                    <div>
                                        <dt>高校生以下・カラオケなし</dt>
                                        <dd>¥430</dd>
                                    </div>
                                </dl>
                                <em>室料込み飲み放題（1人）¥1,089（税込）／ ソフトドリンクのみは¥840（税込）</em>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section>
                <div id="karaoke_menu_section" class="yafuso_karaoke_menu_029">
                    <div class="yafuso_single_029">
                        <div class="yafuso_karaoke_title_029 act blur">
                            <span aria-hidden="true"></span>
                            <h2>飲み放題メニュー</h2>
                            <p>アルコールからソフトドリンクまで幅広く取り揃え。<br>
                                角ハイボールも飲み放題に含まれます。</p>
                        </div>
                        <figure class="yafuso_karaoke_drink_photo_029 act inup">
                            <img src="<?php echo $img; ?>/karaoke_generated_drinks.webp" alt="カラオケワールドももたろうの飲み放題ドリンクイメージ" loading="lazy">
                        </figure>
                        <div class="yafuso_karaoke_drink_grid_029 act inup delay_1">
                            <article>
                                <h3>ハイボール</h3>
                                <p>角ハイボール／角ハイ濃いめ／角ハイジンジャー／角ハイコーラ／泡盛ハイボール／黒ッキリボール</p>
                            </article>
                            <article>
                                <h3>サワー・果実酒</h3>
                                <p>こだわり酒場のレモンサワー／トマトサワー／梅サワー／巨峰サワー</p>
                            </article>
                            <article>
                                <h3>カクテル＆ワインサワー</h3>
                                <p>ピーチウーロン／ファジーネーブル／カシスオレンジ／モスコミュール／翠ジンソーダ／赤玉パンチサワー</p>
                            </article>
                            <article>
                                <h3>お茶割</h3>
                                <p>JJ／ウーロンハイ／緑茶ハイ</p>
                            </article>
                            <article>
                                <h3>うちな〜テイスト</h3>
                                <p>WATTAパッションサワー／WATTAシークヮーサーサワー／WATTAパインサワー</p>
                            </article>
                            <article>
                                <h3>ビールテイスト</h3>
                                <p>オリオン麦職人（生）／レッドアイ／シャンディーガフ</p>
                            </article>
                            <article>
                                <h3>ノンアルコール</h3>
                                <p>サントリーオールフリー／ノンアルカシオレ／ノンアルシャルドネ</p>
                            </article>
                            <article>
                                <h3>泡盛</h3>
                                <p>古里／久米仙ブラウン／菊之露ブラウン／残波（黒）／龍ゴールド</p>
                            </article>
                            <article>
                                <h3>ソフトドリンク</h3>
                                <p>コーラ／C.C.レモン／メロンソーダ／ジンジャーエール／オレンジジュース／ウーロン茶／コーヒー／ココア など</p>
                            </article>
                        </div>
                        <div class="yafuso_karaoke_menu_action_029 act inup delay_2">
                            <a href="#karaoke_drink_menu_modal" data-yafuso-karaoke-menu-open>飲み放題メニューを見る</a>
                        </div>
                        <div id="karaoke_drink_menu_modal" class="yafuso_karaoke_modal_029" data-yafuso-karaoke-modal aria-hidden="true" role="dialog" aria-modal="true" aria-label="飲み放題メニュー">
                            <a class="yafuso_karaoke_modal_backdrop_029" href="#karaoke_menu_section" data-yafuso-karaoke-menu-close aria-label="閉じる"></a>
                            <div class="yafuso_karaoke_modal_body_029">
                                <a class="yafuso_karaoke_modal_close_029" href="#karaoke_menu_section" data-yafuso-karaoke-menu-close aria-label="閉じる">×</a>
                                <img src="<?php echo $img; ?>/karaoke_momotaro10.webp" alt="カラオケワールドももたろうの飲み放題メニュー" loading="lazy">
                            </div>
                        </div>
                        <div class="yafuso_karaoke_food_menu_029 act blur">
                            <h3>フードメニュー</h3>
                            <dl>
                                <div>
                                    <dt>ポテトチップス</dt>
                                    <dd>¥330</dd>
                                </div>
                                <div>
                                    <dt>チョコレート盛り</dt>
                                    <dd>¥550</dd>
                                </div>
                            </dl>
                            <p>その他のお料理は、隣接する屋台村からご注文いただけます。</p>
                        </div>
                    </div>
                </div>
            </section>

            <section>
                <div class="yafuso_karaoke_rooms_029">
                    <div class="yafuso_single_029">
                        <div class="yafuso_karaoke_rooms_grid_029">
                            <div class="yafuso_karaoke_rooms_text_029 act inup">
                                <span class="yafuso_lower_lantern_icon_029" aria-hidden="true">歌</span>
                                <h2>大人数の宴会も、幹事さん安心。</h2>
                                <p>隣接する屋台村でたっぷり飲み食いして、そのままももたろうへ。<br>
                                    移動不要でスムーズに流れ込めるのが最大の魅力です。</p>
                            </div>
                            <div class="yafuso_karaoke_gallery_col_029 act blur">
                                <div class="gallery_thumbnail yafuso_karaoke_gallery_029" data-gallery-id="karaoke_momotaro_gallery">
                                    <div class="main-gallery">
                                        <div class="main-slide cover">
                                            <img src="<?php echo $img; ?>/karaoke_1.webp" alt="カラオケワールドももたろうの個室" class="active">
                                            <div class="overflow">
                                                <img src="<?php echo $img; ?>/karaoke_2.webp" alt="カラオケワールドももたろうのキッズスペース">
                                                <img src="<?php echo $img; ?>/karaoke_momotaro5.webp" alt="カラオケワールドももたろうの店内写真">
                                                <img src="<?php echo $img; ?>/karaoke_4.webp" alt="カラオケワールドももたろうの大人数向け個室">
                                                <img src="<?php echo $img; ?>/karaoke_momotaro1.webp" alt="カラオケワールドももたろうのダーツマシン">
                                                <img src="<?php echo $img; ?>/karaoke_momotaro2.webp" alt="カラオケワールドももたろうの店内写真">
                                                <img src="<?php echo $img; ?>/karaoke_momotaro3.webp" alt="カラオケワールドももたろうの店内写真">
                                                <img src="<?php echo $img; ?>/karaoke_momotaro4.webp" alt="カラオケワールドももたろうの店内写真">
                                                <img src="<?php echo $img; ?>/karaoke_momotaro6.webp" alt="カラオケワールドももたろうの店内写真">
                                                <img src="<?php echo $img; ?>/karaoke_momotaro7.webp" alt="カラオケワールドももたろうの店内写真">
                                                <img src="<?php echo $img; ?>/karaoke_momotaro8.webp" alt="カラオケワールドももたろうの店内写真">
                                                <img src="<?php echo $img; ?>/karaoke_momotaro9.webp" alt="カラオケワールドももたろうの店内写真">
                                                <img src="<?php echo $img; ?>/karaoke_momotaro10.webp" alt="カラオケワールドももたろうの店内写真">
                                                <img src="<?php echo $img; ?>/karaoke_momotaro11.webp" alt="カラオケワールドももたろうの店内写真">
                                                <img src="<?php echo $img; ?>/karaoke_momotaro12.webp" alt="カラオケワールドももたろうの店内写真">
                                                <img src="<?php echo $img; ?>/karaoke_momotaro13.webp" alt="カラオケワールドももたろうの店内写真">
                                                <img src="<?php echo $img; ?>/karaoke_momotaro14.webp" alt="カラオケワールドももたろうの店内写真">
                                                <img src="<?php echo $img; ?>/karaoke_momotaro15.webp" alt="カラオケワールドももたろうの店内写真">
                                                <img src="<?php echo $img; ?>/karaoke_momotaro17.webp" alt="カラオケワールドももたろうの店内写真">
                                                <img src="<?php echo $img; ?>/karaoke_momotaro18.webp" alt="カラオケワールドももたろうの店内写真">
                                                <img src="<?php echo $img; ?>/karaoke_momotaro19.webp" alt="カラオケワールドももたろうの店内写真">
                                                <img src="<?php echo $img; ?>/karaoke_momotaro20.webp" alt="カラオケワールドももたろうの店内写真">
                                                <img src="<?php echo $img; ?>/karaoke_momotaro21.webp" alt="カラオケワールドももたろうの店内写真">
                                                <img src="<?php echo $img; ?>/karaoke_momotaro22.webp" alt="カラオケワールドももたろうの店内写真">
                                                <img src="<?php echo $img; ?>/karaoke_momotaro23.webp" alt="カラオケワールドももたろうの店内写真">
                                                <img src="<?php echo $img; ?>/karaoke_momotaro24.webp" alt="カラオケワールドももたろうの店内写真">
                                                <img src="<?php echo $img; ?>/karaoke_momotaro26.webp" alt="カラオケワールドももたろうの店内写真">
                                            </div>
                                            <div class="gallery-nav">
                                                <button class="nav-button prev" type="button" aria-label="前の写真">&#10094;</button>
                                                <button class="nav-button next" type="button" aria-label="次の写真">&#10095;</button>
                                            </div>
                                        </div>

                                        <div class="thumbnail-gallery-container">
                                            <div class='space_1 space_sp1'></div>
                                            <div class="thumbnail-gallery">
                                                <div class="thumbnail active"><img src="<?php echo $img; ?>/karaoke_1.webp" alt="個室のサムネイル"></div>
                                                <div class="thumbnail"><img src="<?php echo $img; ?>/karaoke_2.webp" alt="キッズスペースのサムネイル"></div>
                                                <div class="thumbnail"><img src="<?php echo $img; ?>/karaoke_momotaro5.webp" alt="ももたろう店内写真のサムネイル"></div>
                                                <div class="thumbnail"><img src="<?php echo $img; ?>/karaoke_4.webp" alt="大人数向け個室のサムネイル"></div>
                                                <div class="thumbnail"><img src="<?php echo $img; ?>/karaoke_momotaro1.webp" alt="ダーツマシンのサムネイル"></div>
                                                <div class="thumbnail"><img src="<?php echo $img; ?>/karaoke_momotaro2.webp" alt="ももたろう店内写真のサムネイル"></div>
                                                <div class="thumbnail"><img src="<?php echo $img; ?>/karaoke_momotaro3.webp" alt="ももたろう店内写真のサムネイル"></div>
                                                <div class="thumbnail"><img src="<?php echo $img; ?>/karaoke_momotaro4.webp" alt="ももたろう店内写真のサムネイル"></div>
                                                <div class="thumbnail"><img src="<?php echo $img; ?>/karaoke_momotaro6.webp" alt="ももたろう店内写真のサムネイル"></div>
                                                <div class="thumbnail"><img src="<?php echo $img; ?>/karaoke_momotaro7.webp" alt="ももたろう店内写真のサムネイル"></div>
                                                <div class="thumbnail"><img src="<?php echo $img; ?>/karaoke_momotaro8.webp" alt="ももたろう店内写真のサムネイル"></div>
                                                <div class="thumbnail"><img src="<?php echo $img; ?>/karaoke_momotaro9.webp" alt="ももたろう店内写真のサムネイル"></div>
                                                <div class="thumbnail"><img src="<?php echo $img; ?>/karaoke_momotaro10.webp" alt="ももたろう店内写真のサムネイル"></div>
                                                <div class="thumbnail"><img src="<?php echo $img; ?>/karaoke_momotaro11.webp" alt="ももたろう店内写真のサムネイル"></div>
                                                <div class="thumbnail"><img src="<?php echo $img; ?>/karaoke_momotaro12.webp" alt="ももたろう店内写真のサムネイル"></div>
                                                <div class="thumbnail"><img src="<?php echo $img; ?>/karaoke_momotaro13.webp" alt="ももたろう店内写真のサムネイル"></div>
                                                <div class="thumbnail"><img src="<?php echo $img; ?>/karaoke_momotaro14.webp" alt="ももたろう店内写真のサムネイル"></div>
                                                <div class="thumbnail"><img src="<?php echo $img; ?>/karaoke_momotaro15.webp" alt="ももたろう店内写真のサムネイル"></div>
                                                <div class="thumbnail"><img src="<?php echo $img; ?>/karaoke_momotaro17.webp" alt="ももたろう店内写真のサムネイル"></div>
                                                <div class="thumbnail"><img src="<?php echo $img; ?>/karaoke_momotaro18.webp" alt="ももたろう店内写真のサムネイル"></div>
                                                <div class="thumbnail"><img src="<?php echo $img; ?>/karaoke_momotaro19.webp" alt="ももたろう店内写真のサムネイル"></div>
                                                <div class="thumbnail"><img src="<?php echo $img; ?>/karaoke_momotaro20.webp" alt="ももたろう店内写真のサムネイル"></div>
                                                <div class="thumbnail"><img src="<?php echo $img; ?>/karaoke_momotaro21.webp" alt="ももたろう店内写真のサムネイル"></div>
                                                <div class="thumbnail"><img src="<?php echo $img; ?>/karaoke_momotaro22.webp" alt="ももたろう店内写真のサムネイル"></div>
                                                <div class="thumbnail"><img src="<?php echo $img; ?>/karaoke_momotaro23.webp" alt="ももたろう店内写真のサムネイル"></div>
                                                <div class="thumbnail"><img src="<?php echo $img; ?>/karaoke_momotaro24.webp" alt="ももたろう店内写真のサムネイル"></div>
                                                <div class="thumbnail"><img src="<?php echo $img; ?>/karaoke_momotaro26.webp" alt="ももたろう店内写真のサムネイル"></div>
                                            </div>
                                            <div class="thumbnail-nav">
                                                <button class="thumb-button prev" type="button" aria-label="前のサムネイル">&#10094;</button>
                                                <button class="thumb-button next" type="button" aria-label="次のサムネイル">&#10095;</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="yafuso_karaoke_feature_grid_029 act inup delay_1">
                                <article><i class="fa-solid fa-bullseye" aria-hidden="true"></i>
                                    <h3>ダーツマシン設置</h3>
                                    <p>カラオケの合間に盛り上がれる。</p>
                                </article>
                                <article><i class="fa-solid fa-users" aria-hidden="true"></i>
                                    <h3>最大18名様</h3>
                                    <p>大人数にも対応できる個室あり。</p>
                                </article>
                                <article><i class="fa-solid fa-child-reaching" aria-hidden="true"></i>
                                    <h3>キッズスペース</h3>
                                    <p>小さなお子様連れも安心。</p>
                                </article>
                                <article><i class="fa-solid fa-tv" aria-hidden="true"></i>
                                    <h3>TVルームあり</h3>
                                    <p>スポーツ観戦にも最適。</p>
                                </article>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section>
                <div id="karaoke_reservation" class="yafuso_karaoke_reservation_029">
                    <div class="yafuso_single_029">
                        <div class="yafuso_karaoke_title_029 act blur">
                            <span aria-hidden="true"></span>
                            <h2>オンライン予約フォーム</h2>
                            <p>ご希望内容を送信してください。空き状況を確認後、店舗より折り返しご連絡いたします。</p>
                        </div>
                        <div class="yafuso_karaoke_reservation_grid_029">
                            <div class="yafuso_karaoke_reservation_note_029 act inup">
                                <h3>送信前のご確認</h3>
                                <ul>
                                    <li><i class="fa-solid fa-phone" aria-hidden="true"></i>予約は店舗からの折り返し連絡をもって確定となります。</li>
                                    <li><i class="fa-solid fa-users" aria-hidden="true"></i>18名様を超えるご利用は、お電話にてご相談ください。</li>
                                    <li><i class="fa-solid fa-clock" aria-hidden="true"></i>当日予約やお急ぎの場合は、電話予約が確実です。</li>
                                </ul>
                                <a href="tel:098-879-1055"><i class="fa-solid fa-phone" aria-hidden="true"></i>電話予約：098-879-1055</a>
                            </div>
                            <form class="yafuso_vendors_form_029 yafuso_karaoke_reservation_form_029 act blur delay_1" action="#karaoke_reservation" method="post" data-yafuso-required-form>
                                <input type="hidden" name="form_type" value="karaoke_reservation">
                                <div class="yafuso_form_trap_029" aria-hidden="true">
                                    <label>入力しないでください<input type="text" name="website" tabindex="-1" autocomplete="off"></label>
                                </div>
                                <?php if (!empty($karaoke_reservation_errors)) : ?>
                                    <div class="yafuso_form_error_029" role="alert">
                                        <strong>送信できませんでした</strong>
                                        <ul>
                                            <?php foreach ($karaoke_reservation_errors as $error) : ?>
                                                <li><?php echo yafuso_karaoke_form_h($error); ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>
                                <div class="yafuso_vendors_form_grid_029">
                                    <label>お名前 <b class="yafuso_required_badge_029">必須</b><input type="text" name="name" value="<?php echo yafuso_karaoke_form_h($karaoke_reservation_values['name']); ?>" autocomplete="name" required></label>
                                    <label>メールアドレス <b class="yafuso_required_badge_029">必須</b><input type="email" name="email" value="<?php echo yafuso_karaoke_form_h($karaoke_reservation_values['email']); ?>" autocomplete="email" required></label>
                                    <label>電話番号 <b class="yafuso_required_badge_029">必須</b><input type="tel" name="tel" value="<?php echo yafuso_karaoke_form_h($karaoke_reservation_values['tel']); ?>" autocomplete="tel" required></label>
                                    <label>ご利用人数 <b class="yafuso_required_badge_029">必須</b><input type="number" name="guests" value="<?php echo yafuso_karaoke_form_h($karaoke_reservation_values['guests']); ?>" min="1" max="18" inputmode="numeric" required></label>
                                    <label>予約希望日 <b class="yafuso_required_badge_029">必須</b><input type="date" name="reservation_date" value="<?php echo yafuso_karaoke_form_h($karaoke_reservation_values['reservation_date']); ?>" min="<?php echo yafuso_karaoke_form_h($karaoke_today); ?>" required></label>
                                    <label>予約希望時間 <b class="yafuso_required_badge_029">必須</b><select name="reservation_time" required>
                                            <option value="" <?php echo $karaoke_reservation_values['reservation_time'] === '' ? 'selected' : ''; ?> disabled>選択してください</option>
                                            <?php foreach ($karaoke_reservation_time_options as $time_option) : ?>
                                                <option value="<?php echo yafuso_karaoke_form_h($time_option); ?>" <?php echo $karaoke_reservation_values['reservation_time'] === $time_option ? 'selected' : ''; ?>><?php echo yafuso_karaoke_form_h($time_option); ?></option>
                                            <?php endforeach; ?>
                                        </select></label>
                                    <label>ご利用内容 <b class="yafuso_required_badge_029">必須</b><select name="plan" required>
                                            <option value="" <?php echo $karaoke_reservation_values['plan'] === '' ? 'selected' : ''; ?> disabled>選択してください</option>
                                            <?php foreach ($karaoke_reservation_plan_options as $plan_option) : ?>
                                                <option value="<?php echo yafuso_karaoke_form_h($plan_option); ?>" <?php echo $karaoke_reservation_values['plan'] === $plan_option ? 'selected' : ''; ?>><?php echo yafuso_karaoke_form_h($plan_option); ?></option>
                                            <?php endforeach; ?>
                                        </select></label>
                                    <label>ご希望のお部屋 <select name="room_request">
                                            <?php foreach ($karaoke_reservation_room_options as $room_option) : ?>
                                                <option value="<?php echo yafuso_karaoke_form_h($room_option); ?>" <?php echo $karaoke_reservation_values['room_request'] === $room_option ? 'selected' : ''; ?>><?php echo yafuso_karaoke_form_h($room_option); ?></option>
                                            <?php endforeach; ?>
                                        </select></label>
                                </div>
                                <label>ご要望・備考<textarea name="message" rows="5" placeholder="お子様連れ、誕生日利用、屋台村からの持ち込み予定などがあればご記入ください。"><?php echo yafuso_karaoke_form_h($karaoke_reservation_values['message']); ?></textarea></label>
                                <label class="yafuso_vendors_agree_029">
                                    <input type="checkbox" name="agree" value="1" required <?php echo (string)($_POST['agree'] ?? '') === '1' ? 'checked' : ''; ?>>
                                    <span>店舗からの折り返し連絡後に予約確定となることに同意します <b class="yafuso_required_badge_029">必須</b></span>
                                </label>
                                <button type="submit">予約内容を送信する</button>
                                <a href="tel:098-879-1055" class="yafuso_vendors_phone_029"><i class="fa-solid fa-phone" aria-hidden="true"></i>電話で予約する</a>
                            </form>
                        </div>
                    </div>
                </div>
            </section>

            <section>
                <div class="yafuso_karaoke_info_029">
                    <div class="yafuso_single_029">
                        <div class="yafuso_karaoke_info_grid_029">
                            <div class="yafuso_karaoke_info_card_029 act inup">
                                <h2>席・設備について</h2>
                                <dl>
                                    <div>
                                        <dt>総席数</dt>
                                        <dd>100席</dd>
                                    </div>
                                    <div>
                                        <dt>座敷</dt>
                                        <dd>座敷席・座椅子あり</dd>
                                    </div>
                                    <div>
                                        <dt>宴会最大人数</dt>
                                        <dd>18名様（着席時）</dd>
                                    </div>
                                    <div>
                                        <dt>個室</dt>
                                        <dd>テーブル個室（4〜8名 / 4〜10名 / 8〜18名）、座敷個室（4〜8名）</dd>
                                    </div>
                                    <div>
                                        <dt>禁煙・喫煙</dt>
                                        <dd>店内全面禁煙（店外に喫煙スペースあり）</dd>
                                    </div>
                                    <div>
                                        <dt>バリアフリー</dt>
                                        <dd>車いすで入店可・トイレ利用可</dd>
                                    </div>
                                    <div>
                                        <dt>お子様連れ</dt>
                                        <dd>OK（キッズルーム完備・お子様用椅子・ベビーカー入店可）</dd>
                                    </div>
                                    <div>
                                        <dt>Wi-Fi</dt>
                                        <dd>利用可（au・ソフトバンク Wi-Fi）</dd>
                                    </div>
                                    <div>
                                        <dt>トイレ</dt>
                                        <dd>洋式（温水洗浄便座）・ハンドソープ・ハンドドライヤー完備</dd>
                                    </div>
                                </dl>
                            </div>
                            <div class="yafuso_karaoke_contact_card_029 act blur delay_1">
                                <img src="<?php echo $img; ?>/momotarou.webp" alt="カラオケワールドももたろうのロゴ" loading="lazy">
                                <h2>ご予約・お問い合わせ</h2>
                                <p>ご予約はお電話またはオンライン予約フォームから承ります。<br>
                                    お急ぎの場合はお電話ください。</p>
                                <dl>
                                    <div>
                                        <dt>電話番号</dt>
                                        <dd><a href="tel:098-879-1055">098-879-1055</a></dd>
                                    </div>
                                    <div>
                                        <dt>営業時間</dt>
                                        <dd>火〜木・日 19:00〜翌2:00 / 金・土・祝前日 19:00〜翌3:00</dd>
                                    </div>
                                    <div>
                                        <dt>定休日</dt>
                                        <dd>毎週月曜日</dd>
                                    </div>
                                    <div>
                                        <dt>Instagram</dt>
                                        <dd><a href="https://www.instagram.com/karaoke.momotaro/" target="_blank" rel="noopener noreferrer">@karaoke.momotaro</a></dd>
                                    </div>
                                </dl>
                                <a href="#karaoke_reservation">オンライン予約フォームへ</a>
                                <a href="tel:098-879-1055" class="yafuso_karaoke_phone_button_029">電話予約：098-879-1055</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <div class="yafuso_lower_lantern_footer_029" aria-hidden="true"></div>
        </div>
    </div>
    <!-- カラオケワールドももたろう karaoke.php -->
</main>

<?php include_once './footer.php'; ?>
