<?php if (!empty($use_yafuso_layout)) : ?>
    <footer>
        <section>
            <div id="access" class="yafuso_access_029 bg_img type2 bg_black bg_blur">
                <div class="yafuso_single_029">
                    <div class="yafuso_section_title_029">
                        <span aria-hidden="true"></span>
                        <h2 class="act txt_split type_popup">アクセス・営業情報</h2>
                    </div>
                    <div class="yafuso_access_grid_029">
                        <div class="yafuso_map_panel_029">
                            <div class="yafuso_map_embed_029">
                                <?php echo $gmap; ?>
                            </div>
                            <a href="<?php echo htmlspecialchars($maplink, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer">
                                Googleマップを開く
                                <i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i>
                            </a>
                        </div>
                        <div class="yafuso_access_panel_029">
                            <dl>
                                <div>
                                    <dt><i class="fa-solid fa-location-dot" aria-hidden="true"></i>住所</dt>
                                    <dd><?php echo htmlspecialchars($postalCode, ENT_QUOTES, 'UTF-8'); ?> <?php echo htmlspecialchars($address, ENT_QUOTES, 'UTF-8'); ?></dd>
                                </div>
                                <div>
                                    <dt><i class="fa-solid fa-building" aria-hidden="true"></i>目印</dt>
                                    <dd>マルシンV1と同じ敷地内</dd>
                                </div>
                                <div>
                                    <dt><i class="fa-regular fa-clock" aria-hidden="true"></i>営業時間</dt>
                                    <dd>毎週火から日 18:00 - 深夜2:00</dd>
                                </div>
                                <div>
                                    <dt><i class="fa-regular fa-calendar" aria-hidden="true"></i>定休日</dt>
                                    <dd>毎週月曜日</dd>
                                </div>
                                <div>
                                    <dt><i class="fa-solid fa-phone" aria-hidden="true"></i>お問い合わせ</dt>
                                    <dd>総合 098-877-2390 / カラオケ予約 098-879-1055</dd>
                                </div>
                            </dl>
                        </div>
                        <div class="yafuso_parking_panel_029">
                            <figure>
                                <img src="<?php echo $img; ?>/hero_scene.webp" alt="やふそ屋台村ちょうちん横丁の駐車場" loading="lazy">
                            </figure>
                            <h3><i class="fa-solid fa-car" aria-hidden="true"></i>駐車場あり</h3>
                            <p>100台完備。国道58号線沿い、浦添市屋富祖エリアで車でも来店しやすい立地です。</p>
                            <strong>飲酒運転は絶対におやめください。</strong>
                            <p>代行・タクシーのご利用をおすすめします。</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="yafuso_footer_029">
            <a class="yafuso_footer_logo_029 act inup" href="./" aria-label="やふそ屋台村 ちょうちん横丁 トップ">
                <img src="<?php echo $img; ?>/logo.webp" alt="やふそ屋台村 ちょうちん横丁" loading="lazy">
            </a>
            <nav class="yafuso_footer_nav_029" aria-label="フッターナビゲーション">
                <a href="./">トップページ</a>
                <a href="concept.php">コンセプト</a>
                <a href="market_stalls.php">屋台のご紹介</a>
                <a href="karaoke.php">カラオケワールド ももたろう</a>
                <a href="vendors.php">出店をご検討の方へ</a>
            </nav>
            <p>© 2026 有限会社 丸真産業</p>
        </div>
    </footer>
</div>
<?php endif; ?>
<script src="js/javascript.js" defer></script>
<script src="js/bg_parallax.js" defer></script>
<?php echo $page_script ?? ''; ?>
</body>

</html>
