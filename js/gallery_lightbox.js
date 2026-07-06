// デバッグ情報表示用
function updateDebugInfo(message) {
    const debugElement = document.getElementById('debugInfo');
    if (debugElement) {
        debugElement.innerHTML += '<br>' + message;
    }
    console.log(message);
}

// DOMが完全に読み込まれるまで待つ
document.addEventListener('DOMContentLoaded', function () {
    updateDebugInfo('DOM読み込み完了');

    // 少し待ってから初期化
    setTimeout(() => {
        try {
            const lightbox = new LightboxSystem();
            updateDebugInfo('LightboxSystem初期化成功');
        } catch (error) {
            updateDebugInfo('エラー: ' + error.message);
        }
    }, 100);
});

class LightboxSystem {
    constructor() {
        updateDebugInfo('LightboxSystemコンストラクタ実行');

        // 要素の取得
        this.lightbox = document.getElementById('lightbox');
        this.lightboxImage = document.getElementById('lightboxImage');
        this.lightboxContent = this.lightbox ? this.lightbox.querySelector('.lightbox-content') : null;
        this.lightboxVideo = null;
        this.mediaContainer = null;
        this.prevBtn = document.getElementById('prevBtn');
        this.nextBtn = document.getElementById('nextBtn');
        this.closeBtn = document.getElementById('closeBtn');
        this.imageCounter = document.getElementById('imageCounter');

        // 各ul.galleryごとに処理を分ける
        this.galleries = Array.from(document.querySelectorAll('ul.gallery'));
        this.galleryItemData = []; // それぞれのulごとに配列で画像データ管理
        this.galleryItemsFlat = []; // すべてのgallery-itemの参照
        this.galleryRefs = []; // 各galleryごとの{ul, items, images, imageData}を持つ

        // インデックス制御
        this.currentGalleryIndex = 0;
        this.currentIndex = 0;

        // 各ulごとの画像データとDOMを集める
        this.galleries.forEach((ul, galleryIdx) => {
            const items = Array.from(ul.querySelectorAll('.gallery-item'));
            const data = [];
            this.galleryRefs.push({
                ul,
                items,
                mediaData: data
            });

            // クリックイベント付与用にflatリストも維持
            items.forEach(item => {
                const img = item.querySelector('img');
                const video = item.querySelector('video');

                if (img) {
                    data.push({
                        type: 'image',
                        src: img.src,
                        alt: img.alt || `画像 ${data.length + 1}`,
                        poster: img.src,
                        element: img
                    });
                } else if (video) {
                    const poster = video.getAttribute('poster') || '';
                    data.push({
                        type: 'video',
                        src: video.getAttribute('src') || '',
                        alt:
                            video.getAttribute('alt') ||
                            video.getAttribute('aria-label') ||
                            `動画 ${data.length + 1}`,
                        poster,
                        element: video
                    });
                }

                const mediaIdx = data.length - 1;

                if (mediaIdx >= 0) {
                    this.galleryItemsFlat.push({
                        item,
                        mediaElement: data[mediaIdx].element || null,
                        galleryIdx,
                        mediaIdx
                    });
                }
            });
        });

        updateDebugInfo('ギャラリー数: ' + this.galleries.length);
        updateDebugInfo('全ギャラリーアイテム数: ' + this.galleryItemsFlat.length);

        this.imageData = []; // これを使わずgalleryRefsから管理する
        // 要素が存在するかチェック
        if (!this.lightbox || !this.lightboxImage || !this.lightboxContent) {
            updateDebugInfo('エラー: 必要な要素が見つかりません');
            return;
        }

        this.setupLightboxMediaElements();
        this.init();
    }

    setupLightboxMediaElements() {
        // 画像と動画をまとめるコンテナを用意
        this.mediaContainer = document.createElement('div');
        this.mediaContainer.className = 'lightbox-media';

        // 元の画像をコンテナに移動
        this.lightboxContent.insertBefore(this.mediaContainer, this.lightboxImage);
        this.mediaContainer.appendChild(this.lightboxImage);

        // 動画要素を追加
        this.lightboxVideo = document.createElement('video');
        this.lightboxVideo.className = 'lightbox-video';
        this.lightboxVideo.controls = true;
        this.lightboxVideo.playsInline = true;
        this.lightboxVideo.setAttribute('webkit-playsinline', '');
        this.lightboxVideo.style.display = 'none';
        this.mediaContainer.appendChild(this.lightboxVideo);
    }

    init() {
        updateDebugInfo('初期化開始');

        // イベントリスナーを設定
        this.setupEventListeners();

        updateDebugInfo('初期化完了');
    }

    setupEventListeners() {
        updateDebugInfo('イベントリスナー設定開始');

        // 各gallery-item(ul.galleryごと)にクリックイベントを設定
        this.galleryItemsFlat.forEach((ref, flatIdx) => {
            updateDebugInfo(`ギャラリー#${ref.galleryIdx + 1} アイテム${ref.mediaIdx + 1}にクリックイベント設定`);

            ref.item.addEventListener('click', (e) => {
                updateDebugInfo(`ギャラリー#${ref.galleryIdx + 1} アイテム${ref.mediaIdx + 1}がクリックされました`);
                e.preventDefault();
                e.stopPropagation();
                this.openLightbox(ref.galleryIdx, ref.mediaIdx);
            });

            // img自体にも同じイベント
            if (ref.mediaElement) {
                ref.mediaElement.addEventListener('click', (e) => {
                    updateDebugInfo(`ギャラリー#${ref.galleryIdx + 1} 画像${ref.mediaIdx + 1}がクリックされました`);
                    e.preventDefault();
                    e.stopPropagation();
                    this.openLightbox(ref.galleryIdx, ref.mediaIdx);
                });
            }
        });

        // Lightboxのコントロール
        if (this.closeBtn) {
            this.closeBtn.addEventListener('click', (e) => {
                updateDebugInfo('閉じるボタンクリック');
                e.stopPropagation();
                this.closeLightbox();
            });
        }

        if (this.prevBtn) {
            this.prevBtn.addEventListener('click', (e) => {
                updateDebugInfo('前の画像ボタンクリック');
                e.stopPropagation();
                this.showPrevImage();
            });
        }

        if (this.nextBtn) {
            this.nextBtn.addEventListener('click', (e) => {
                updateDebugInfo('次の画像ボタンクリック');
                e.stopPropagation();
                this.showNextImage();
            });
        }

        // Lightboxの背景をクリックで閉じる
        this.lightbox.addEventListener('click', (e) => {
            if (e.target === this.lightbox) {
                updateDebugInfo('背景クリック');
                this.closeLightbox();
            }
        });

        // キーボードイベント
        document.addEventListener('keydown', (e) => {
            if (this.lightbox.classList.contains('active')) {
                switch (e.key) {
                    case 'Escape':
                        updateDebugInfo('ESCキー押下');
                        this.closeLightbox();
                        break;
                    case 'ArrowLeft':
                        updateDebugInfo('左矢印キー押下');
                        this.showPrevImage();
                        break;
                    case 'ArrowRight':
                        updateDebugInfo('右矢印キー押下');
                        this.showNextImage();
                        break;
                }
            }
        });

        updateDebugInfo('イベントリスナー設定完了');
    }

    openLightbox(galleryIdx, imageIdx) {
        updateDebugInfo(`Lightboxを開く - ギャラリー: ${galleryIdx}, インデックス: ${imageIdx}`);

        this.currentGalleryIndex = galleryIdx;
        this.currentIndex = imageIdx;
        this.updateLightboxImage();
        this.lightbox.classList.add('active');
        document.body.style.overflow = 'hidden';

        updateDebugInfo('Lightbox表示完了');
    }

    closeLightbox() {
        updateDebugInfo('Lightboxを閉じる');

        this.lightbox.classList.remove('active');
        this.stopVideoPlayback();
        document.body.style.overflow = 'auto';
    }

    showPrevImage() {
        const gallery = this.galleryRefs[this.currentGalleryIndex];
        const len = gallery && gallery.mediaData ? gallery.mediaData.length : 0;
        if (!len) return;
        this.currentIndex = (this.currentIndex - 1 + len) % len;
        this.updateLightboxImage();
        updateDebugInfo(`前の画像表示: ${this.currentIndex}`);
    }

    showNextImage() {
        const gallery = this.galleryRefs[this.currentGalleryIndex];
        const len = gallery && gallery.mediaData ? gallery.mediaData.length : 0;
        if (!len) return;
        this.currentIndex = (this.currentIndex + 1) % len;
        this.updateLightboxImage();
        updateDebugInfo(`次の画像表示: ${this.currentIndex}`);
    }

    updateLightboxImage() {
        const gallery = this.galleryRefs[this.currentGalleryIndex];
        if (!gallery) return;
        const currentMedia = gallery.mediaData[this.currentIndex];
        if (!currentMedia) return;

        if (this.imageCounter) {
            this.imageCounter.textContent = `${this.currentIndex + 1} / ${gallery.mediaData.length}`;
        }

        // 画像の切り替えアニメーション
        this.lightboxImage.style.opacity = '0';
        if (this.lightboxVideo) {
            this.lightboxVideo.style.opacity = '0';
        }

        if (currentMedia.type === 'video') {
            // 動画表示
            this.lightboxImage.style.display = 'none';
            if (this.lightboxVideo) {
                this.lightboxVideo.style.display = 'block';
                this.lightboxVideo.src = currentMedia.src;
                if (currentMedia.poster) {
                    this.lightboxVideo.poster = currentMedia.poster;
                } else {
                    this.lightboxVideo.removeAttribute('poster');
                }
                this.lightboxVideo.currentTime = 0;
                this.lightboxVideo.play().catch(() => {
                    // 自動再生がブロックされた場合は無視
                });
            }
        } else {
            // 画像表示
            this.stopVideoPlayback();
            this.lightboxImage.style.display = 'block';
            this.lightboxImage.src = currentMedia.src;
            this.lightboxImage.alt = currentMedia.alt;
            if (this.lightboxVideo) {
                this.lightboxVideo.style.display = 'none';
                this.lightboxVideo.removeAttribute('src');
                this.lightboxVideo.load();
            }
        }

        setTimeout(() => {
            if (currentMedia.type === 'video' && this.lightboxVideo) {
                this.lightboxVideo.style.opacity = '1';
            } else {
                this.lightboxImage.style.opacity = '1';
            }
        }, 100);

        updateDebugInfo(
            `画像更新（ギャラリー${this.currentGalleryIndex + 1}）: ${this.currentIndex + 1}/${gallery.mediaData.length}`
        );
    }

    stopVideoPlayback() {
        if (this.lightboxVideo) {
            this.lightboxVideo.pause();
            this.lightboxVideo.currentTime = 0;
        }
    }
}
