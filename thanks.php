<?php
require_once './common.php';
yafuso_session_start();

$requested_thanks_type = $_GET['type'] ?? '';
$thanks_type = is_scalar($requested_thanks_type) ? (string)$requested_thanks_type : '';
$thanks_redirects = [
    'karaoke_reservation' => '/karaoke.php#karaoke_reservation',
    'vendor_inquiry' => '/vendors.php#vendors_contact_form',
];
if (
    !isset($thanks_redirects[$thanks_type])
    || empty($_SESSION['yafuso_mailform_success'][$thanks_type])
) {
    $redirect = $thanks_redirects[$thanks_type] ?? '/';
    header('Location: ' . $redirect, true, 303);
    exit;
}
unset($_SESSION['yafuso_mailform_success'][$thanks_type]);
$visitor_form_submit_token = bin2hex(random_bytes(32));
$_SESSION['yafuso_visitor_form_submit_token'] = $visitor_form_submit_token;

$is_karaoke_reservation_thanks = $thanks_type === 'karaoke_reservation';
$is_vendor_inquiry_thanks = $thanks_type === 'vendor_inquiry';
$page_title = $is_karaoke_reservation_thanks ? '予約受付完了' : 'お問い合わせ受付完了';
$page_meta_title = $page_title . '｜やふそ屋台村 ちょうちん横丁';
$page_meta_description = 'フォームの送信を受け付けました。内容を確認のうえ、担当者より折り返しご連絡いたします。';
$use_yafuso_layout = true;
?>
<?php include_once './header.php'; ?>

<div class='space_3 space_sp2'></div>
<section>
    <div class="single">
        <div class="mbox border bc_aaa size_1">
            <h3 class="tcenter base_color fs_30 fs_sp20">
                <span><?php echo $is_karaoke_reservation_thanks ? '予約リクエストを送信しました' : '送信が完了しました'; ?></span>
            </h3>
            <?php if ($is_karaoke_reservation_thanks) : ?>
                <p class="b_m10 tcenter t_m10">オンライン予約をご利用いただきありがとうございます。<br>
                    空き状況を確認のうえ、店舗より折り返しご連絡いたします。<br>
                    予約は折り返し連絡をもって確定となります。</p>
            <?php elseif ($is_vendor_inquiry_thanks) : ?>
                <p class="b_m10 tcenter t_m10">出店のお申込み・お問い合わせをいただきありがとうございます。<br>
                    内容を確認のうえ、担当者より折り返しご連絡いたします。</p>
            <?php else : ?>
                <p class="b_m10 tcenter t_m10">お問い合わせ頂きありがとうございます。<br>
                    改めてご連絡をさせていただきますので、<br>
                    今しばらくお待ちくださいますようお願いいたします。</p>
            <?php endif; ?>
            <button class="btn_mini center radius">
                <a href="./">ホームへ戻る</a>
            </button>
        </div>
    </div>
</section>

<?php
$safe_thanks_type = in_array($thanks_type, ['karaoke_reservation', 'vendor_inquiry'], true) ? $thanks_type : 'general';
$page_script = "
<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js' defer></script>
<script>
(() => {
    const formName = " . json_encode($safe_thanks_type, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . ";
    const eventKey = 'yafuso_ga4_form_submit_' + formName;
    if (formName !== 'general' && !sessionStorage.getItem(eventKey) && typeof gtag === 'function') {
        sessionStorage.setItem(eventKey, '1');
        gtag('event', 'form_submit', { form_name: formName });
    }
})();
</script>
";
?>
<?php include_once './footer.php'; ?>
