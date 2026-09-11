<?php
require_once './common.php';

$page_title = "屋富祖とは";
$page_meta_title = "屋富祖とは｜やふそ屋台村 ちょうちん横丁";
$page_meta_description = "徒歩約10分、約500mの通りに戦後から続く物語が息づく浦添市・屋富祖。懐かしさと新しさが同居する街の歴史と楽しみ方をご紹介します。";
$page_meta_image = $img . "/about_yafuso_ogp.jpg";
$page_script = '<script src="' . htmlspecialchars(yafuso_url('/js/slider_fullslider.js'), ENT_QUOTES, 'UTF-8') . '" defer></script>';
$use_yafuso_layout = true;
?>
<?php include_once './header.php'; ?>

<main>
<div class="yafuso_about_page_029">
    <section>
    <div class="yafuso_about_hero_029">
        <div class="yafuso_about_hero_photo_029 slider_fullslider_wrap" aria-hidden="true">
            <ul class="slider_fullslider yafuso_about_hero_slider_029">
                <li class="slide active">
                    <img decoding="async" src="<?php echo $img; ?>/about_yafuso_hero.webp" alt="" loading="eager" fetchpriority="high">
                </li>
                <li class="slide">
                    <img decoding="async" src="<?php echo $img; ?>/about_yafuso_walk_03.webp" alt="" loading="lazy">
                </li>
                <li class="slide">
                    <img decoding="async" src="<?php echo $img; ?>/about_yafuso_walk_05.webp" alt="" loading="lazy">
                </li>
                <li class="slide">
                    <img decoding="async" src="<?php echo $img; ?>/about_yafuso_generations.webp" alt="" loading="lazy">
                </li>
            </ul>
        </div>
        <div class="yafuso_about_hero_inner_029">
            <div class="yafuso_about_hero_label_029">
                <img src="<?php echo $img; ?>/chouchin.webp" alt="" aria-hidden="true">
                <span>ABOUT YAFUSO</span>
                <strong>屋富祖とは</strong>
            </div>
            <h1>
                <span class="yafuso_about_hero_title_line_029">10分で歩く通り、</span>
                <span class="yafuso_about_hero_title_line_029">物語は<span class="yellow">80年</span>を超えて。</span>
            </h1>
            <p>ディープな沖縄に出会う場所。</p>
        </div>
    </div>
    </section>

    <section>
    <div id="about_yafuso_town" class="yafuso_about_town_029">
        <div class="yafuso_about_single_029">
            <div class="yafuso_about_section_head_029">
                <span class="yafuso_about_number_029">01</span>
                <div>
                    <small>THE TOWN</small>
                    <h2>屋富祖というまち</h2>
                </div>
            </div>

            <div class="yafuso_about_town_grid_029">
                <div class="yafuso_about_prose_029">
                    <p>那覇空港から国道58号を北へ約10km。屋富祖交差点から延びる約500mの通りと、その周辺に広がるまちが「屋富祖（やふそ）」です。</p>
                    <p>端から端まで歩いても、およそ10分。大きな繁華街ではありませんが、この通りには、戦後から現在まで受け継がれてきた人々の営みと、観光地ではなかなか出会えない沖縄の日常が詰まっています。</p>
                </div>
                <figure class="yafuso_about_town_photo_029">
                    <img decoding="async" src="<?php echo $img; ?>/about_yafuso_access_map.webp" alt="那覇空港から屋富祖まで約10kmの位置関係を示す案内図" loading="lazy">
                    <figcaption>那覇空港から屋富祖まで約10km</figcaption>
                </figure>
            </div>

            <ul class="yafuso_about_facts_029" aria-label="屋富祖の3つの数字">
                <li>
                    <i class="fa-solid fa-plane-arrival" aria-hidden="true"></i>
                    <span>那覇空港から</span>
                    <strong>約<b>10</b>km</strong>
                </li>
                <li>
                    <i class="fa-solid fa-road" aria-hidden="true"></i>
                    <span>通りの長さ</span>
                    <strong>約<b>500</b>m</strong>
                </li>
                <li>
                    <i class="fa-solid fa-person-walking" aria-hidden="true"></i>
                    <span>端から端まで</span>
                    <strong>徒歩約<b>10</b>分</strong>
                </li>
            </ul>
        </div>
    </div>
    </section>

    <section>
    <div class="yafuso_about_history_029">
        <div class="yafuso_about_single_029">
            <div class="yafuso_about_section_head_029 yafuso_about_section_head_light_029">
                <span class="yafuso_about_number_029">02</span>
                <div>
                    <small>HISTORY</small>
                    <h2>地域の人々に育てられた通り</h2>
                </div>
            </div>

            <div class="yafuso_about_history_lead_029">
                <figure>
                    <div class="yafuso_about_image_frame_029">
                        <img decoding="async" src="<?php echo $img; ?>/about_yafuso_history_1959.webp" alt="1959年頃の屋富祖大通り。通りの両側に商店が並び、右手に食堂、糸満屋、宮里食堂が見える" loading="lazy">
                    </div>
                    <figcaption>
                        1959年頃の屋富祖大通り<br>
                        出典：<a href="https://www2.archives.pref.okinawa.jp/opa/OPA600_RESULT_BUNSYO.aspx?cont_cd=A000009983&amp;src_keyword=&amp;keyword_hit=&amp;lang=jp" target="_blank" rel="noopener noreferrer">『屋富祖戦後写真集』（浦添市屋富祖自治会）</a>
                    </figcaption>
                </figure>
                <div>
                    <p>屋富祖大通りの始まりは、戦前の集落にあった細く曲がりくねった道を、戦後、米軍がブルドーザーで拡幅したことにさかのぼります。</p>
                    <p>周辺に軍関係施設が整備され、多くの働く人々が行き交うようになると、1950年ごろから食料品店や日用品店などが次々と開店。沖縄各地から活力ある商売人が集まり、屋富祖は商業と娯楽のまちとして発展していきました。</p>
                </div>
            </div>

            <div class="yafuso_about_history_note_029">
                <i class="fa-solid fa-hands-holding-circle" aria-hidden="true"></i>
                <p>まだ道が舗装されていなかった頃には、道路整備の費用を地域の商店主たちが出し合ったという逸話も残っています。<br>屋富祖は、商売をする人、暮らす人、通りを利用する人たちが、自らの手で育ててきたまちです。</p>
            </div>
        </div>
    </div>
    </section>

    <section>
    <div class="yafuso_about_generations_029">
        <div class="yafuso_about_single_029">
            <div class="yafuso_about_section_head_029">
                <span class="yafuso_about_number_029">03</span>
                <div>
                    <small>THEN &amp; NOW</small>
                    <h2>懐かしさと新しさの同居する町</h2>
                </div>
            </div>

            <div class="yafuso_about_generations_story_029">
                <div class="yafuso_about_generations_prose_029">
                    <p>時代や生活様式の変化とともに、一時は、にぎわいから遠ざかった時期もありましたが、<br>昔ながらの町並みや、人と人との距離の近さは失われませんでした。</p>
                    <p>近年では、屋富祖に残るレトロな雰囲気に魅力を感じた若い世代が新しい店を開き、昔から続く店と新しい感性が自然に交わり始めています。</p>
                    <p>新しい店や個性的な空間が少しずつ増えていく、屋富祖には、昔の面影を残しながら、新しい表情を育て続ける面白さがあります。</p>
                </div>
                <figure class="yafuso_about_generations_photo_029">
                    <img decoding="async" src="<?php echo $img; ?>/about_yafuso_generations.webp" alt="古い建物を生かした店で世代を超えて会話を楽しむ人々を描いた生成イメージ" loading="lazy">
                    <span>新しい感性が交わる</span>
                    <figcaption class="yafuso_about_image_note_029">※イメージ画像</figcaption>
                </figure>
            </div>
        </div>
    </div>
    </section>

    <section>
    <div class="yafuso_about_walk_029">
        <div class="yafuso_about_single_029">
            <div class="yafuso_about_section_head_029">
                <span class="yafuso_about_number_029">04</span>
                <div>
                    <small>WANDER</small>
                    <h2>気の向くままに楽しめる町</h2>
                </div>
            </div>

            <div class="yafuso_about_walk_story_029">
                <figure class="yafuso_about_walk_photo_029">
                    <img decoding="async" src="<?php echo $img; ?>/about_yafuso_walk_01.webp" alt="提灯が灯る屋富祖の通りを歩く人々を描いた生成イメージ" loading="lazy">
                    <figcaption class="yafuso_about_image_note_029">※イメージ画像</figcaption>
                </figure>
                <div class="yafuso_about_walk_prose_029">
                    <p>屋富祖の楽しみ方は、最初から一軒の目的地を決めることだけではありません。</p>
                    <p>大通りを歩き、気になった店をのぞいてみる。建物の2階に目を向けたり、通りから一本入った路地を歩いたりする。</p>
                    <p>店の人や居合わせたお客さんにおすすめを聞き、次の一軒へ向かうといった、予定を決めすぎない過ごし方もできる町です。</p>
                    <p>初めて訪れた人も、いつの間にか会話の輪に加われる場所です。</p>
                    <p>屋富祖には地域の人が食事をし、酒を酌み交わし、歌い、語り合う、昔ながらの沖縄の日常があります。</p>
                </div>
            </div>
        </div>
    </div>
    </section>

    <section>
    <div class="yafuso_about_closing_029">
        <div class="yafuso_about_closing_photo_029" aria-hidden="true">
            <img decoding="async" src="<?php echo $img; ?>/about_yafuso_hero.webp" alt="" loading="lazy">
        </div>
        <div class="yafuso_about_closing_inner_029">
            <p>歩いて10分ほどの小さな通りを、少し時間をかけて歩いてみてください。</p>
            <h2>懐かしくて新しい屋富祖で、<br>自分好みの名店を見つけてみませんか？</h2>
            <a href="<?= htmlspecialchars(yafuso_url('/market_stalls.php'), ENT_QUOTES, 'UTF-8') ?>">
                屋台のご紹介を見る
                <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
            </a>
        </div>
    </div>
    </section>
</div>
</main>

<?php include_once './footer.php'; ?>
