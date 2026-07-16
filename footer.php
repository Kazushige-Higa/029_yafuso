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
                        <div class="yafuso_access_panel_029">
                            <dl>
                                <div>
                                    <dt><i class="fa-solid fa-location-dot" aria-hidden="true"></i>住所</dt>
                                    <dd><?php echo htmlspecialchars($postalCode, ENT_QUOTES, 'UTF-8'); ?> <?php echo htmlspecialchars($address, ENT_QUOTES, 'UTF-8'); ?></dd>
                                </div>
                                <div>
                                    <dt><i class="fa-solid fa-building" aria-hidden="true"></i>目印</dt>
                                    <dd>パチンコ店・マルシンV1横</dd>
                                </div>
                                <div>
                                    <dt><i class="fa-regular fa-clock" aria-hidden="true"></i>営業時間</dt>
                                    <dd>火〜日 18:00〜2:00</dd>
                                </div>
                                <div>
                                    <dt><i class="fa-regular fa-calendar" aria-hidden="true"></i>定休日</dt>
                                    <dd>月曜日</dd>
                                </div>
                                <div>
                                    <dt><i class="fa-solid fa-phone" aria-hidden="true"></i>お問い合わせ</dt>
                                    <dd>屋台村全体に関するご質問　098-877-2390<br>
                                        各店舗に関する個別のご質問は、店舗紹介欄に記載の連絡先・SNSより各施設に直接お問い合わせください。
                                        <div class="yafuso_access_notice_029">
                                            <h3><i class="fa-solid fa-car" aria-hidden="true"></i>駐車場100台完備</h3>
                                            <p>屋台村すぐそばに駐車場100台完備。</p>
                                            <div class="yafuso_drunk_driving_notice_029">
                                                <img src="<?php echo $img; ?>/stop_icon.webp" alt="飲酒運転禁止" loading="lazy">
                                                <div>
                                                    <h4>飲酒運転禁止</h4>
                                                    <p><strong>飲酒運転は絶対におやめください。</strong><br>
                                                        お酒を楽しまれた後は代行・タクシーのご利用をおすすめします。</p>
                                                </div>
                                            </div>
                                        </div>
                                    </dd>
                                </div>
                            </dl>
                        </div>
                        <div class="yafuso_map_panel_029">
                            <div class="yafuso_map_embed_029">
                                <?php echo $gmap; ?>
                            </div>
                            <a href="<?php echo htmlspecialchars($maplink, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer">
                                Googleマップを開く
                                <i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i>
                            </a>
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
