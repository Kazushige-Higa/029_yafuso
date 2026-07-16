<?php
require_once './common.php';

$page_title = "やふそ屋台村 ちょうちん横丁";
$page_meta_title = "やふそ屋台村 ちょうちん横丁｜浦添・屋富祖の屋内型屋台村";
$page_meta_description = "浦添市屋富祖にある屋内型屋台村「ちょうちん横丁」。屋富祖のディープな街時間の中で、飲む・食べる・歌う・遊ぶを楽しめるにぎわいの空間です。";
$page_meta_image = $img . "/karaoke_momotaro16.webp";
$page_style = "";
$use_yafuso_layout = true;
$page_script = '<script src="js/slider_fullslider.js" defer></script>';
?>
<?php include_once './header.php'; ?>

<main>
    <section>
        <div id="yafuso_top" class="yafuso_hero_029">
            <div class="yafuso_hero_photo_029 slider_fullslider_wrap" aria-hidden="true">
                <ul class="slider_fullslider yafuso_hero_slider_029">
                    <li class="slide active"><img decoding="async" src="<?php echo $img; ?>/03.webp" alt="カラオケを楽しむグループ" loading="eager" fetchpriority="high"></li>
                    <li class="slide"><img decoding="async" src="<?php echo $img; ?>/06.webp" alt="カラオケと一緒に楽しめるドリンク" loading="lazy"></li>
                    <li class="slide"><img decoding="async" src="<?php echo $img; ?>/tokunobu_2.webp" alt="屋台ラーメンとくのぶの料理イメージ" loading="lazy"></li>
                    <li class="slide"><img decoding="async" src="<?php echo $img; ?>/tebachu_1.webp" alt="屋台村で楽しめる手羽先料理" loading="lazy"></li>
                    <li class="slide"><img decoding="async" src="<?php echo $img; ?>/karaoke_generated_party.webp" alt="にぎやかで心地よい時間は、ここにある！" loading="lazy"></li>
                </ul>
            </div>
            <div class="yafuso_hero_inner_029 act inup">
                <p class="yafuso_hero_kicker_029 act01 blur">ディープな街屋富祖で飲もう！</p>
                <h1 class="act02 blur">
                    <span>やふそ屋台村</span>
                    ちょうちん横丁
                </h1>
                <div class="act04 inup">
                    <p class="yafuso_hero_lead_029">まったりひとり飲みから<br>仲間とわいわい！ハシゴ酒まで<br>にぎやかで心地よい時間は、ここにある！</p>
                    <div class="yafuso_hero_buttons_029">
                        <a href="#shops">【店舗一覧】をチェック！</a>
                        <a href="#access">【アクセス】58号線沿い！</a>
                        <a href="concept.php">【屋富祖】ってどんな街？</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div id="concept" class="yafuso_concept_029">
            <div class="yafuso_single_029 yafuso_concept_grid_029">
                <div class="yafuso_section_title_029 yafuso_section_title_center_029">
                    <span aria-hidden="true"></span>
                    <h2 class="act txt_split type_popup">飲む・食べる・歌う・遊ぶ</h2>
                </div>
                <div class="yafuso_concept_text_029 act inup">
                    <p><span class="red">浦添市屋富祖の屋台村<br class="sponly">『ちょうちん横丁』へようこそ！</span></p>
                    <p>まったりひとり飲みから、仲間とわいわい！<br class="sponly">ハシゴ酒まで、<br>
                        お酒やおつまみ・お食事を楽しみながら、<br>
                        にぎやかで心地よい時間をお過ごしください。</p>
                </div>
                <ul class="yafuso_feature_pills_029 act inup delay_1" aria-label="ちょうちん横丁の特徴">
                    <li><i class="fa-solid fa-martini-glass-citrus" aria-hidden="true"></i>せんべろあり！</li>
                    <li><i class="fa-solid fa-microphone-lines" aria-hidden="true"></i>カラオケ店併設♪</li>
                    <li><i class="fa-solid fa-yen-sign" aria-hidden="true"></i>コスパ◎</li>
                    <li><i class="fa-solid fa-snowflake" aria-hidden="true"></i>屋内で涼しい！</li>
                    <li><i class="fa-solid fa-route" aria-hidden="true"></i>アクセス良好◎</li>
                    <li><i class="fa-regular fa-clock" aria-hidden="true"></i>深夜2時まで営業</li>
                </ul>
            </div>
        </div>
    </section>

    <section>
        <div class="yafuso_top_gallery_029">
            <div>
                <div class="gallery_slider contain set4 right yafuso_top_gallery_slider_029">
                    <ul>
                        <li><img decoding="async" src="<?php echo $img; ?>/karaoke_momotaro16.webp" alt="カラオケワールドももたろうイメージ" loading="lazy"></li>
                        <li><img decoding="async" src="<?php echo $img; ?>/01.webp" alt="やふそ屋台村ちょうちん横丁の写真01" loading="lazy"></li>
                        <li><img decoding="async" src="<?php echo $img; ?>/karaoke_generated_party.webp" alt="カラオケワールドももたろうイメージ02" loading="lazy"></li>
                        <li><img decoding="async" src="<?php echo $img; ?>/02.webp" alt="やふそ屋台村ちょうちん横丁の写真02" loading="lazy"></li>
                        <li><img decoding="async" src="<?php echo $img; ?>/03.webp" alt="やふそ屋台村ちょうちん横丁の写真03" loading="lazy"></li>
                        <li><img decoding="async" src="<?php echo $img; ?>/06.webp" alt="やふそ屋台村ちょうちん横丁の入口写真" loading="lazy"></li>
                    </ul>
                    <ul aria-hidden="true">
                        <li><img decoding="async" src="<?php echo $img; ?>/karaoke_momotaro16.webp" alt="カラオケワールドももたろうイメージ" loading="lazy"></li>
                        <li><img decoding="async" src="<?php echo $img; ?>/01.webp" alt="やふそ屋台村ちょうちん横丁の写真01" loading="lazy"></li>
                        <li><img decoding="async" src="<?php echo $img; ?>/karaoke_generated_party.webp" alt="カラオケワールドももたろうイメージ02" loading="lazy"></li>
                        <li><img decoding="async" src="<?php echo $img; ?>/02.webp" alt="やふそ屋台村ちょうちん横丁の写真02" loading="lazy"></li>
                        <li><img decoding="async" src="<?php echo $img; ?>/03.webp" alt="やふそ屋台村ちょうちん横丁の写真03" loading="lazy"></li>
                        <li><img decoding="async" src="<?php echo $img; ?>/06.webp" alt="やふそ屋台村ちょうちん横丁の入口写真" loading="lazy"></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div id="shops" class="yafuso_shops_029">
            <div class="yafuso_single_029">
                <div class="yafuso_section_title_029 yafuso_section_title_red_029 yafuso_section_title_center_029">
                    <span aria-hidden="true"></span>
                    <h2 class="act txt_split type_popup">屋台のご紹介</h2>
                </div>

                <div class="yafuso_shop_list_029">
                    <article class="yafuso_shop_card_029 act inup">
                        <div class="yafuso_shop_body_029">
                            <img decoding="async" class="yafuso_shop_sns_icon_029" src="<?php echo $img; ?>/sns_momotaro.webp" alt="カラオケワールドももたろうのSNSアイコン" loading="lazy">
                            <h3>カラオケワールド<br>
                                <span class="pink">ももたろう</span>
                            </h3>
                            <p class="yafuso_shop_lead_029">屋台村に隣接するカラオケ店♪<br>
                                お部屋からお電話で屋台のお料理を注文できる！</p>
                            <p class="yafuso_shop_campaign_029 blinking tcenter_sp">平日20時までのご来店で<br class="sponly">飲み放題1時間無料！</p>
                            <ul class="yafuso_shop_points_029">
                                <li><i class="fa-regular fa-circle-check" aria-hidden="true"></i>ダーツマシンあり</li>
                                <li><i class="fa-regular fa-circle-check" aria-hidden="true"></i>予約 098-879-1055</li>
                            </ul>
                            <div class="yafuso_shop_meta_029">
                                <span><i class="fa-regular fa-clock" aria-hidden="true"></i>19:00 - 2:00</span>
                                <span><i class="fa-regular fa-calendar" aria-hidden="true"></i>毎週月曜日</span>
                                <a href="https://www.instagram.com/karaoke.momotaro/" target="_blank" rel="noopener noreferrer">
                                    <i class="fa-brands fa-instagram" aria-hidden="true"></i>@karaoke.momotaro
                                </a>
                            </div>
                            <div class="yafuso_shop_actions_029">
                                <a class="yafuso_shop_detail_button_029" href="market_stalls.php#market_stalls_momotaro">詳しくはコチラ</a>
                                <a class="yafuso_shop_sns_button_029" href="https://www.instagram.com/karaoke.momotaro/" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-instagram" aria-hidden="true"></i>フォローする</a>
                            </div>
                        </div>
                        <figure class="yafuso_shop_visual_029">
                            <img decoding="async" src="<?php echo $img; ?>/karaoke_momotarou.webp" alt="カラオケワールドももたろうの店内イメージ" loading="lazy">
                        </figure>
                    </article>

                    <article class="yafuso_shop_card_029 act inup">
                        <div class="yafuso_shop_body_029">
                            <img decoding="async" class="yafuso_shop_sns_icon_029" src="<?php echo $img; ?>/sns_okada.webp" alt="西成ホルモンおか田のSNSアイコン" loading="lazy">
                            <h3>西成ホルモン <span class="red">おか田</span></h3>
                            <p class="yafuso_shop_lead_029">大阪・西成スタイルのホルモン焼き。<br>
                                昔ながらの大衆酒場の雰囲気で、もつ煮込みが一番人気。</p>
                            <ul class="yafuso_shop_points_029">
                                <li><i class="fa-regular fa-circle-check" aria-hidden="true"></i>テーブルチャージなし</li>
                                <li><i class="fa-regular fa-circle-check" aria-hidden="true"></i>アサヒスーパードライあり</li>
                                <li><i class="fa-regular fa-circle-check" aria-hidden="true"></i>団体さまのご予約OK！</li>
                            </ul>
                            <div class="yafuso_shop_meta_029">
                                <span><i class="fa-regular fa-clock" aria-hidden="true"></i>18:00 - 2:00</span>
                                <span><i class="fa-regular fa-calendar" aria-hidden="true"></i>毎週月曜日</span>
                                <a href="https://www.instagram.com/horumon_okada/" target="_blank" rel="noopener noreferrer">
                                    <i class="fa-brands fa-instagram" aria-hidden="true"></i>@horumon_okada
                                </a>
                            </div>
                            <div class="yafuso_shop_actions_029">
                                <a class="yafuso_shop_detail_button_029" href="market_stalls.php#market_stalls_okada">詳しくはコチラ</a>
                                <a class="yafuso_shop_sns_button_029" href="https://www.instagram.com/horumon_okada/" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-instagram" aria-hidden="true"></i>フォローする</a>
                            </div>
                        </div>
                        <figure class="yafuso_shop_visual_029">
                            <img decoding="async" src="<?php echo $img; ?>/okada_0.webp" alt="西成ホルモンおか田の看板イメージ" loading="lazy">
                        </figure>
                    </article>

                    <article class="yafuso_shop_card_029 act inup">
                        <div class="yafuso_shop_body_029">
                            <img decoding="async" class="yafuso_shop_sns_icon_029" src="<?php echo $img; ?>/sns_tokunobu.webp" alt="屋台ラーメンとくのぶのSNSアイコン" loading="lazy">
                            <h3>屋台ラーメン <span class="red">とくのぶ</span></h3>
                            <p class="yafuso_shop_lead_029">大量の煮干しと節類からとった、香り高い極上の正油スープ。<br>
                                今日をがんばる心と体に、じんわり染み渡る一杯です。</p>
                            <ul class="yafuso_shop_points_029">
                                <li><i class="fa-regular fa-circle-check" aria-hidden="true"></i>今日をがんばる心と体に、じんわり染み渡る活力の味</li>
                                <li><i class="fa-regular fa-circle-check" aria-hidden="true"></i>煮干し＆節系の香り高い、極上の正油スープ</li>
                                <li><i class="fa-regular fa-circle-check" aria-hidden="true"></i>お食事メインのランチから、横丁巡りのひとときまで</li>
                                <li><i class="fa-regular fa-circle-check" aria-hidden="true"></i>ラーメン一杯だけでのご利用も、どうぞお気軽に</li>
                            </ul>
                            <div class="yafuso_shop_meta_029">
                                <span><i class="fa-regular fa-clock" aria-hidden="true"></i>18:00 - 2:00</span>
                                <span><i class="fa-regular fa-calendar" aria-hidden="true"></i>毎週月曜日</span>
                                <a href="https://www.instagram.com/tokunobuyatairamen/" target="_blank" rel="noopener noreferrer">
                                    <i class="fa-brands fa-instagram" aria-hidden="true"></i>@tokunobuyatairamen
                                </a>
                            </div>
                            <div class="yafuso_shop_actions_029">
                                <a class="yafuso_shop_detail_button_029" href="market_stalls.php#market_stalls_tokunobu">詳しくはコチラ</a>
                                <a class="yafuso_shop_sns_button_029" href="https://www.instagram.com/tokunobuyatairamen/" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-instagram" aria-hidden="true"></i>フォローする</a>
                            </div>
                        </div>
                        <figure class="yafuso_shop_visual_029">
                            <img decoding="async" src="<?php echo $img; ?>/tokunobu_1.webp" alt="屋台ラーメンとくのぶのラーメンイメージ" loading="lazy">
                        </figure>
                    </article>

                    <article class="yafuso_shop_card_029 act inup">
                        <div class="yafuso_shop_body_029">
                            <img decoding="async" class="yafuso_shop_sns_icon_029" src="<?php echo $img; ?>/sns_tatan.webp" alt="居酒屋たーたんのSNSアイコン" loading="lazy">
                            <h3>居酒屋 <span class="red">たーたん</span></h3>
                            <p class="yafuso_shop_lead_029">沖縄料理とお肉料理がメイン。<br>
                                常時40〜50種類のフードメニューあり◎</p>
                            <ul class="yafuso_shop_points_029">
                                <li><i class="fa-regular fa-circle-check" aria-hidden="true"></i>日替わりおすすめあり</li>
                                <li><i class="fa-regular fa-circle-check" aria-hidden="true"></i>プレモルや角ハイ込みの飲み放題あり</li>
                            </ul>
                            <div class="yafuso_shop_meta_029">
                                <span><i class="fa-regular fa-clock" aria-hidden="true"></i>18:00 - 2:00</span>
                                <span><i class="fa-regular fa-calendar" aria-hidden="true"></i>毎週月曜日</span>
                                <a href="https://www.instagram.com/ta_tan0301/" target="_blank" rel="noopener noreferrer">
                                    <i class="fa-brands fa-instagram" aria-hidden="true"></i>@ta_tan0301
                                </a>
                            </div>
                            <div class="yafuso_shop_actions_029">
                                <a class="yafuso_shop_detail_button_029" href="market_stalls.php#market_stalls_tatan">詳しくはコチラ</a>
                                <a class="yafuso_shop_sns_button_029" href="https://www.instagram.com/ta_tan0301/" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-instagram" aria-hidden="true"></i>フォローする</a>
                            </div>
                        </div>
                        <figure class="yafuso_shop_visual_029">
                            <img decoding="async" src="<?php echo $img; ?>/tatan_3.webp" alt="居酒屋たーたんの看板イメージ" loading="lazy">
                        </figure>
                    </article>

                    <article class="yafuso_shop_card_029 act inup">
                        <div class="yafuso_shop_body_029">
                            <img decoding="async" class="yafuso_shop_sns_icon_029" src="<?php echo $img; ?>/sns_tebachu.webp" alt="手羽先居酒屋てばちゅ〜のSNSアイコン" loading="lazy">
                            <h3>手羽先居酒屋<br><span class="red">てばちゅ〜</span></h3>
                            <p class="yafuso_shop_lead_029">こだわり調理の食べやすい手羽先が人気の居酒屋。<br>
                                気さくなスタッフ達が迎えてくれるので、お一人様から女性まで気軽に飲みに行けます！</p>
                            <ul class="yafuso_shop_points_029">
                                <li><i class="fa-regular fa-circle-check" aria-hidden="true"></i>女性でもふらっと入りやすい</li>
                                <li><i class="fa-regular fa-circle-check" aria-hidden="true"></i>横丁飲みの一軒目にも最適</li>
                                <li><i class="fa-regular fa-circle-check" aria-hidden="true"></i>金土祝前日は25:00まで</li>
                            </ul>
                            <div class="yafuso_shop_meta_029">
                                <span><i class="fa-regular fa-clock" aria-hidden="true"></i>平日 18:00 - 24:00 / 金土祝前日 18:00 - 25:00</span>
                                <span><i class="fa-regular fa-calendar" aria-hidden="true"></i>毎週月曜日</span>
                                <a href="https://www.instagram.com/tebachu.tebasaki/" target="_blank" rel="noopener noreferrer">
                                    <i class="fa-brands fa-instagram" aria-hidden="true"></i>@tebachu.tebasaki
                                </a>
                            </div>
                            <div class="yafuso_shop_actions_029">
                                <a class="yafuso_shop_detail_button_029" href="market_stalls.php#market_stalls_tebachu">詳しくはコチラ</a>
                                <a class="yafuso_shop_sns_button_029" href="https://www.instagram.com/tebachu.tebasaki/" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-instagram" aria-hidden="true"></i>フォローする</a>
                            </div>
                        </div>
                        <figure class="yafuso_shop_visual_029">
                            <img decoding="async" src="<?php echo $img; ?>/tebachu_1.webp" alt="手羽先居酒屋てばちゅ〜の看板イメージ" loading="lazy">
                        </figure>
                    </article>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div id="scene" class="yafuso_scene_029">
            <div class="yafuso_single_029">
                <div class="yafuso_section_title_029 yafuso_section_title_center_029">
                    <span aria-hidden="true"></span>
                    <h2 class="act txt_split type_popup">こんな時には"屋台村"へ！</h2>
                </div>
                <ul class="yafuso_scene_grid_029 act inup">
                    <li>
                        <figure>
                            <img decoding="async" src="<?php echo $img; ?>/use_party_v2.webp" alt="飲み会・打ち上げのイメージ" loading="lazy">
                        </figure>
                        <div>
                            <h3><span>01</span>飲み会・打ち上げ</h3>
                            <p>友達・会社の同僚・サークル仲間と。食事の後の楽しいカラオケまで、全部ここで完結♪</p>
                        </div>
                    </li>
                    <li>
                        <figure>
                            <img decoding="async" src="<?php echo $img; ?>/use_date_v2.webp" alt="デート・女子会のイメージ" loading="lazy">
                        </figure>
                        <div>
                            <h3><span>02</span>カジュアルな飲みに◎</h3>
                            <p>横丁の中でハシゴ酒できて移動も楽！</p>
                        </div>
                    </li>
                    <li>
                        <figure>
                            <img decoding="async" src="<?php echo $img; ?>/use_karaoke_v2.webp" alt="カラオケ × 屋台村のイメージ" loading="lazy">
                        </figure>
                        <div>
                            <h3><span>03</span>カラオケ × 屋台村</h3>
                            <p>個室で歌いながら、屋台から本格フードとお酒を届けてもらえる特別体験。</p>
                        </div>
                    </li>
                    <li>
                        <figure>
                            <img decoding="async" src="<?php echo $img; ?>/use_solo_v2.webp" alt="一人飲み・ふらっと立ち寄りの店主イメージ" loading="lazy">
                        </figure>
                        <div>
                            <h3><span>04</span>一人飲み・ふらっと立ち寄り</h3>
                            <p>アットホームな空間で、まったり過ごしたいひとり時間にも◎</p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </section>

</main>

<?php include_once './footer.php'; ?>
