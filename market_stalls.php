<?php
require_once './common.php';

$page_title = "屋台のご紹介";
$page_meta_title = "屋台のご紹介｜やふそ屋台村 ちょうちん横丁";
$page_meta_description = "やふそ屋台村 ちょうちん横丁に集まるカラオケ、ホルモン、ラーメン、沖縄料理、手羽先の各店舗をご紹介します。";
$page_meta_image = $img . "/hero_scene.webp";
$page_style = "";
$use_yafuso_layout = true;
$page_script = <<<'HTML'
<script src="js/gallery_thumbnail.js" defer></script>
HTML;
?>
<?php include_once './header.php'; ?>

<main>
    <!-- 屋台のご紹介 market_stalls.php -->
    <div class='overflow'>
        <div id="market_stalls_page" class="yafuso_market_029">
            <section>
                <div class="yafuso_market_hero_029">
                    <div class="yafuso_market_hero_photo_029" aria-hidden="true">
                        <img decoding="async" src="<?php echo $img; ?>/hero_scene.webp" alt="やふそ屋台村ちょうちん横丁の屋台エリア" loading="eager" fetchpriority="high">
                    </div>
                    <div class="yafuso_single_029">
                        <div class="yafuso_market_hero_inner_029">
                            <p class="yafuso_market_kicker_029">MARKET STALLS</p>
                            <h2>屋台のご紹介</h2>
                            <p class="yafuso_market_copy_029">個性あふれる屋台が、<br>あなたの一日を彩る</p>
                            <p class="yafuso_market_intro_029">ホルモン焼き、ラーメン、沖縄料理、手羽先、カラオケ。ジャンルも雰囲気も異なる店が一か所に集まっているから、「今日は何にしようか」と迷う楽しさがあります。</p>
                            <div class="yafuso_market_hero_actions_029">
                                <a href="#market_stalls_list"><i class="fa-solid fa-store" aria-hidden="true"></i>店舗を見る</a>
                                <a href="#market_stalls_scene"><i class="fa-solid fa-route" aria-hidden="true"></i>使い方を見る</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section>
                <div id="market_stalls_scene" class="yafuso_market_scene_029">
                    <div class="yafuso_single_029">
                        <div class="yafuso_market_scene_head_029">
                            <span aria-hidden="true"></span>
                            <h2>気分に合わせて、<br class="sponly">横丁をハシゴ</h2>
                        </div>
                        <div class="yafuso_market_scene_grid_029">
                            <div class="yafuso_market_scene_item_029">
                                <i class="fa-solid fa-beer-mug-empty" aria-hidden="true"></i>
                                <span>一軒でじっくり</span>
                            </div>
                            <div class="yafuso_market_scene_item_029">
                                <i class="fa-solid fa-utensils" aria-hidden="true"></i>
                                <span>料理で選ぶ</span>
                            </div>
                            <div class="yafuso_market_scene_item_029">
                                <i class="fa-solid fa-people-group" aria-hidden="true"></i>
                                <span>会社飲み会</span>
                            </div>
                            <div class="yafuso_market_scene_item_029">
                                <i class="fa-solid fa-shoe-prints" aria-hidden="true"></i>
                                <span>気分でハシゴ</span>
                            </div>
                            <div class="yafuso_market_scene_item_029">
                                <i class="fa-solid fa-microphone-lines" aria-hidden="true"></i>
                                <span>カラオケ隣接</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section>
                <div id="market_stalls_list" class="yafuso_market_list_029">
                    <div class="yafuso_single_029">
                        <div class="yafuso_market_title_029">
                            <span aria-hidden="true"></span>
                            <h2>屋台のご紹介</h2>
                            <p>料理、雰囲気、過ごし方で選べる、ちょうちん横丁の個性豊かなラインナップです。</p>
                        </div>

                        <article id="market_stalls_momotaro" class="yafuso_market_shop_029">
                            <div class="yafuso_market_shop_gallery_029">
                                <div class="gallery_thumbnail yafuso_market_gallery_029" data-gallery-id="market_momotaro_gallery">
                                    <div class="main-gallery">
                                        <div class="main-slide cover">
                                            <img decoding="async" loading="lazy" src="<?php echo $img; ?>/karaoke_momotarou.webp" alt="カラオケワールドももたろうの個室" class="active">
                                            <div class="overflow">
                                                <img decoding="async" loading="lazy" src="<?php echo $img; ?>/karaoke_momotaro5.webp" alt="カラオケワールドももたろうの店内写真">
                                                <img decoding="async" loading="lazy" src="<?php echo $img; ?>/karaoke_momotaro1.webp" alt="カラオケワールドももたろうのダーツマシン">
                                                <img decoding="async" loading="lazy" src="<?php echo $img; ?>/karaoke_momotaro2.webp" alt="カラオケワールドももたろうの店内写真">
                                                <img decoding="async" loading="lazy" src="<?php echo $img; ?>/karaoke_momotaro3.webp" alt="カラオケワールドももたろうの店内写真">
                                                <img decoding="async" loading="lazy" src="<?php echo $img; ?>/karaoke_momotaro4.webp" alt="カラオケワールドももたろうの店内写真">
                                                <img decoding="async" loading="lazy" src="<?php echo $img; ?>/karaoke_momotaro6.webp" alt="カラオケワールドももたろうの店内写真">
                                                <img decoding="async" loading="lazy" src="<?php echo $img; ?>/karaoke_momotaro7.webp" alt="カラオケワールドももたろうの店内写真">
                                                <img decoding="async" loading="lazy" src="<?php echo $img; ?>/karaoke_momotaro8.webp" alt="カラオケワールドももたろうの店内写真">
                                                <img decoding="async" loading="lazy" src="<?php echo $img; ?>/karaoke_momotaro9.webp" alt="カラオケワールドももたろうの店内写真">
                                                <img decoding="async" loading="lazy" src="<?php echo $img; ?>/karaoke_momotaro10.webp" alt="カラオケワールドももたろうの店内写真">
                                                <img decoding="async" loading="lazy" src="<?php echo $img; ?>/karaoke_momotaro11.webp" alt="カラオケワールドももたろうの店内写真">
                                                <img decoding="async" loading="lazy" src="<?php echo $img; ?>/karaoke_momotaro12.webp" alt="カラオケワールドももたろうの店内写真">
                                                <img decoding="async" loading="lazy" src="<?php echo $img; ?>/karaoke_momotaro13.webp" alt="カラオケワールドももたろうの店内写真">
                                                <img decoding="async" loading="lazy" src="<?php echo $img; ?>/karaoke_momotaro14.webp" alt="カラオケワールドももたろうの店内写真">
                                                <img decoding="async" loading="lazy" src="<?php echo $img; ?>/karaoke_momotaro15.webp" alt="カラオケワールドももたろうの店内写真">
                                                <img decoding="async" loading="lazy" src="<?php echo $img; ?>/karaoke_momotaro17.webp" alt="カラオケワールドももたろうの店内写真">
                                                <img decoding="async" loading="lazy" src="<?php echo $img; ?>/karaoke_momotaro18.webp" alt="カラオケワールドももたろうの店内写真">
                                                <img decoding="async" loading="lazy" src="<?php echo $img; ?>/karaoke_momotaro19.webp" alt="カラオケワールドももたろうの店内写真">
                                                <img decoding="async" loading="lazy" src="<?php echo $img; ?>/karaoke_momotaro20.webp" alt="カラオケワールドももたろうの店内写真">
                                                <img decoding="async" loading="lazy" src="<?php echo $img; ?>/karaoke_momotaro21.webp" alt="カラオケワールドももたろうの店内写真">
                                                <img decoding="async" loading="lazy" src="<?php echo $img; ?>/karaoke_momotaro22.webp" alt="カラオケワールドももたろうの店内写真">
                                                <img decoding="async" loading="lazy" src="<?php echo $img; ?>/karaoke_momotaro23.webp" alt="カラオケワールドももたろうの店内写真">
                                                <img decoding="async" loading="lazy" src="<?php echo $img; ?>/karaoke_momotaro24.webp" alt="カラオケワールドももたろうの店内写真">
                                                <img decoding="async" loading="lazy" src="<?php echo $img; ?>/karaoke_momotaro26.webp" alt="カラオケワールドももたろうの店内写真">
                                            </div>
                                            <div class="gallery-nav">
                                                <button class="nav-button prev" type="button" aria-label="前の写真">&#10094;</button>
                                                <button class="nav-button next" type="button" aria-label="次の写真">&#10095;</button>
                                            </div>
                                        </div>

                                        <div class="thumbnail-gallery-container">
                                            <div class='space_1 space_sp1'></div>
                                            <div class="thumbnail-gallery">
                                                <div class="thumbnail active">
                                                    <img decoding="async" loading="lazy" src="<?php echo $img; ?>/karaoke_momotarou.webp" alt="カラオケ個室のサムネイル">
                                                </div>
                                                <div class="thumbnail">
                                                    <img decoding="async" loading="lazy" src="<?php echo $img; ?>/karaoke_momotaro5.webp" alt="ももたろう店内写真のサムネイル">
                                                </div>
                                                <div class="thumbnail">
                                                    <img decoding="async" loading="lazy" src="<?php echo $img; ?>/karaoke_momotaro1.webp" alt="ダーツマシンのサムネイル">
                                                </div>
                                                <div class="thumbnail">
                                                    <img decoding="async" loading="lazy" src="<?php echo $img; ?>/karaoke_momotaro2.webp" alt="ももたろう店内写真のサムネイル">
                                                </div>
                                                <div class="thumbnail">
                                                    <img decoding="async" loading="lazy" src="<?php echo $img; ?>/karaoke_momotaro3.webp" alt="ももたろう店内写真のサムネイル">
                                                </div>
                                                <div class="thumbnail">
                                                    <img decoding="async" loading="lazy" src="<?php echo $img; ?>/karaoke_momotaro4.webp" alt="ももたろう店内写真のサムネイル">
                                                </div>
                                                <div class="thumbnail">
                                                    <img decoding="async" loading="lazy" src="<?php echo $img; ?>/karaoke_momotaro6.webp" alt="ももたろう店内写真のサムネイル">
                                                </div>
                                                <div class="thumbnail">
                                                    <img decoding="async" loading="lazy" src="<?php echo $img; ?>/karaoke_momotaro7.webp" alt="ももたろう店内写真のサムネイル">
                                                </div>
                                                <div class="thumbnail">
                                                    <img decoding="async" loading="lazy" src="<?php echo $img; ?>/karaoke_momotaro8.webp" alt="ももたろう店内写真のサムネイル">
                                                </div>
                                                <div class="thumbnail">
                                                    <img decoding="async" loading="lazy" src="<?php echo $img; ?>/karaoke_momotaro9.webp" alt="ももたろう店内写真のサムネイル">
                                                </div>
                                                <div class="thumbnail">
                                                    <img decoding="async" loading="lazy" src="<?php echo $img; ?>/karaoke_momotaro10.webp" alt="ももたろう店内写真のサムネイル">
                                                </div>
                                                <div class="thumbnail">
                                                    <img decoding="async" loading="lazy" src="<?php echo $img; ?>/karaoke_momotaro11.webp" alt="ももたろう店内写真のサムネイル">
                                                </div>
                                                <div class="thumbnail">
                                                    <img decoding="async" loading="lazy" src="<?php echo $img; ?>/karaoke_momotaro12.webp" alt="ももたろう店内写真のサムネイル">
                                                </div>
                                                <div class="thumbnail">
                                                    <img decoding="async" loading="lazy" src="<?php echo $img; ?>/karaoke_momotaro13.webp" alt="ももたろう店内写真のサムネイル">
                                                </div>
                                                <div class="thumbnail">
                                                    <img decoding="async" loading="lazy" src="<?php echo $img; ?>/karaoke_momotaro14.webp" alt="ももたろう店内写真のサムネイル">
                                                </div>
                                                <div class="thumbnail">
                                                    <img decoding="async" loading="lazy" src="<?php echo $img; ?>/karaoke_momotaro15.webp" alt="ももたろう店内写真のサムネイル">
                                                </div>
                                                <div class="thumbnail">
                                                    <img decoding="async" loading="lazy" src="<?php echo $img; ?>/karaoke_momotaro17.webp" alt="ももたろう店内写真のサムネイル">
                                                </div>
                                                <div class="thumbnail">
                                                    <img decoding="async" loading="lazy" src="<?php echo $img; ?>/karaoke_momotaro18.webp" alt="ももたろう店内写真のサムネイル">
                                                </div>
                                                <div class="thumbnail">
                                                    <img decoding="async" loading="lazy" src="<?php echo $img; ?>/karaoke_momotaro19.webp" alt="ももたろう店内写真のサムネイル">
                                                </div>
                                                <div class="thumbnail">
                                                    <img decoding="async" loading="lazy" src="<?php echo $img; ?>/karaoke_momotaro20.webp" alt="ももたろう店内写真のサムネイル">
                                                </div>
                                                <div class="thumbnail">
                                                    <img decoding="async" loading="lazy" src="<?php echo $img; ?>/karaoke_momotaro21.webp" alt="ももたろう店内写真のサムネイル">
                                                </div>
                                                <div class="thumbnail">
                                                    <img decoding="async" loading="lazy" src="<?php echo $img; ?>/karaoke_momotaro22.webp" alt="ももたろう店内写真のサムネイル">
                                                </div>
                                                <div class="thumbnail">
                                                    <img decoding="async" loading="lazy" src="<?php echo $img; ?>/karaoke_momotaro23.webp" alt="ももたろう店内写真のサムネイル">
                                                </div>
                                                <div class="thumbnail">
                                                    <img decoding="async" loading="lazy" src="<?php echo $img; ?>/karaoke_momotaro24.webp" alt="ももたろう店内写真のサムネイル">
                                                </div>
                                                <div class="thumbnail">
                                                    <img decoding="async" loading="lazy" src="<?php echo $img; ?>/karaoke_momotaro26.webp" alt="ももたろう店内写真のサムネイル">
                                                </div>
                                            </div>
                                            <div class="thumbnail-nav">
                                                <button class="thumb-button prev" type="button" aria-label="前のサムネイル">&#10094;</button>
                                                <button class="thumb-button next" type="button" aria-label="次のサムネイル">&#10095;</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="yafuso_market_shop_body_029">
                                <img decoding="async" class="yafuso_market_sns_icon_029" src="<?php echo $img; ?>/sns_momotaro.webp" alt="ももたろうのSNSアイコン" loading="lazy">
                                <h3>カラオケワールド<br class="sponly"> <span class="pink">ももたろう</span></h3>
                                <p class="yafuso_market_lead_029">屋台村と隣接する、ここにしかない珍しいスタイルのカラオケ店。<br>
                                屋台村の種類豊富な料理をカラオケルームから注文できます。</p>
                                <ul class="yafuso_market_point_list_029">
                                    <li><i class="fa-solid fa-circle-check" aria-hidden="true"></i>食べながら歌いながら、最高の時間を過ごせる</li>
                                    <li><i class="fa-solid fa-circle-check" aria-hidden="true"></i>会社の飲み会や家族でのお出かけにもぴったり</li>
                                    <li><i class="fa-solid fa-circle-check" aria-hidden="true"></i>平日20時までの来店で飲み放題1時間無料</li>
                                    <li><i class="fa-solid fa-circle-check" aria-hidden="true"></i>ダーツマシンも設置</li>
                                </ul>
                            </div>
                            <div class="yafuso_market_meta_029">
                                <dl>
                                    <div>
                                        <dt><i class="fa-regular fa-clock" aria-hidden="true"></i>営業時間</dt>
                                        <dd>19:00 〜 2:00</dd>
                                    </div>
                                    <div>
                                        <dt><i class="fa-regular fa-calendar" aria-hidden="true"></i>定休日</dt>
                                        <dd>毎週月曜日</dd>
                                    </div>
                                    <div>
                                        <dt><i class="fa-solid fa-phone" aria-hidden="true"></i>予約</dt>
                                        <dd><a href="tel:098-879-1055">098-879-1055</a></dd>
                                    </div>
                                    <div>
                                        <dt><i class="fa-brands fa-instagram" aria-hidden="true"></i>Instagram</dt>
                                        <dd><a href="https://www.instagram.com/karaoke.momotaro/" target="_blank" rel="noopener noreferrer">@karaoke.momotaro</a></dd>
                                    </div>
                                </dl>
                                <a class="yafuso_market_button_029" href="tel:098-879-1055">電話で予約する</a>
                            </div>
                        </article>

                        <article id="market_stalls_okada" class="yafuso_market_shop_029">
                            <div class="yafuso_market_shop_gallery_029">
                                <div class="gallery_thumbnail yafuso_market_gallery_029" data-gallery-id="market_okada_gallery">
                                    <div class="main-gallery">
                                        <div class="main-slide cover">
                                            <img decoding="async" loading="lazy" src="<?php echo $img; ?>/okada_0.webp" alt="西成ホルモンおか田の写真01" class="active">
                                            <div class="overflow">
                                                <img decoding="async" loading="lazy" src="<?php echo $img; ?>/okada_1.webp" alt="西成ホルモンおか田の写真02">
                                                <img decoding="async" loading="lazy" src="<?php echo $img; ?>/okada_3.webp" alt="西成ホルモンおか田の写真03">
                                                <img decoding="async" loading="lazy" src="<?php echo $img; ?>/okada_4.webp" alt="西成ホルモンおか田の写真04">
                                                <img decoding="async" loading="lazy" src="<?php echo $img; ?>/okada_5.webp" alt="西成ホルモンおか田の写真05">
                                                <img decoding="async" loading="lazy" src="<?php echo $img; ?>/okada_6.webp" alt="西成ホルモンおか田の写真06">
                                                <img decoding="async" loading="lazy" src="<?php echo $img; ?>/okada_7.webp" alt="西成ホルモンおか田の写真07">
                                                <img decoding="async" loading="lazy" src="<?php echo $img; ?>/okada_8.webp" alt="西成ホルモンおか田の写真08">
                                                <img decoding="async" loading="lazy" src="<?php echo $img; ?>/okada_9.webp" alt="西成ホルモンおか田の写真09">
                                                <img decoding="async" loading="lazy" src="<?php echo $img; ?>/okada_10.webp" alt="西成ホルモンおか田の写真10">
                                                <img decoding="async" loading="lazy" src="<?php echo $img; ?>/okada_11.webp" alt="西成ホルモンおか田の写真11">
                                                <img decoding="async" loading="lazy" src="<?php echo $img; ?>/okada_12.webp" alt="西成ホルモンおか田の写真12">
                                                <img decoding="async" loading="lazy" src="<?php echo $img; ?>/okada_13.webp" alt="西成ホルモンおか田の写真13">
                                                <img decoding="async" loading="lazy" src="<?php echo $img; ?>/okada_14.webp" alt="西成ホルモンおか田の写真14">
                                                <img decoding="async" loading="lazy" src="<?php echo $img; ?>/okada_15.webp" alt="西成ホルモンおか田の写真15">
                                                <img decoding="async" loading="lazy" src="<?php echo $img; ?>/okada_16.webp" alt="西成ホルモンおか田の写真16">
                                                <img decoding="async" loading="lazy" src="<?php echo $img; ?>/okada_17.webp" alt="西成ホルモンおか田の写真17">
                                            </div>
                                            <div class="gallery-nav">
                                                <button class="nav-button prev" type="button" aria-label="前の写真">&#10094;</button>
                                                <button class="nav-button next" type="button" aria-label="次の写真">&#10095;</button>
                                            </div>
                                        </div>

                                        <div class="thumbnail-gallery-container">
                                            <div class='space_1 space_sp1'></div>
                                            <div class="thumbnail-gallery">
                                                <div class="thumbnail active">
                                                    <img decoding="async" loading="lazy" src="<?php echo $img; ?>/okada_0.webp" alt="おか田写真01のサムネイル">
                                                </div>
                                                <div class="thumbnail">
                                                    <img decoding="async" loading="lazy" src="<?php echo $img; ?>/okada_1.webp" alt="おか田写真02のサムネイル">
                                                </div>
                                                <div class="thumbnail">
                                                    <img decoding="async" loading="lazy" src="<?php echo $img; ?>/okada_3.webp" alt="おか田写真03のサムネイル">
                                                </div>
                                                <div class="thumbnail">
                                                    <img decoding="async" loading="lazy" src="<?php echo $img; ?>/okada_4.webp" alt="おか田写真04のサムネイル">
                                                </div>
                                                <div class="thumbnail">
                                                    <img decoding="async" loading="lazy" src="<?php echo $img; ?>/okada_5.webp" alt="おか田写真05のサムネイル">
                                                </div>
                                                <div class="thumbnail">
                                                    <img decoding="async" loading="lazy" src="<?php echo $img; ?>/okada_6.webp" alt="おか田写真06のサムネイル">
                                                </div>
                                                <div class="thumbnail">
                                                    <img decoding="async" loading="lazy" src="<?php echo $img; ?>/okada_7.webp" alt="おか田写真07のサムネイル">
                                                </div>
                                                <div class="thumbnail">
                                                    <img decoding="async" loading="lazy" src="<?php echo $img; ?>/okada_8.webp" alt="おか田写真08のサムネイル">
                                                </div>
                                                <div class="thumbnail">
                                                    <img decoding="async" loading="lazy" src="<?php echo $img; ?>/okada_9.webp" alt="おか田写真09のサムネイル">
                                                </div>
                                                <div class="thumbnail">
                                                    <img decoding="async" loading="lazy" src="<?php echo $img; ?>/okada_10.webp" alt="おか田写真10のサムネイル">
                                                </div>
                                                <div class="thumbnail">
                                                    <img decoding="async" loading="lazy" src="<?php echo $img; ?>/okada_11.webp" alt="おか田写真11のサムネイル">
                                                </div>
                                                <div class="thumbnail">
                                                    <img decoding="async" loading="lazy" src="<?php echo $img; ?>/okada_12.webp" alt="おか田写真12のサムネイル">
                                                </div>
                                                <div class="thumbnail">
                                                    <img decoding="async" loading="lazy" src="<?php echo $img; ?>/okada_13.webp" alt="おか田写真13のサムネイル">
                                                </div>
                                                <div class="thumbnail">
                                                    <img decoding="async" loading="lazy" src="<?php echo $img; ?>/okada_14.webp" alt="おか田写真14のサムネイル">
                                                </div>
                                                <div class="thumbnail">
                                                    <img decoding="async" loading="lazy" src="<?php echo $img; ?>/okada_15.webp" alt="おか田写真15のサムネイル">
                                                </div>
                                                <div class="thumbnail">
                                                    <img decoding="async" loading="lazy" src="<?php echo $img; ?>/okada_16.webp" alt="おか田写真16のサムネイル">
                                                </div>
                                                <div class="thumbnail">
                                                    <img decoding="async" loading="lazy" src="<?php echo $img; ?>/okada_17.webp" alt="おか田写真17のサムネイル">
                                                </div>
                                            </div>
                                            <div class="thumbnail-nav">
                                                <button class="thumb-button prev" type="button" aria-label="前のサムネイル">&#10094;</button>
                                                <button class="thumb-button next" type="button" aria-label="次のサムネイル">&#10095;</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="yafuso_market_shop_body_029">
                                <img decoding="async" class="yafuso_market_sns_icon_029" src="<?php echo $img; ?>/sns_okada.webp" alt="おか田のSNSアイコン" loading="lazy">
                                <h3>西成ホルモン <span class="red">おか田</span></h3>
                                <p class="yafuso_market_lead_029">大阪・西成で愛され続けてきたホルモン焼きの文化を沖縄の地で再現。<br>
                                昔ながらの大衆酒場の雰囲気をそのまま楽しめます。</p>
                                <ul class="yafuso_market_point_list_029">
                                    <li><i class="fa-solid fa-circle-check" aria-hidden="true"></i>一番人気はもつ煮込み</li>
                                    <li><i class="fa-solid fa-circle-check" aria-hidden="true"></i>せんべろ・飲み放題でアサヒスーパードライあり</li>
                                    <li><i class="fa-solid fa-circle-check" aria-hidden="true"></i>テーブルチャージなし</li>
                                    <li><i class="fa-solid fa-circle-check" aria-hidden="true"></i>団体様の予約も相談OK</li>
                                </ul>
                            </div>
                            <div class="yafuso_market_meta_029">
                                <dl>
                                    <div>
                                        <dt><i class="fa-solid fa-fire" aria-hidden="true"></i>名物</dt>
                                        <dd>もつ煮込み</dd>
                                    </div>
                                    <div>
                                        <dt><i class="fa-regular fa-clock" aria-hidden="true"></i>営業時間</dt>
                                        <dd>18:00 〜 2:00</dd>
                                    </div>
                                    <div>
                                        <dt><i class="fa-regular fa-calendar" aria-hidden="true"></i>定休日</dt>
                                        <dd>毎週月曜日</dd>
                                    </div>
                                    <div>
                                        <dt><i class="fa-brands fa-instagram" aria-hidden="true"></i>Instagram</dt>
                                        <dd><a href="https://www.instagram.com/horumon_okada/" target="_blank" rel="noopener noreferrer">@horumon_okada</a></dd>
                                    </div>
                                </dl>
                                <a class="yafuso_market_button_029" href="https://www.instagram.com/horumon_okada/" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-instagram" aria-hidden="true"></i> Instagramへ</a>
                            </div>
                        </article>

                        <article id="market_stalls_tokunobu" class="yafuso_market_shop_029">
                            <div class="yafuso_market_shop_gallery_029">
                                <div class="gallery_thumbnail yafuso_market_gallery_029" data-gallery-id="market_tokunobu_gallery">
                                    <div class="main-gallery">
                                        <div class="main-slide cover">
                                            <img decoding="async" loading="lazy" src="<?php echo $img; ?>/tokunobu_1.webp" alt="屋台ラーメンとくのぶの正油ラーメン" class="active">
                                            <div class="overflow">
                                                <img decoding="async" loading="lazy" src="<?php echo $img; ?>/tokunobu_2.webp" alt="屋台ラーメンとくのぶのラーメン写真">
                                                <img decoding="async" loading="lazy" src="<?php echo $img; ?>/tokunobu_3.webp" alt="屋台ラーメンとくのぶの料理写真">
                                                <img decoding="async" loading="lazy" src="<?php echo $img; ?>/tokunobu_4.webp" alt="屋台ラーメンとくのぶの一品料理">
                                                <img decoding="async" loading="lazy" src="<?php echo $img; ?>/tokunobu_5.webp" alt="屋台ラーメンとくのぶの店舗写真">
                                            </div>
                                            <div class="gallery-nav">
                                                <button class="nav-button prev" type="button" aria-label="前の写真">&#10094;</button>
                                                <button class="nav-button next" type="button" aria-label="次の写真">&#10095;</button>
                                            </div>
                                        </div>

                                        <div class="thumbnail-gallery-container">
                                            <div class='space_1 space_sp1'></div>
                                            <div class="thumbnail-gallery">
                                                <div class="thumbnail active">
                                                    <img decoding="async" loading="lazy" src="<?php echo $img; ?>/tokunobu_1.webp" alt="正油ラーメンのサムネイル">
                                                </div>
                                                <div class="thumbnail">
                                                    <img decoding="async" loading="lazy" src="<?php echo $img; ?>/tokunobu_2.webp" alt="ラーメン写真のサムネイル">
                                                </div>
                                                <div class="thumbnail">
                                                    <img decoding="async" loading="lazy" src="<?php echo $img; ?>/tokunobu_3.webp" alt="料理写真のサムネイル">
                                                </div>
                                                <div class="thumbnail">
                                                    <img decoding="async" loading="lazy" src="<?php echo $img; ?>/tokunobu_4.webp" alt="一品料理のサムネイル">
                                                </div>
                                                <div class="thumbnail">
                                                    <img decoding="async" loading="lazy" src="<?php echo $img; ?>/tokunobu_5.webp" alt="店舗写真のサムネイル">
                                                </div>
                                            </div>
                                            <div class="thumbnail-nav">
                                                <button class="thumb-button prev" type="button" aria-label="前のサムネイル">&#10094;</button>
                                                <button class="thumb-button next" type="button" aria-label="次のサムネイル">&#10095;</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="yafuso_market_shop_body_029">
                                <img decoding="async" class="yafuso_market_sns_icon_029" src="<?php echo $img; ?>/sns_tokunobu.webp" alt="とくのぶのSNSアイコン" loading="lazy">
                                <h3>屋台ラーメン <span class="red">とくのぶ</span></h3>
                                <p class="yafuso_market_lead_029">大量の煮干しと節類に愛情をたっぷり込めた、香り高い正油スープ。<br>
                                今日をがんばる心と体に、じんわり染み渡る一杯です。</p>
                                <ul class="yafuso_market_point_list_029">
                                    <li><i class="fa-solid fa-circle-check" aria-hidden="true"></i>煮干し＆節系の香り高い、極上の正油スープ</li>
                                    <li><i class="fa-solid fa-circle-check" aria-hidden="true"></i>お食事メインのランチから、横丁巡りのひとときまで</li>
                                    <li><i class="fa-solid fa-circle-check" aria-hidden="true"></i>ラーメン一杯だけでのご利用も、どうぞお気軽に</li>
                                    <li><i class="fa-solid fa-circle-check" aria-hidden="true"></i>今日をがんばる心と体に、じんわり染み渡る活力の味</li>
                                </ul>
                            </div>
                            <div class="yafuso_market_meta_029">
                                <dl>
                                    <div>
                                        <dt><i class="fa-solid fa-bowl-food" aria-hidden="true"></i>スープ</dt>
                                        <dd>煮干し＆節系</dd>
                                    </div>
                                    <div>
                                        <dt><i class="fa-regular fa-clock" aria-hidden="true"></i>営業時間</dt>
                                        <dd>18:00 〜 2:00</dd>
                                    </div>
                                    <div>
                                        <dt><i class="fa-regular fa-calendar" aria-hidden="true"></i>定休日</dt>
                                        <dd>毎週月曜日</dd>
                                    </div>
                                    <div>
                                        <dt><i class="fa-brands fa-instagram" aria-hidden="true"></i>Instagram</dt>
                                        <dd><a href="https://www.instagram.com/tokunobuyatairamen/" target="_blank" rel="noopener noreferrer">@tokunobuyatairamen</a></dd>
                                    </div>
                                </dl>
                                <a class="yafuso_market_button_029" href="https://www.instagram.com/tokunobuyatairamen/" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-instagram" aria-hidden="true"></i> Instagramへ</a>
                            </div>
                        </article>

                        <article id="market_stalls_tatan" class="yafuso_market_shop_029">
                            <div class="yafuso_market_shop_gallery_029">
                                <div class="gallery_thumbnail yafuso_market_gallery_029" data-gallery-id="market_tatan_gallery">
                                    <div class="main-gallery">
                                        <div class="main-slide cover">
                                            <img decoding="async" loading="lazy" src="<?php echo $img; ?>/tatan_3.webp" alt="居酒屋たーたんの料理盛り合わせ" class="active">
                                            <div class="overflow">
                                                <img decoding="async" loading="lazy" src="<?php echo $img; ?>/shop_tatan.webp" alt="居酒屋たーたんの看板イメージ">
                                                <img decoding="async" loading="lazy" src="<?php echo $img; ?>/tatan_1.webp" alt="居酒屋たーたんの店舗イメージ">
                                                <img decoding="async" loading="lazy" src="<?php echo $img; ?>/tatan_2.webp" alt="居酒屋たーたんの一品料理">
                                                <img decoding="async" loading="lazy" src="<?php echo $img; ?>/tatan_4.webp" alt="居酒屋たーたんの料理写真">
                                            </div>
                                            <div class="gallery-nav">
                                                <button class="nav-button prev" type="button" aria-label="前の写真">&#10094;</button>
                                                <button class="nav-button next" type="button" aria-label="次の写真">&#10095;</button>
                                            </div>
                                        </div>

                                        <div class="thumbnail-gallery-container">
                                            <div class='space_1 space_sp1'></div>
                                            <div class="thumbnail-gallery">
                                                <div class="thumbnail active">
                                                    <img decoding="async" loading="lazy" src="<?php echo $img; ?>/tatan_3.webp" alt="料理盛り合わせのサムネイル">
                                                </div>
                                                <div class="thumbnail">
                                                    <img decoding="async" loading="lazy" src="<?php echo $img; ?>/shop_tatan.webp" alt="たーたん看板のサムネイル">
                                                </div>
                                                <div class="thumbnail">
                                                    <img decoding="async" loading="lazy" src="<?php echo $img; ?>/tatan_1.webp" alt="店舗イメージのサムネイル">
                                                </div>
                                                <div class="thumbnail">
                                                    <img decoding="async" loading="lazy" src="<?php echo $img; ?>/tatan_2.webp" alt="一品料理のサムネイル">
                                                </div>
                                                <div class="thumbnail">
                                                    <img decoding="async" loading="lazy" src="<?php echo $img; ?>/tatan_4.webp" alt="料理写真のサムネイル">
                                                </div>
                                            </div>
                                            <div class="thumbnail-nav">
                                                <button class="thumb-button prev" type="button" aria-label="前のサムネイル">&#10094;</button>
                                                <button class="thumb-button next" type="button" aria-label="次のサムネイル">&#10095;</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="yafuso_market_shop_body_029">
                                <img decoding="async" class="yafuso_market_sns_icon_029" src="<?php echo $img; ?>/sns_tatan.webp" alt="たーたんのSNSアイコン" loading="lazy">
                                <h3>居酒屋 <span class="red">たーたん</span></h3>
                                <p class="yafuso_market_lead_029">沖縄料理とお肉料理をメインとした本格居酒屋。<br>
                                日替わりオススメを含めて常時40〜50種のフードメニューがそろいます。</p>
                                <ul class="yafuso_market_point_list_029">
                                    <li><i class="fa-solid fa-circle-check" aria-hidden="true"></i>フードメニューは常時40〜50種</li>
                                    <li><i class="fa-solid fa-circle-check" aria-hidden="true"></i>せんべろ・飲み放題あり</li>
                                    <li><i class="fa-solid fa-circle-check" aria-hidden="true"></i>飲み放題は約40種</li>
                                    <li><i class="fa-solid fa-circle-check" aria-hidden="true"></i>プレモルや角ハイボールも楽しめる</li>
                                </ul>
                            </div>
                            <div class="yafuso_market_meta_029">
                                <dl>
                                    <div>
                                        <dt><i class="fa-solid fa-utensils" aria-hidden="true"></i>フード</dt>
                                        <dd>常時40〜50種</dd>
                                    </div>
                                    <div>
                                        <dt><i class="fa-regular fa-clock" aria-hidden="true"></i>営業時間</dt>
                                        <dd>18:00 〜 2:00</dd>
                                    </div>
                                    <div>
                                        <dt><i class="fa-regular fa-calendar" aria-hidden="true"></i>定休日</dt>
                                        <dd>毎週月曜日</dd>
                                    </div>
                                    <div>
                                        <dt><i class="fa-brands fa-instagram" aria-hidden="true"></i>Instagram</dt>
                                        <dd><a href="https://www.instagram.com/ta_tan0301/" target="_blank" rel="noopener noreferrer">@ta_tan0301</a></dd>
                                    </div>
                                </dl>
                                <a class="yafuso_market_button_029" href="https://www.instagram.com/ta_tan0301/" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-instagram" aria-hidden="true"></i> Instagramへ</a>
                            </div>
                        </article>

                        <article id="market_stalls_tebachu" class="yafuso_market_shop_029">
                            <div class="yafuso_market_shop_gallery_029">
                                <div class="gallery_thumbnail yafuso_market_gallery_029" data-gallery-id="market_tebachu_gallery">
                                    <div class="main-gallery">
                                        <div class="main-slide cover">
                                            <img decoding="async" loading="lazy" src="<?php echo $img; ?>/tebachu_combo.webp" alt="手羽先居酒屋てばちゅ〜の手羽唐コンプリート" class="active">
                                            <div class="overflow">
                                                <img decoding="async" loading="lazy" src="<?php echo $img; ?>/tebachu_1.webp" alt="手羽先居酒屋てばちゅ〜の手羽先盛り合わせ">
                                                <img decoding="async" loading="lazy" src="<?php echo $img; ?>/tebachu_2.webp" alt="手羽先居酒屋てばちゅ〜の手羽先">
                                                <img decoding="async" loading="lazy" src="<?php echo $img; ?>/tebachu_3.webp" alt="手羽先居酒屋てばちゅ〜の料理写真">
                                                <img decoding="async" loading="lazy" src="<?php echo $img; ?>/tebachu_4.webp" alt="手羽先居酒屋てばちゅ〜の一品料理">
                                                <img decoding="async" loading="lazy" src="<?php echo $img; ?>/tebachu_5.webp" alt="手羽先居酒屋てばちゅ〜の料理写真">
                                                <img decoding="async" loading="lazy" src="<?php echo $img; ?>/tebachu_6.webp" alt="手羽先居酒屋てばちゅ〜の料理写真">
                                                <img decoding="async" loading="lazy" src="<?php echo $img; ?>/tebachu_7.webp" alt="手羽先居酒屋てばちゅ〜の料理写真">
                                                <img decoding="async" loading="lazy" src="<?php echo $img; ?>/tebachu_8.webp" alt="手羽先居酒屋てばちゅ〜の料理写真">
                                                <img decoding="async" loading="lazy" src="<?php echo $img; ?>/tebachu_9.webp" alt="手羽先居酒屋てばちゅ〜の料理写真">
                                                <img decoding="async" loading="lazy" src="<?php echo $img; ?>/tebachu_10.webp" alt="手羽先居酒屋てばちゅ〜の料理写真">
                                                <img decoding="async" loading="lazy" src="<?php echo $img; ?>/tebachu_11.webp" alt="手羽先居酒屋てばちゅ〜の料理写真">
                                                <img decoding="async" loading="lazy" src="<?php echo $img; ?>/shop_tebachu.webp" alt="手羽先居酒屋てばちゅ〜の看板イメージ">
                                            </div>
                                            <div class="gallery-nav">
                                                <button class="nav-button prev" type="button" aria-label="前の写真">&#10094;</button>
                                                <button class="nav-button next" type="button" aria-label="次の写真">&#10095;</button>
                                            </div>
                                        </div>

                                        <div class="thumbnail-gallery-container">
                                            <div class='space_1 space_sp1'></div>
                                            <div class="thumbnail-gallery">
                                                <div class="thumbnail active">
                                                    <img decoding="async" loading="lazy" src="<?php echo $img; ?>/tebachu_combo.webp" alt="手羽唐コンプリートのサムネイル">
                                                </div>
                                                <div class="thumbnail">
                                                    <img decoding="async" loading="lazy" src="<?php echo $img; ?>/tebachu_1.webp" alt="手羽先盛り合わせのサムネイル">
                                                </div>
                                                <div class="thumbnail">
                                                    <img decoding="async" loading="lazy" src="<?php echo $img; ?>/tebachu_2.webp" alt="手羽先のサムネイル">
                                                </div>
                                                <div class="thumbnail">
                                                    <img decoding="async" loading="lazy" src="<?php echo $img; ?>/tebachu_3.webp" alt="料理写真のサムネイル">
                                                </div>
                                                <div class="thumbnail">
                                                    <img decoding="async" loading="lazy" src="<?php echo $img; ?>/tebachu_4.webp" alt="一品料理のサムネイル">
                                                </div>
                                                <div class="thumbnail">
                                                    <img decoding="async" loading="lazy" src="<?php echo $img; ?>/tebachu_5.webp" alt="てばちゅ〜料理写真のサムネイル">
                                                </div>
                                                <div class="thumbnail">
                                                    <img decoding="async" loading="lazy" src="<?php echo $img; ?>/tebachu_6.webp" alt="てばちゅ〜料理写真のサムネイル">
                                                </div>
                                                <div class="thumbnail">
                                                    <img decoding="async" loading="lazy" src="<?php echo $img; ?>/tebachu_7.webp" alt="てばちゅ〜料理写真のサムネイル">
                                                </div>
                                                <div class="thumbnail">
                                                    <img decoding="async" loading="lazy" src="<?php echo $img; ?>/tebachu_8.webp" alt="てばちゅ〜料理写真のサムネイル">
                                                </div>
                                                <div class="thumbnail">
                                                    <img decoding="async" loading="lazy" src="<?php echo $img; ?>/tebachu_9.webp" alt="てばちゅ〜料理写真のサムネイル">
                                                </div>
                                                <div class="thumbnail">
                                                    <img decoding="async" loading="lazy" src="<?php echo $img; ?>/tebachu_10.webp" alt="てばちゅ〜料理写真のサムネイル">
                                                </div>
                                                <div class="thumbnail">
                                                    <img decoding="async" loading="lazy" src="<?php echo $img; ?>/tebachu_11.webp" alt="てばちゅ〜料理写真のサムネイル">
                                                </div>
                                                <div class="thumbnail">
                                                    <img decoding="async" loading="lazy" src="<?php echo $img; ?>/shop_tebachu.webp" alt="てばちゅ〜看板のサムネイル">
                                                </div>
                                            </div>
                                            <div class="thumbnail-nav">
                                                <button class="thumb-button prev" type="button" aria-label="前のサムネイル">&#10094;</button>
                                                <button class="thumb-button next" type="button" aria-label="次のサムネイル">&#10095;</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="yafuso_market_shop_body_029">
                                <img decoding="async" class="yafuso_market_sns_icon_029" src="<?php echo $img; ?>/sns_tebachu.webp" alt="てばちゅ〜のSNSアイコン" loading="lazy">
                                <h3>手羽先居酒屋<span class="red">てばちゅ〜</span></h3>
                                <p class="yafuso_market_lead_029">こだわりの調理法で仕上げた手羽先が大人気！<br>
                                    気さくなスタッフたちが出迎えるポップでかわいい居酒屋です。</p>
                                <ul class="yafuso_market_point_list_029">
                                    <li><i class="fa-solid fa-circle-check" aria-hidden="true"></i>こだわり調理の手羽先が名物</li>
                                    <li><i class="fa-solid fa-circle-check" aria-hidden="true"></i>女性でもふらっと入りやすい明るい雰囲気</li>
                                    <li><i class="fa-solid fa-circle-check" aria-hidden="true"></i>横丁飲みの一軒目にもおすすめ</li>
                                    <li><i class="fa-solid fa-circle-check" aria-hidden="true"></i>金・土・祝前日は25時まで営業</li>
                                </ul>
                            </div>
                            <div class="yafuso_market_meta_029">
                                <dl>
                                    <div>
                                        <dt><i class="fa-solid fa-drumstick-bite" aria-hidden="true"></i>名物</dt>
                                        <dd>こだわり調理の手羽先</dd>
                                    </div>
                                    <div>
                                        <dt><i class="fa-regular fa-clock" aria-hidden="true"></i>平日</dt>
                                        <dd>18:00 〜 24:00</dd>
                                    </div>
                                    <div>
                                        <dt><i class="fa-regular fa-clock" aria-hidden="true"></i>金土祝前日</dt>
                                        <dd>18:00 〜 25:00</dd>
                                    </div>
                                    <div>
                                        <dt><i class="fa-regular fa-calendar" aria-hidden="true"></i>定休日</dt>
                                        <dd>毎週月曜日</dd>
                                    </div>
                                    <div>
                                        <dt><i class="fa-brands fa-instagram" aria-hidden="true"></i>Instagram</dt>
                                        <dd><a href="https://www.instagram.com/tebachu.tebasaki/" target="_blank" rel="noopener noreferrer">@tebachu.tebasaki</a></dd>
                                    </div>

                                </dl>
                                <a class="yafuso_market_button_029" href="https://www.instagram.com/tebachu.tebasaki/" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-instagram" aria-hidden="true"></i> Instagramへ</a>
                            </div>
                        </article>
                    </div>
                </div>
            </section>

            <section>
                <div class="yafuso_market_cta_029 bg_pink">
                    <div class="yafuso_single_029">
                        <div class="yafuso_market_cta_inner_029">
                            <div class="yafuso_market_cta_text_029">
                                <span>食事も、カラオケも。</span>
                                <h2 class="pink">屋台村で食べて、<br>ももたろうで歌う。</h2>
                                <p>一軒でじっくり飲むも良し、気分のままハシゴするも良し。<br>
                                あなたのペースで、あなただけの時間をつくってください。</p>
                                <a href="karaoke.php">カラオケワールドももたろうを見る</a>
                            </div>
                            <figure class="yafuso_market_cta_photo_029">
                                <img decoding="async" src="<?php echo $img; ?>/karaoke_momotarou.webp" alt="カラオケワールドももたろうで歌う利用イメージ" loading="lazy">
                            </figure>
                        </div>
                    </div>
                </div>
            </section>

            <div class="yafuso_lower_lantern_footer_029" aria-hidden="true"></div>
        </div>
    </div>
    <!-- 屋台のご紹介 market_stalls.php -->
</main>

<?php include_once './footer.php'; ?>
