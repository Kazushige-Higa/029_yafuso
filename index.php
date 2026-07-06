<?php
require_once './common.php';

$page_title = "やふそ屋台村 ちょうちん横丁";
$page_meta_title = "やふそ屋台村 ちょうちん横丁｜浦添・屋富祖の屋内型屋台村";
$page_meta_description = "浦添市屋富祖にある屋内型屋台村「ちょうちん横丁」。ホルモン、ラーメン、沖縄料理、手羽先、カラオケを天候を気にせず快適に楽しめる、にぎわいと安心の空間です。";
$page_meta_image = $img . "/hero_scene.webp";
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
                    <li class="slide active"><img src="<?php echo $img; ?>/03.webp" alt="やふそ屋台村ちょうちん横丁で食事を楽しむ来店客" loading="eager"></li>
                    <li class="slide"><img src="<?php echo $img; ?>/02.webp" alt="提灯が灯る屋内型屋台村のにぎわい" loading="lazy"></li>
                    <li class="slide"><img src="<?php echo $img; ?>/karaoke_generated_party.webp" alt="カラオケワールドももたろう画像" loading="lazy"></li>
                    <li class="slide"><img src="<?php echo $img; ?>/01.webp" alt="屋富祖のちょうちん横丁に並ぶ屋台席" loading="lazy"></li>
                    <li class="slide"><img src="<?php echo $img; ?>/hero_scene.webp" alt="赤ちょうちんが並ぶやふそ屋台村の入口" loading="lazy"></li>
                </ul>
            </div>
            <div class="yafuso_hero_inner_029 act inup">
                <p class="yafuso_hero_kicker_029 act01 blur">浦添市初の屋台村</p>
                <h1 class="act02 blur">
                    <span>やふそ屋台村</span>
                    ちょうちん横丁
                </h1>
                <div class="act04 inup">
                    <p class="yafuso_hero_lead_029">5店舗が集まる、にぎわいと安心の空間。<br>雨の日も、暑い日も、ここにおいで。</p>
                    <div class="yafuso_hero_buttons_029">
                        <a href="#shops">屋台のご紹介を見る</a>
                        <a href="#access">アクセスを見る</a>
                    </div>
                    <ul class="yafuso_hero_status_029" aria-label="営業情報の概要">
                        <li><i class="fa-regular fa-clock" aria-hidden="true"></i>18:00〜2:00</li>
                        <li><i class="fa-solid fa-house" aria-hidden="true"></i>屋内型</li>
                        <li><i class="fa-solid fa-coins" aria-hidden="true"></i>せんべろ 1,000円〜</li>
                        <li><i class="fa-solid fa-microphone-lines" aria-hidden="true"></i>カラオケ併設！</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div id="concept" class="yafuso_concept_029">
            <div class="yafuso_single_029 yafuso_concept_grid_029">
                <div class="yafuso_section_title_029 yafuso_section_title_center_029">
                    <span aria-hidden="true"></span>
                    <h2 class="act txt_split type_popup">コンセプト</h2>
                </div>
                <div class="yafuso_concept_text_029 act inup">
                    <p class="orange">「今日という一日に、晴れやかな余韻を。」</p>
                    <p><span class="red">浦添市屋富祖の屋内型屋台村「ちょうちん横丁」へようこそ。</span><br>
                        ここは、いつでも、どんな気分のときでも、心地よく過ごせる場所です。</p>
                    <p class="t_m5">香ばしいホルモンの煙、熱々のラーメン、心まであたたまる沖縄料理に手羽先、そして場を彩るカラオケ。それぞれの店が持つ「味」と「人柄」の灯りが集まって、ひとつの温かい場をつくっています。</p>
                    <p>ご褒美のひとり飲みから、仲間との楽しいハシゴ酒まで。食べたいものが違っても、ここならみんなが好きなものを選べて笑顔になれる。</p>
                    <p><span class="red">雨の日でも天候を気にせず快適に過ごせる店内</span>で、お好きな時間をゆっくりとお過ごしください。ちょうちんの暖かな灯りに包まれながら、訪れた人の心に「いい灯」がともる、そんな安心できる場所をいつでもお届けします。</p>
                </div>
                <figure class="yafuso_concept_photo_029 act blur delay_1">
                    <img src="<?php echo $img; ?>/concept_scene.webp" alt="ちょうちん横丁のにぎやかな屋内屋台村" loading="lazy">
                </figure>
            </div>
        </div>
    </section>

    <section>
        <div class="yafuso_top_gallery_029">
            <div>
                <div class="gallery_slider contain set4 right yafuso_top_gallery_slider_029">
                    <ul>
                        <li><img src="<?php echo $img; ?>/karaoke_momotaro16.webp" alt="カラオケワールドももたろうイメージ" loading="lazy"></li>
                        <li><img src="<?php echo $img; ?>/01.webp" alt="やふそ屋台村ちょうちん横丁の写真01" loading="lazy"></li>
                        <li><img src="<?php echo $img; ?>/karaoke_generated_party.webp" alt="カラオケワールドももたろうイメージ02" loading="lazy"></li>
                        <li><img src="<?php echo $img; ?>/02.webp" alt="やふそ屋台村ちょうちん横丁の写真02" loading="lazy"></li>
                        <li><img src="<?php echo $img; ?>/03.webp" alt="やふそ屋台村ちょうちん横丁の写真03" loading="lazy"></li>
                        <li><img src="<?php echo $img; ?>/hero_scene.webp" alt="やふそ屋台村ちょうちん横丁の入口写真" loading="lazy"></li>
                    </ul>
                    <ul aria-hidden="true">
                        <li><img src="<?php echo $img; ?>/karaoke_momotaro16.webp" alt="カラオケワールドももたろうイメージ" loading="lazy"></li>
                        <li><img src="<?php echo $img; ?>/01.webp" alt="やふそ屋台村ちょうちん横丁の写真01" loading="lazy"></li>
                        <li><img src="<?php echo $img; ?>/karaoke_generated_party.webp" alt="カラオケワールドももたろうイメージ02" loading="lazy"></li>
                        <li><img src="<?php echo $img; ?>/02.webp" alt="やふそ屋台村ちょうちん横丁の写真02" loading="lazy"></li>
                        <li><img src="<?php echo $img; ?>/03.webp" alt="やふそ屋台村ちょうちん横丁の写真03" loading="lazy"></li>
                        <li><img src="<?php echo $img; ?>/hero_scene.webp" alt="やふそ屋台村ちょうちん横丁の入口写真" loading="lazy"></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="yafuso_reason_029">
            <div class="yafuso_single_029">
                <div class="yafuso_section_title_029 yafuso_section_title_center_029 yafuso_reason_heading_029">
                    <span aria-hidden="true"></span>
                    <h2 class="act txt_split type_popup">選ばれる4つの理由</h2>
                </div>
                <ul class="yafuso_reason_grid_029 act inup delay_1">
                    <li>
                        <figure>
                            <img src="<?php echo $img; ?>/reason_value.webp" alt="圧倒的コスパのイラスト" loading="lazy">
                        </figure>
                        <h3>圧倒的コスパ</h3>
                        <p>せんべろ・飲み放題が<span class="red">1,000円〜！</span><br>
                            お財布に優しく、思い切り楽しめる。</p>
                    </li>
                    <li>
                        <figure>
                            <img src="<?php echo $img; ?>/reason_late.webp" alt="深夜2時までのイラスト" loading="lazy">
                        </figure>
                        <h3>深夜2時まで</h3>
                        <p>深夜2:00まで営業中！<br>
                            <span class="red">屋台</span>からカラオケまでずっとここで。
                        </p>
                    </li>
                    <li>
                        <figure>
                            <img src="<?php echo $img; ?>/reason_indoor.webp" alt="屋内で快適のイラスト" loading="lazy">
                        </figure>
                        <h3>屋内で快適</h3>
                        <p>沖縄の暑さも突然の雨も<span class="red">関係なし！</span><br>
                            エアコン完備で年中快適。</p>
                    </li>
                    <li>
                        <figure>
                            <img src="<?php echo $img; ?>/reason_all_in.webp" alt="全部ここで完結のイラスト" loading="lazy">
                        </figure>
                        <h3>全部ここで完結</h3>
                        <p><span class="red">食べる・飲む・歌う・ダーツ。</span><br>
                            5店舗とカラオケが一か所に集結！</p>
                    </li>
                </ul>
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
                            <img class="yafuso_shop_sns_icon_029" src="<?php echo $img; ?>/sns_momotaro.webp" alt="カラオケワールドももたろうのSNSアイコン" loading="lazy">
                            <h3>カラオケワールド<br>
                                <span class="pink">ももたろう</span>
                            </h3>
                            <p class="yafuso_shop_lead_029">屋台村に隣接するカラオケ店。<br>
                                部屋から屋台の料理を注文できる唯一無二のスタイル。</p>
                            <ul class="yafuso_shop_points_029">
                                <li><i class="fa-regular fa-circle-check" aria-hidden="true"></i>ダーツマシンあり</li>
                                <li><i class="fa-regular fa-circle-check" aria-hidden="true"></i>平日20時までの来店で飲み放題1時間無料</li>
                                <li><i class="fa-regular fa-circle-check" aria-hidden="true"></i>予約 098-879-1055</li>
                            </ul>
                            <div class="yafuso_shop_meta_029">
                                <span><i class="fa-regular fa-clock" aria-hidden="true"></i>19:00 - 2:00</span>
                                <span><i class="fa-regular fa-calendar" aria-hidden="true"></i>毎週月曜日</span>
                                <a href="https://www.instagram.com/karaoke.momotaro/" target="_blank" rel="noopener noreferrer">
                                    <i class="fa-brands fa-instagram" aria-hidden="true"></i>@karaoke.momotaro
                                </a>
                            </div>
                            <a class="yafuso_shop_detail_button_029" href="#market_stalls_momotaro">詳しくはコチラ</a>
                        </div>
                        <figure class="yafuso_shop_visual_029">
                            <img src="<?php echo $img; ?>/karaoke_momotarou.webp" alt="カラオケワールドももたろうの店内イメージ" loading="lazy">
                        </figure>
                    </article>

                    <article class="yafuso_shop_card_029 act inup">
                        <div class="yafuso_shop_body_029">
                            <img class="yafuso_shop_sns_icon_029" src="<?php echo $img; ?>/sns_okada.webp" alt="西成ホルモンおか田のSNSアイコン" loading="lazy">
                            <h3>西成ホルモン <span class="red">おか田</span></h3>
                            <p class="yafuso_shop_lead_029">大阪・西成スタイルのホルモン焼き。<br>
                                昔ながらの大衆酒場の雰囲気で、もつ煮込みが一番人気。</p>
                            <ul class="yafuso_shop_points_029">
                                <li><i class="fa-regular fa-circle-check" aria-hidden="true"></i>テーブルチャージなし</li>
                                <li><i class="fa-regular fa-circle-check" aria-hidden="true"></i>アサヒスーパードライあり</li>
                                <li><i class="fa-regular fa-circle-check" aria-hidden="true"></i>団体予約OK</li>
                            </ul>
                            <div class="yafuso_shop_meta_029">
                                <span><i class="fa-regular fa-clock" aria-hidden="true"></i>18:00 - 2:00</span>
                                <span><i class="fa-regular fa-calendar" aria-hidden="true"></i>毎週月曜日</span>
                                <a href="https://www.instagram.com/horumon_okada/" target="_blank" rel="noopener noreferrer">
                                    <i class="fa-brands fa-instagram" aria-hidden="true"></i>@horumon_okada
                                </a>
                            </div>
                            <a class="yafuso_shop_detail_button_029" href="#market_stalls_okada">詳しくはコチラ</a>
                        </div>
                        <figure class="yafuso_shop_visual_029">
                            <img src="<?php echo $img; ?>/okada_0.webp" alt="西成ホルモンおか田の看板イメージ" loading="lazy">
                        </figure>
                    </article>

                    <article class="yafuso_shop_card_029 act inup">
                        <div class="yafuso_shop_body_029">
                            <img class="yafuso_shop_sns_icon_029" src="<?php echo $img; ?>/sns_tokunobu.webp" alt="屋台ラーメンとくのぶのSNSアイコン" loading="lazy">
                            <h3>屋台ラーメン <span class="red">とくのぶ</span></h3>
                            <p class="yafuso_shop_lead_029">大量の煮干しと節類からとった、香り高い極上の正油スープ。<br>
                                今日をがんばる心と体に、じんわり染み渡る一杯です。</p>
                            <ul class="yafuso_shop_points_029">
                                <li><i class="fa-regular fa-circle-check" aria-hidden="true"></i>煮干し＆節系の香り高い、極上の正油スープ</li>
                                <li><i class="fa-regular fa-circle-check" aria-hidden="true"></i>お食事メインのランチから、横丁巡りのひとときまで</li>
                                <li><i class="fa-regular fa-circle-check" aria-hidden="true"></i>ラーメン一杯だけでのご利用も、どうぞお気軽に</li>
                                <li><i class="fa-regular fa-circle-check" aria-hidden="true"></i>今日をがんばる心と体に、じんわり染み渡る活力の味</li>
                            </ul>
                            <div class="yafuso_shop_meta_029">
                                <span><i class="fa-regular fa-clock" aria-hidden="true"></i>18:00 - 2:00</span>
                                <span><i class="fa-regular fa-calendar" aria-hidden="true"></i>毎週月曜日</span>
                                <a href="https://www.instagram.com/tokunobuyatairamen/" target="_blank" rel="noopener noreferrer">
                                    <i class="fa-brands fa-instagram" aria-hidden="true"></i>@tokunobuyatairamen
                                </a>
                            </div>
                            <a class="yafuso_shop_detail_button_029" href="#market_stalls_tokunobu">詳しくはコチラ</a>
                        </div>
                        <figure class="yafuso_shop_visual_029">
                            <img src="<?php echo $img; ?>/tokunobu_1.webp" alt="屋台ラーメンとくのぶのラーメンイメージ" loading="lazy">
                        </figure>
                    </article>

                    <article class="yafuso_shop_card_029 act inup">
                        <div class="yafuso_shop_body_029">
                            <img class="yafuso_shop_sns_icon_029" src="<?php echo $img; ?>/sns_tatan.webp" alt="居酒屋たーたんのSNSアイコン" loading="lazy">
                            <h3>居酒屋 <span class="red">たーたん</span></h3>
                            <p class="yafuso_shop_lead_029">沖縄料理とお肉料理をメイン。<br>
                                常時40から50種のフードメニューを提供。</p>
                            <ul class="yafuso_shop_points_029">
                                <li><i class="fa-regular fa-circle-check" aria-hidden="true"></i>日替わりおすすめあり</li>
                                <li><i class="fa-regular fa-circle-check" aria-hidden="true"></i>プレモルや角ハイ入り飲み放題</li>
                                <li><i class="fa-regular fa-circle-check" aria-hidden="true"></i>約40種のせんべろ</li>
                            </ul>
                            <div class="yafuso_shop_meta_029">
                                <span><i class="fa-regular fa-clock" aria-hidden="true"></i>18:00 - 2:00</span>
                                <span><i class="fa-regular fa-calendar" aria-hidden="true"></i>毎週月曜日</span>
                                <a href="https://www.instagram.com/ta_tan0301/" target="_blank" rel="noopener noreferrer">
                                    <i class="fa-brands fa-instagram" aria-hidden="true"></i>@ta_tan0301
                                </a>
                            </div>
                            <a class="yafuso_shop_detail_button_029" href="#market_stalls_tatan">詳しくはコチラ</a>
                        </div>
                        <figure class="yafuso_shop_visual_029">
                            <img src="<?php echo $img; ?>/tatan_3.webp" alt="居酒屋たーたんの看板イメージ" loading="lazy">
                        </figure>
                    </article>

                    <article class="yafuso_shop_card_029 act inup">
                        <div class="yafuso_shop_body_029">
                            <img class="yafuso_shop_sns_icon_029" src="<?php echo $img; ?>/sns_tebachu.webp" alt="手羽先居酒屋てばちゅ〜のSNSアイコン" loading="lazy">
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
                            <a class="yafuso_shop_detail_button_029" href="#market_stalls_tebachu">詳しくはコチラ</a>
                        </div>
                        <figure class="yafuso_shop_visual_029">
                            <img src="<?php echo $img; ?>/tebachu_1.webp" alt="手羽先居酒屋てばちゅ〜の看板イメージ" loading="lazy">
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
                    <h2 class="act txt_split type_popup">今日の使い方</h2>
                </div>
                <ul class="yafuso_scene_grid_029 act inup">
                    <li>
                        <figure>
                            <img src="<?php echo $img; ?>/use_party_v2.webp" alt="飲み会・打ち上げのイメージ" loading="lazy">
                        </figure>
                        <div>
                            <h3><span>01</span>飲み会・打ち上げ</h3>
                            <p>会社の同僚、サークル仲間、友達グループで。食事の後のカラオケまで全部ここで完結。</p>
                        </div>
                    </li>
                    <li>
                        <figure>
                            <img src="<?php echo $img; ?>/use_date_v2.webp" alt="デート・女子会のイメージ" loading="lazy">
                        </figure>
                        <div>
                            <h3><span>02</span>デート・女子会</h3>
                            <p>選択肢の多さが会話のネタになる、カジュアルでにぎやかな横丁時間。</p>
                        </div>
                    </li>
                    <li>
                        <figure>
                            <img src="<?php echo $img; ?>/use_karaoke_v2.webp" alt="カラオケ × 屋台村のイメージ" loading="lazy">
                        </figure>
                        <div>
                            <h3><span>03</span>カラオケ × 屋台村</h3>
                            <p>個室で歌いながら、屋台から本格フードとお酒を届けてもらえる特別体験。</p>
                        </div>
                    </li>
                    <li>
                        <figure>
                            <img src="<?php echo $img; ?>/use_solo_v2.webp" alt="一人飲み・ふらっと立ち寄りのイメージ" loading="lazy">
                        </figure>
                        <div>
                            <h3><span>04</span>一人飲み・ふらっと立ち寄り</h3>
                            <p>カウンターでマスターや常連とゆるく話す、アットホームな時間にも。</p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </section>

    <section>
        <div id="price" class="yafuso_price_029">
            <div class="yafuso_single_029">
                <div class="yafuso_section_title_029 yafuso_section_title_center_029">
                    <span aria-hidden="true"></span>
                    <h2 class="act txt_split type_popup">お得な料金プラン</h2>
                </div>
                <div class="yafuso_price_grid_029">
                    <article>
                        <figure><img src="<?php echo $img; ?>/price_senbero.webp" alt="せんべろプランのイラスト" loading="lazy"></figure>
                        <h3>せんべろプラン</h3>
                        <p>ドリンク最大4杯</p>
                        <strong>¥1,000</strong>
                        <small>おか田・たーたんで楽しめる定番プラン。<br>チャージ料なし。</small>
                    </article>
                    <article>
                        <figure><img src="<?php echo $img; ?>/price_nomihodai.webp" alt="飲み放題プランのイラスト" loading="lazy"></figure>
                        <h3>飲み放題プラン</h3>
                        <p>横丁巡りにうれしい利用しやすい価格</p>
                        <strong>1h ¥1,000〜</strong>
                        <small>たーたんはプレモル・角ハイ入り。<br>内容は店舗により異なります。</small>
                    </article>
                    <article>
                        <figure><img src="<?php echo $img; ?>/price_karaoke.webp" alt="カラオケ平日特典のイラスト" loading="lazy"></figure>
                        <h3>カラオケ平日特典</h3>
                        <p>平日20時までの来店で</p>
                        <strong>飲み放題<br>1時間無料</strong>
                        <small>ダーツマシン設置。<br>部屋から屋台村への注文もできます。</small>
                    </article>
                </div>
                <p class="yafuso_price_note_029">価格は税込です。店舗により内容が異なります。詳細は各店舗にてご確認ください。</p>
            </div>
        </div>
    </section>

    <section>
        <div class="yafuso_sns_029">
            <div class="yafuso_single_029">
                <div class="yafuso_sns_mark_029" aria-hidden="true">
                    <i class="fa-brands fa-instagram"></i>
                </div>
                <div class="yafuso_section_title_029 yafuso_section_title_center_029">
                    <h2 class="act txt_split type_popup">SNS・インスタグラム</h2>
                </div>
                <p class="yafuso_sns_lead_029">各店舗の最新情報・新メニュー・イベントはInstagramで発信中。</p>
                <ul class="yafuso_sns_grid_029">
                    <li>
                        <a href="https://www.instagram.com/karaoke.momotaro/" target="_blank" rel="noopener noreferrer">
                            <img class="yafuso_sns_account_icon_029" src="<?php echo $img; ?>/sns_momotaro.webp" alt="カラオケワールドももたろうのInstagramアイコン" loading="lazy">
                            <span>カラオケワールド<br>ももたろう</span>
                            <small>@karaoke.momotaro</small>
                            <b><i class="fa-brands fa-instagram" aria-hidden="true"></i>フォローする</b>
                        </a>
                    </li>
                    <li>
                        <a href="https://www.instagram.com/horumon_okada/" target="_blank" rel="noopener noreferrer">
                            <img class="yafuso_sns_account_icon_029" src="<?php echo $img; ?>/sns_okada.webp" alt="西成ホルモンおか田のInstagramアイコン" loading="lazy">
                            <span>西成ホルモン<br>おか田</span>
                            <small>@horumon_okada</small>
                            <b><i class="fa-brands fa-instagram" aria-hidden="true"></i>フォローする</b>
                        </a>
                    </li>
                    <li>
                        <a href="https://www.instagram.com/tokunobuyatairamen/" target="_blank" rel="noopener noreferrer">
                            <img class="yafuso_sns_account_icon_029" src="<?php echo $img; ?>/sns_tokunobu.webp" alt="屋台ラーメンとくのぶのInstagramアイコン" loading="lazy">
                            <span>屋台ラーメン<br>とくのぶ</span>
                            <small>@tokunobuyatairamen</small>
                            <b><i class="fa-brands fa-instagram" aria-hidden="true"></i>フォローする</b>
                        </a>
                    </li>
                    <li>
                        <a href="https://www.instagram.com/ta_tan0301/" target="_blank" rel="noopener noreferrer">
                            <img class="yafuso_sns_account_icon_029" src="<?php echo $img; ?>/sns_tatan.webp" alt="居酒屋たーたんのInstagramアイコン" loading="lazy">
                            <span>居酒屋<br>たーたん</span>
                            <small>@ta_tan0301</small>
                            <b><i class="fa-brands fa-instagram" aria-hidden="true"></i>フォローする</b>
                        </a>
                    </li>
                    <li>
                        <a href="https://www.instagram.com/tebachu.tebasaki/" target="_blank" rel="noopener noreferrer">
                            <img class="yafuso_sns_account_icon_029" src="<?php echo $img; ?>/sns_tebachu.webp" alt="手羽先居酒屋てばちゅ〜のInstagramアイコン" loading="lazy">
                            <span>手羽先居酒屋<br>てばちゅ〜</span>
                            <small>@tebachu.tebasaki</small>
                            <b><i class="fa-brands fa-instagram" aria-hidden="true"></i>フォローする</b>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </section>
</main>

<?php include_once './footer.php'; ?>
