<?php
require_once './common.php';

$page_title = "出店をご検討の方へ";
$page_meta_title = "出店をご検討の方へ｜やふそ屋台村 ちょうちん横丁";
$page_meta_description = "やふそ屋台村 ちょうちん横丁の貸し屋台区画、出店メリット、設備、契約条件、申し込み情報をご案内します。";
$page_meta_image = $img . "/vendors_4_1.webp";
$page_style = "";
$use_yafuso_layout = true;
$page_script = <<<'HTML'
<script src="js/gallery_thumbnail.js" defer></script>
<script>
    (() => {
        const initYafusoRequiredForm = function() {
            const form = document.querySelector('[data-yafuso-required-form]');
            if (!form) {
                return;
            }
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
        };

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initYafusoRequiredForm);
        } else {
            initYafusoRequiredForm();
        }
    })();
</script>
HTML;
?>
<?php include_once './header.php'; ?>

<main>
    <!-- 出店をご検討の方へ vendors.php -->
    <div class='overflow'>
        <div id="vendors_page" class="yafuso_vendors_029">
            <section>
                <div class="yafuso_vendors_hero_029">
                    <div class="yafuso_vendors_hero_photo_029" aria-hidden="true">
                        <img src="<?php echo $img; ?>/vendors_4_1.webp" alt="出店向け貸し屋台区画の内観" loading="eager">
                    </div>
                    <div class="yafuso_single_029">
                        <div class="yafuso_vendors_hero_inner_029">
                            <p class="yafuso_vendors_kicker_029">TENANT RECRUIT</p>
                            <h2>出店をご検討の方へ</h2>
                            <p class="yafuso_vendors_copy_029">あなたのお店を、<br>ちょうちん横丁で始めませんか。</p>
                            <p class="yafuso_vendors_intro_029">設備はすでに揃っています。お客さんは横丁全体で共有できます。<br>
                            隣には仲間がいます。初期費用を抑えながら、地域に根ざしたファンを最短で育てられる環境が、屋富祖のこの場所に待っています。</p>
                            <div class="yafuso_vendors_hero_actions_029">
                                <a href="#vendors_rooms"><i class="fa-solid fa-store" aria-hidden="true"></i>貸し屋台を見る</a>
                                <a href="#vendors_contact_form"><i class="fa-solid fa-envelope" aria-hidden="true"></i>申し込みへ進む</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section>
                <div class="yafuso_vendors_message_029">
                    <div class="yafuso_single_029">
                        <div class="yafuso_vendors_message_grid_029">
                            <div class="yafuso_vendors_message_text_029">
                                <span class="yafuso_lower_lantern_icon_029" aria-hidden="true">灯</span>
                                <h3>単なる貸し店舗ではなく、<br>共に育つ横丁の土壌。</h3>
                                <p>店主同士がお客様を共有し、共に成長していく「相互集客」の土壌がある場所です。<br>
                                あなたが繁盛すれば横丁全体が盛り上がり、横丁が盛り上がればあなたのお店にも人が来る。そんな好循環の中に、あなたのお店を加えてみませんか。</p>
                            </div>
                            <div class="yafuso_vendors_message_panel_029">
                                <strong>同じ志を持つ仲間が、<br>今日もちょうちん横丁で<br class="pconly">灯を灯しています。</strong>
                                <ul>
                                    <li><i class="fa-solid fa-circle-check" aria-hidden="true"></i>設備完備で始めやすい</li>
                                    <li><i class="fa-solid fa-circle-check" aria-hidden="true"></i>横丁全体で認知されやすい</li>
                                    <li><i class="fa-solid fa-circle-check" aria-hidden="true"></i>店主同士で支え合える</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section>
                <div class="yafuso_vendors_merit_029">
                    <div class="yafuso_single_029">
                        <div class="yafuso_vendors_title_029">
                            <span aria-hidden="true"></span>
                            <h2>出店のメリット</h2>
                            <p>開業前の不安を減らし、屋富祖の地でファンを育てるための環境が整っています。</p>
                        </div>
                        <div class="yafuso_vendors_merit_grid_029">
                            <article>
                                <figure><img src="<?php echo $img; ?>/vendors_8_6.webp" alt="厨房設備が整った貸し屋台" loading="lazy"></figure>
                                <h3>初期費用が抑えられる</h3>
                                <p>コンロ・シンク・テーブル・カウンター・イス・照明など主要設備がすでに完備。<br>
                                    内装コストをかけず、すぐに営業を開始できます。</p>
                            </article>
                            <article>
                                <figure><img src="<?php echo $img; ?>/karaoke_generated_party.webp" alt="にぎわう屋台村の相互集客イメージ" loading="lazy"></figure>
                                <h3>相互集客で安定した来客</h3>
                                <p>屋台村全体のお客様が自然とあなたのお店にも流れてくる横丁スタイル。<br>
                                    開業直後から認知されやすい環境です。</p>
                            </article>
                            <article>
                                <figure><img src="<?php echo $img; ?>/hero_scene.webp" alt="駐車場100台完備の駐車場" loading="lazy"></figure>
                                <h3>駐車場100台完備</h3>
                                <p>国道58号沿いという好立地に加え、お客様専用駐車場を100台完備しています。<br>
                                    車での来訪も安心です。</p>
                            </article>
                            <article>
                                <figure><img src="<?php echo $img; ?>/concept_scene.webp" alt="屋内型で営業しやすい横丁空間" loading="lazy"></figure>
                                <h3>屋内型で通年営業しやすい</h3>
                                <p>天候に左右されない屋内環境のため、<br>
                                    沖縄の夏や雨季も安定して営業できます。</p>
                            </article>
                            <article>
                                <figure><img src="<?php echo $img; ?>/vendors_1_4.webp" alt="横丁内で仲間と営業できる貸し屋台区画" loading="lazy"></figure>
                                <h3>孤独な経営にならない</h3>
                                <p>隣には同じ想いを持つ店主仲間がいます。<br>
                                    情報交換や助け合いができる横丁ならではのコミュニティがあります。</p>
                            </article>
                        </div>
                    </div>
                </div>
            </section>

            <section>
                <div id="vendors_rooms" class="yafuso_vendors_rooms_029">
                    <div class="yafuso_single_029">
                        <div class="yafuso_vendors_title_029">
                            <span aria-hidden="true"></span>
                            <h2>貸し屋台のご案内</h2>
                            <p>丸真本社ビル内の3区画をご紹介します。<br>
                                各区画の画像はサムネイルから切り替えてご確認ください。</p>
                        </div>

                        <article class="yafuso_vendor_room_029">
                            <div class="yafuso_vendor_room_head_029">
                                <span>丸真本社ビル 8号</span>
                                <h3>8号室｜12.96㎡（3.92坪）</h3>
                                <p>コンパクトながら使いやすい間取り。一人でも切り盛りしやすいサイズ感で、<br class="pconly">こだわりの一品料理や専門店スタイルの出店にも向いています。</p>
                            </div>
                            <div class="yafuso_vendor_room_body_029">
                                <div class="yafuso_vendor_room_gallery_029">
                                    <div class="gallery_thumbnail yafuso_vendor_gallery_029" data-gallery-id="vendors_room_8_gallery">
                                        <div class="main-gallery">
                                            <div class="main-slide cover">
                                                <img src="<?php echo $img; ?>/vendors_8_1.webp" alt="丸真本社ビル8号の外観" class="active">
                                                <div class="overflow">
                                                    <img src="<?php echo $img; ?>/vendors_8_2.webp" alt="丸真本社ビル8号の外観写真">
                                                    <img src="<?php echo $img; ?>/vendors_8_3.webp" alt="丸真本社ビル8号の入口写真">
                                                    <img src="<?php echo $img; ?>/vendors_8_4.webp" alt="丸真本社ビル8号の屋台区画">
                                                    <img src="<?php echo $img; ?>/vendors_8_5.webp" alt="丸真本社ビル8号の客席">
                                                    <img src="<?php echo $img; ?>/vendors_8_6.webp" alt="丸真本社ビル8号の厨房設備">
                                                    <img src="<?php echo $img; ?>/vendors_8_7.webp" alt="丸真本社ビル8号のシンク設備">
                                                    <img src="<?php echo $img; ?>/vendors_8_8.webp" alt="丸真本社ビル8号のカウンター">
                                                    <img src="<?php echo $img; ?>/vendors_8_9.webp" alt="丸真本社ビル8号の照明設備">
                                                    <img src="<?php echo $img; ?>/vendors_8_10.webp" alt="丸真本社ビル8号の店舗内写真">
                                                    <img src="<?php echo $img; ?>/vendors_8_11.webp" alt="丸真本社ビル8号の共用部">
                                                    <img src="<?php echo $img; ?>/vendors_8_12.webp" alt="丸真本社ビル8号の区画写真">
                                                </div>
                                                <div class="gallery-nav">
                                                    <button class="nav-button prev" type="button" aria-label="前の写真">&#10094;</button>
                                                    <button class="nav-button next" type="button" aria-label="次の写真">&#10095;</button>
                                                </div>
                                            </div>
                                            <div class="thumbnail-gallery-container">
                                                <div class='space_1 space_sp1'></div>
                                                <div class="thumbnail-gallery">
                                                    <div class="thumbnail active"><img src="<?php echo $img; ?>/vendors_8_1.webp" alt="8号外観のサムネイル"></div>
                                                    <div class="thumbnail"><img src="<?php echo $img; ?>/vendors_8_2.webp" alt="8号外観写真のサムネイル"></div>
                                                    <div class="thumbnail"><img src="<?php echo $img; ?>/vendors_8_3.webp" alt="8号入口写真のサムネイル"></div>
                                                    <div class="thumbnail"><img src="<?php echo $img; ?>/vendors_8_4.webp" alt="8号屋台区画のサムネイル"></div>
                                                    <div class="thumbnail"><img src="<?php echo $img; ?>/vendors_8_5.webp" alt="8号客席のサムネイル"></div>
                                                    <div class="thumbnail"><img src="<?php echo $img; ?>/vendors_8_6.webp" alt="8号厨房設備のサムネイル"></div>
                                                    <div class="thumbnail"><img src="<?php echo $img; ?>/vendors_8_7.webp" alt="8号シンク設備のサムネイル"></div>
                                                    <div class="thumbnail"><img src="<?php echo $img; ?>/vendors_8_8.webp" alt="8号カウンターのサムネイル"></div>
                                                    <div class="thumbnail"><img src="<?php echo $img; ?>/vendors_8_9.webp" alt="8号照明設備のサムネイル"></div>
                                                    <div class="thumbnail"><img src="<?php echo $img; ?>/vendors_8_10.webp" alt="8号店舗内写真のサムネイル"></div>
                                                    <div class="thumbnail"><img src="<?php echo $img; ?>/vendors_8_11.webp" alt="8号共用部のサムネイル"></div>
                                                    <div class="thumbnail"><img src="<?php echo $img; ?>/vendors_8_12.webp" alt="8号区画写真のサムネイル"></div>
                                                </div>
                                                <div class="thumbnail-nav">
                                                    <button class="thumb-button prev" type="button" aria-label="前のサムネイル">&#10094;</button>
                                                    <button class="thumb-button next" type="button" aria-label="次のサムネイル">&#10095;</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="yafuso_vendor_room_info_029">
                                    <dl>
                                        <div>
                                            <dt>床面積</dt>
                                            <dd>12.96㎡ / 3.92坪</dd>
                                        </div>
                                        <div>
                                            <dt>家賃</dt>
                                            <dd>59,620円（税込）</dd>
                                        </div>
                                        <div>
                                            <dt>共益費</dt>
                                            <dd>13,750円</dd>
                                        </div>
                                        <div>
                                            <dt>敷金 / 礼金</dt>
                                            <dd>2ヶ月 / なし</dd>
                                        </div>
                                        <div>
                                            <dt>保証金</dt>
                                            <dd>1ヶ月</dd>
                                        </div>
                                        <div>
                                            <dt>仲介手数料</dt>
                                            <dd>1ヶ月</dd>
                                        </div>
                                        <div>
                                            <dt>備考</dt>
                                            <dd>家賃・共益費は消費税込み。毎月別途光熱費あり</dd>
                                        </div>
                                    </dl>
                                    <a href="https://www.e-uchina.net/bukken/jigyo/c-7389-3251030-0434/panorama.html?i=0" target="_blank" rel="noopener noreferrer">パノラマ内覧を見る</a>
                                </div>
                            </div>
                        </article>

                        <article class="yafuso_vendor_room_029">
                            <div class="yafuso_vendor_room_head_029">
                                <span>丸真本社ビル 1号</span>
                                <h3>1号室｜22.41㎡（6.78坪）</h3>
                                <p>横丁内でも広めのゆとりある区画。座席数を確保したい方や、<br class="pconly">厨房を広く使いたいメニュー構成にも対応できます。</p>
                            </div>
                            <div class="yafuso_vendor_room_body_029">
                                <div class="yafuso_vendor_room_gallery_029">
                                    <div class="gallery_thumbnail yafuso_vendor_gallery_029" data-gallery-id="vendors_room_1_gallery">
                                        <div class="main-gallery">
                                            <div class="main-slide cover">
                                                <img src="<?php echo $img; ?>/vendors_1_1.webp" alt="丸真本社ビル1号の外観" class="active">
                                                <div class="overflow">
                                                    <img src="<?php echo $img; ?>/vendors_1_2.webp" alt="丸真本社ビル1号の外観写真">
                                                    <img src="<?php echo $img; ?>/vendors_1_3.webp" alt="丸真本社ビル1号の入口写真">
                                                    <img src="<?php echo $img; ?>/vendors_1_4.webp" alt="丸真本社ビル1号の屋台区画">
                                                    <img src="<?php echo $img; ?>/vendors_1_5.webp" alt="丸真本社ビル1号の厨房設備">
                                                    <img src="<?php echo $img; ?>/vendors_1_6.webp" alt="丸真本社ビル1号のシンク設備">
                                                    <img src="<?php echo $img; ?>/vendors_1_7.webp" alt="丸真本社ビル1号のカウンター">
                                                    <img src="<?php echo $img; ?>/vendors_1_8.webp" alt="丸真本社ビル1号の店舗内写真">
                                                    <img src="<?php echo $img; ?>/vendors_1_9.webp" alt="丸真本社ビル1号の共用部">
                                                    <img src="<?php echo $img; ?>/vendors_1_10.webp" alt="丸真本社ビル1号の区画写真">
                                                </div>
                                                <div class="gallery-nav">
                                                    <button class="nav-button prev" type="button" aria-label="前の写真">&#10094;</button>
                                                    <button class="nav-button next" type="button" aria-label="次の写真">&#10095;</button>
                                                </div>
                                            </div>
                                            <div class="thumbnail-gallery-container">
                                                <div class='space_1 space_sp1'></div>
                                                <div class="thumbnail-gallery">
                                                    <div class="thumbnail active"><img src="<?php echo $img; ?>/vendors_1_1.webp" alt="1号外観のサムネイル"></div>
                                                    <div class="thumbnail"><img src="<?php echo $img; ?>/vendors_1_2.webp" alt="1号外観写真のサムネイル"></div>
                                                    <div class="thumbnail"><img src="<?php echo $img; ?>/vendors_1_3.webp" alt="1号入口写真のサムネイル"></div>
                                                    <div class="thumbnail"><img src="<?php echo $img; ?>/vendors_1_4.webp" alt="1号屋台区画のサムネイル"></div>
                                                    <div class="thumbnail"><img src="<?php echo $img; ?>/vendors_1_5.webp" alt="1号厨房設備のサムネイル"></div>
                                                    <div class="thumbnail"><img src="<?php echo $img; ?>/vendors_1_6.webp" alt="1号シンク設備のサムネイル"></div>
                                                    <div class="thumbnail"><img src="<?php echo $img; ?>/vendors_1_7.webp" alt="1号カウンターのサムネイル"></div>
                                                    <div class="thumbnail"><img src="<?php echo $img; ?>/vendors_1_8.webp" alt="1号店舗内写真のサムネイル"></div>
                                                    <div class="thumbnail"><img src="<?php echo $img; ?>/vendors_1_9.webp" alt="1号共用部のサムネイル"></div>
                                                    <div class="thumbnail"><img src="<?php echo $img; ?>/vendors_1_10.webp" alt="1号区画写真のサムネイル"></div>
                                                </div>
                                                <div class="thumbnail-nav">
                                                    <button class="thumb-button prev" type="button" aria-label="前のサムネイル">&#10094;</button>
                                                    <button class="thumb-button next" type="button" aria-label="次のサムネイル">&#10095;</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="yafuso_vendor_room_info_029">
                                    <dl>
                                        <div>
                                            <dt>床面積</dt>
                                            <dd>22.41㎡ / 6.78坪</dd>
                                        </div>
                                        <div>
                                            <dt>家賃</dt>
                                            <dd>79,970円（税込）</dd>
                                        </div>
                                        <div>
                                            <dt>共益費</dt>
                                            <dd>13,750円</dd>
                                        </div>
                                        <div>
                                            <dt>敷金 / 礼金</dt>
                                            <dd>2ヶ月 / なし</dd>
                                        </div>
                                        <div>
                                            <dt>保証金</dt>
                                            <dd>1ヶ月</dd>
                                        </div>
                                        <div>
                                            <dt>仲介手数料</dt>
                                            <dd>1ヶ月</dd>
                                        </div>
                                        <div>
                                            <dt>備考</dt>
                                            <dd>家賃・共益費は消費税込み。毎月別途光熱費あり</dd>
                                        </div>
                                    </dl>
                                    <a href="https://www.e-uchina.net/bukken/jigyo/c-7389-3221116-0416/panorama.html?i=0" target="_blank" rel="noopener noreferrer">パノラマ内覧を見る</a>
                                </div>
                            </div>
                        </article>

                        <article class="yafuso_vendor_room_029">
                            <div class="yafuso_vendor_room_head_029">
                                <span>丸真本社ビル 4号</span>
                                <h3>4号室｜17㎡（5.14坪）</h3>
                                <p>使いやすい中間サイズ。カウンター中心の一人営業から、テーブルを設けた小グループ対応まで、<br class="pconly">レイアウトの自由度が高い区画です。</p>
                            </div>
                            <div class="yafuso_vendor_room_body_029">
                                <div class="yafuso_vendor_room_gallery_029">
                                    <div class="gallery_thumbnail yafuso_vendor_gallery_029" data-gallery-id="vendors_room_4_gallery">
                                        <div class="main-gallery">
                                            <div class="main-slide cover">
                                                <img src="<?php echo $img; ?>/vendors_4_1.webp" alt="丸真本社ビル4号の屋内屋台区画" class="active">
                                                <div class="overflow">
                                                    <img src="<?php echo $img; ?>/vendors_4_2.webp" alt="丸真本社ビル4号の店舗内写真">
                                                    <img src="<?php echo $img; ?>/vendors_4_3.webp" alt="丸真本社ビル4号のカウンター">
                                                    <img src="<?php echo $img; ?>/vendors_4_4.webp" alt="丸真本社ビル4号の客席">
                                                    <img src="<?php echo $img; ?>/vendors_4_5.webp" alt="丸真本社ビル4号の厨房設備">
                                                    <img src="<?php echo $img; ?>/vendors_4_6.webp" alt="丸真本社ビル4号のシンク設備">
                                                    <img src="<?php echo $img; ?>/vendors_4_7.webp" alt="丸真本社ビル4号の照明設備">
                                                    <img src="<?php echo $img; ?>/vendors_4_8.webp" alt="丸真本社ビル4号の区画写真">
                                                    <img src="<?php echo $img; ?>/vendors_4_9.webp" alt="丸真本社ビル4号の共用部">
                                                    <img src="<?php echo $img; ?>/vendors_4_10.webp" alt="丸真本社ビル4号の店舗写真">
                                                    <img src="<?php echo $img; ?>/vendors_4_11.webp" alt="丸真本社ビル4号の内覧写真">
                                                </div>
                                                <div class="gallery-nav">
                                                    <button class="nav-button prev" type="button" aria-label="前の写真">&#10094;</button>
                                                    <button class="nav-button next" type="button" aria-label="次の写真">&#10095;</button>
                                                </div>
                                            </div>
                                            <div class="thumbnail-gallery-container">
                                                <div class='space_1 space_sp1'></div>
                                                <div class="thumbnail-gallery">
                                                    <div class="thumbnail active"><img src="<?php echo $img; ?>/vendors_4_1.webp" alt="4号屋内屋台区画のサムネイル"></div>
                                                    <div class="thumbnail"><img src="<?php echo $img; ?>/vendors_4_2.webp" alt="4号店舗内写真のサムネイル"></div>
                                                    <div class="thumbnail"><img src="<?php echo $img; ?>/vendors_4_3.webp" alt="4号カウンターのサムネイル"></div>
                                                    <div class="thumbnail"><img src="<?php echo $img; ?>/vendors_4_4.webp" alt="4号客席のサムネイル"></div>
                                                    <div class="thumbnail"><img src="<?php echo $img; ?>/vendors_4_5.webp" alt="4号厨房設備のサムネイル"></div>
                                                    <div class="thumbnail"><img src="<?php echo $img; ?>/vendors_4_6.webp" alt="4号シンク設備のサムネイル"></div>
                                                    <div class="thumbnail"><img src="<?php echo $img; ?>/vendors_4_7.webp" alt="4号照明設備のサムネイル"></div>
                                                    <div class="thumbnail"><img src="<?php echo $img; ?>/vendors_4_8.webp" alt="4号区画写真のサムネイル"></div>
                                                    <div class="thumbnail"><img src="<?php echo $img; ?>/vendors_4_9.webp" alt="4号共用部のサムネイル"></div>
                                                    <div class="thumbnail"><img src="<?php echo $img; ?>/vendors_4_10.webp" alt="4号店舗写真のサムネイル"></div>
                                                    <div class="thumbnail"><img src="<?php echo $img; ?>/vendors_4_11.webp" alt="4号内覧写真のサムネイル"></div>
                                                </div>
                                                <div class="thumbnail-nav">
                                                    <button class="thumb-button prev" type="button" aria-label="前のサムネイル">&#10094;</button>
                                                    <button class="thumb-button next" type="button" aria-label="次のサムネイル">&#10095;</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="yafuso_vendor_room_info_029">
                                    <dl>
                                        <div>
                                            <dt>床面積</dt>
                                            <dd>17㎡ / 5.14坪</dd>
                                        </div>
                                        <div>
                                            <dt>家賃</dt>
                                            <dd>67,540円（税込）</dd>
                                        </div>
                                        <div>
                                            <dt>共益費</dt>
                                            <dd>13,750円</dd>
                                        </div>
                                        <div>
                                            <dt>敷金 / 礼金</dt>
                                            <dd>2ヶ月 / なし</dd>
                                        </div>
                                        <div>
                                            <dt>保証金</dt>
                                            <dd>1ヶ月</dd>
                                        </div>
                                        <div>
                                            <dt>仲介手数料</dt>
                                            <dd>1ヶ月</dd>
                                        </div>
                                        <div>
                                            <dt>備考</dt>
                                            <dd>家賃・共益費は消費税込み。毎月別途光熱費あり</dd>
                                        </div>
                                    </dl>
                                    <a href="https://www.e-uchina.net/bukken/jigyo/c-7389-3250228-0381/panorama.html?i=0" target="_blank" rel="noopener noreferrer">パノラマ内覧を見る</a>
                                </div>
                            </div>
                        </article>
                    </div>
                </div>
            </section>

            <section>
                <div class="yafuso_vendors_conditions_029">
                    <div class="yafuso_single_029">
                        <div class="yafuso_vendors_condition_grid_029">
                            <div class="yafuso_vendors_condition_card_029">
                                <h2>設備・条件について</h2>
                                <h3>共通設備</h3>
                                <ul>
                                    <li>所在階：1階（2階建）</li>
                                    <li>冷房（クーラー）完備・換気口あり</li>
                                    <li>男女別トイレ・インターネット対応</li>
                                    <li>三層シンク・コンロ（プロパンガス）・給湯設備あり</li>
                                    <li>厨房設備・敷地内ゴミ置き場・看板取付スペースあり</li>
                                    <li>店主専用1〜2台無料・お客様専用駐車場多数あり</li>
                                </ul>
                            </div>
                            <div class="yafuso_vendors_condition_card_029">
                                <h2>契約条件</h2>
                                <dl>
                                    <div>
                                        <dt>契約期間</dt>
                                        <dd>2年更新</dd>
                                    </div>
                                    <div>
                                        <dt>更新料</dt>
                                        <dd>11,000円（税込）</dd>
                                    </div>
                                    <div>
                                        <dt>保証会社</dt>
                                        <dd>加入必須（家賃保証会社・火災保険）</dd>
                                    </div>
                                    <div>
                                        <dt>入居時期</dt>
                                        <dd>要相談</dd>
                                    </div>
                                </dl>
                                <div class="yafuso_vendors_note_029">
                                    <i class="fa-solid fa-circle-info" aria-hidden="true"></i>
                                    <p>各区画とも家賃・共益費は消費税込みです。<br>
                                        毎月別途光熱費が発生します。</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section>
                <div class="yafuso_vendors_rules_029">
                    <div class="yafuso_single_029">
                        <div class="yafuso_vendors_title_029">
                            <span aria-hidden="true"></span>
                            <h2>出店に伴うルールについて</h2>
                            <p>出店者全員で横丁の雰囲気と価値を守ることを大切にしています。</p>
                        </div>
                        <div class="yafuso_vendors_rule_grid_029">
                            <article>
                                <h3>デザイン・外装</h3>
                                <p>統一した雰囲気を損なわないデザインであること。<br>
                                    看板・装飾等は事前に運営会社へご相談ください。</p>
                            </article>
                            <article>
                                <h3>営業時間</h3>
                                <p>横丁の営業時間（火〜日 18:00〜2:00）に準じた営業を基本とします。<br>
                                    <span class="orange">日中の営業もご希望の方はお気軽にご相談ください。</span>
                                </p>
                            </article>
                            <article>
                                <h3>食品営業許可</h3>
                                <p>飲食物を提供される場合は、食品衛生法に基づく営業許可を取得のうえ営業を開始してください。</p>
                            </article>
                            <article>
                                <h3>光熱費</h3>
                                <p>お客様専用スペースの冷房に関わる光熱費は、自店舗分とは別途算出となります。</p>
                            </article>
                            <article>
                                <h3>共用部の清潔維持</h3>
                                <p>通路・トイレ・ゴミ置き場などの共用スペースは、出店者全員で清潔に保ってください。</p>
                            </article>
                            <article>
                                <h3>騒音・近隣への配慮</h3>
                                <p>音楽・呼び込み等による過度な騒音は控え、他の出店者およびお客様の迷惑とならないよう配慮してください。</p>
                            </article>
                            <article>
                                <h3>転貸の禁止</h3>
                                <p>賃借した区画を第三者に転貸することは禁止とします。</p>
                            </article>
                            <article>
                                <h3>火気の取り扱い</h3>
                                <p>コンロ・プロパンガスの使用に際しては、消防法および施設の安全基準を遵守してください。</p>
                            </article>
                            <article>
                                <h3>閉店時の対応</h3>
                                <p>契約解除の際は、原状回復のうえ速やかに明け渡しをお願いいたします。</p>
                            </article>
                        </div>
                    </div>
                </div>
            </section>

            <section>
                <div id="vendors_contact_form" class="yafuso_vendors_contact_029">
                    <div class="yafuso_single_029">
                        <div class="yafuso_vendors_contact_grid_029">
                            <div class="yafuso_vendors_contact_text_029">
                                <span>APPLICATION</span>
                                <h2>お申し込みフォーム</h2>
                                <p>下記の内容をご確認・ご同意のうえ、お問い合わせください。担当者よりご連絡いたします。</p>
                            </div>
                            <form class="yafuso_vendors_form_029" action="#" method="post" data-yafuso-required-form>
                                <label class="yafuso_vendors_agree_029">
                                    <input type="checkbox" name="agree" required>
                                    <span>上記「設備・条件・出店に伴うルールについて」に同意します <b class="yafuso_required_badge_029">必須</b></span>
                                </label>
                                <div class="yafuso_vendors_form_grid_029">
                                    <label>お名前 <b class="yafuso_required_badge_029">必須</b><input type="text" name="name" autocomplete="name" required></label>
                                    <label>メールアドレス <b class="yafuso_required_badge_029">必須</b><input type="email" name="email" autocomplete="email" required></label>
                                    <label>電話番号 <b class="yafuso_required_badge_029">必須</b><input type="tel" name="tel" autocomplete="tel" required></label>
                                    <label>ご希望の区画 <b class="yafuso_required_badge_029">必須</b><select name="room" required>
                                            <option value="" selected disabled>選択してください</option>
                                            <option>8号室</option>
                                            <option>1号室</option>
                                            <option>4号室</option>
                                            <option>未定</option>
                                        </select></label>
                                </div>
                                <label>開業予定のジャンル・メニュー概要 <b class="yafuso_required_badge_029">必須</b><textarea name="genre" rows="4" required></textarea></label>
                                <label>ご質問・備考 <b class="yafuso_required_badge_029">必須</b><textarea name="message" rows="5" required></textarea></label>
                                <button type="submit" disabled>メールを送信する</button>
                                <a href="tel:090-2171-7224" class="yafuso_vendors_phone_029"><i class="fa-solid fa-phone" aria-hidden="true"></i>電話で相談する</a>
                            </form>
                            <div class="yafuso_vendors_company_029">
                                <h3>運営会社</h3>
                                <dl>
                                    <div>
                                        <dt>会社名</dt>
                                        <dd>有限会社 丸真産業</dd>
                                    </div>
                                    <div>
                                        <dt>所在地</dt>
                                        <dd>〒901-2127 沖縄県浦添市屋富祖3-33-6</dd>
                                    </div>
                                </dl>
                                <a href="https://www.marushin-v.com/" target="_blank" rel="noopener noreferrer">マルシングループについて</a>
                                <h3 class="yafuso_vendors_company_subtitle_029">テナント募集・契約に関するお問い合わせ先</h3>
                                <dl>
                                    <div>
                                        <dt>会社名</dt>
                                        <dd>株式会社ネクスフィールド</dd>
                                    </div>
                                    <div>
                                        <dt>担当</dt>
                                        <dd>銘苅</dd>
                                    </div>
                                    <div>
                                        <dt>TEL</dt>
                                        <dd><a href="tel:090-2171-7224">090-2171-7224</a></dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <div class="yafuso_lower_lantern_footer_029" aria-hidden="true"></div>
        </div>
    </div>
    <!-- 出店をご検討の方へ vendors.php -->
</main>

<?php include_once './footer.php'; ?>
