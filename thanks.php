<?php
$is_karaoke_reservation_thanks = (string)($_GET['type'] ?? '') === 'karaoke_reservation';
$page_title = $is_karaoke_reservation_thanks ? "予約受付完了" : "送信完了";
$page_title_eng = $is_karaoke_reservation_thanks ? "Reservation received" : "Send completely";
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

<?php $page_script = "
<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js' defer></script>

"; ?>
<?php include_once './footer.php'; ?>
