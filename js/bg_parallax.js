// JavaScript による拡張パララックス効果 - モバイル対応版
document.addEventListener('DOMContentLoaded', function () {
    const parallaxElements = document.querySelectorAll('.bg_parallax');
    const isMobile = window.innerWidth <= 768;
    const isTouch = 'ontouchstart' in window;

    // デバイス判定
    const deviceInfo = {
        isMobile: isMobile,
        isTouch: isTouch,
        isIOS: /iPad|iPhone|iPod/.test(navigator.userAgent),
        isAndroid: /Android/.test(navigator.userAgent)
    };

    // 画像の読み込み完了後にパララックス効果を適用
    function initParallax() {
        parallaxElements.forEach((element, index) => {
            element.classList.add('js-enhanced');
        });
    }

    // 画像読み込みチェック
    function checkImagesLoaded() {
        const images = [];
        parallaxElements.forEach(element => {
            const computedStyle = window.getComputedStyle(element, ':before');
            const backgroundImage = computedStyle.backgroundImage;

            if (backgroundImage && backgroundImage !== 'none') {
                const imageUrl = backgroundImage.replace(/^url\(["']?/, '').replace(/["']?\)$/, '');
                if (imageUrl && !imageUrl.includes('gradient')) {
                    images.push(imageUrl);
                }
            }
        });

        // 画像のプリロード
        let loadedImages = 0;
        if (images.length === 0) {
            initParallax();
            return;
        }

        images.forEach(src => {
            const img = new Image();
            img.onload = img.onerror = () => {
                loadedImages++;
                if (loadedImages === images.length) {
                    initParallax();
                }
            };
            img.src = src;
        });
    }

    // モバイル用パララックス効果
    function mobileParallaxScroll() {
        const scrolled = window.pageYOffset;

        parallaxElements.forEach((element, index) => {
            const rect = element.getBoundingClientRect();
            const elementTop = rect.top + window.pageYOffset;

            // 要素が画面内にある場合のみ処理
            if (rect.bottom >= -100 && rect.top <= window.innerHeight + 100) {
                // モバイル用の軽量パララックス効果
                const speed = deviceInfo.isIOS ? 0.1 : 0.2; // iOSでは軽量化
                const yPos = (scrolled - elementTop) * speed;

                // モバイル用CSS変数
                element.style.setProperty('--mobile-parallax-y', `${yPos}px`);
            }
        });
    }

    // デスクトップ用パララックス効果
    function desktopParallaxScroll() {
        const scrolled = window.pageYOffset;

        parallaxElements.forEach((element, index) => {
            const rect = element.getBoundingClientRect();
            const elementTop = rect.top + window.pageYOffset;

            // 要素が画面内にある場合のみ処理
            if (rect.bottom >= 0 && rect.top <= window.innerHeight) {
                // デスクトップ用パララックス速度
                const speed = 0.3 + (index * 0.1);
                const yPos = (scrolled - elementTop) * speed;

                // CSS変数を使用してパララックス効果を適用
                element.style.setProperty('--parallax-y', `${yPos}px`);

                if (element.classList.contains('js-enhanced')) {
                    element.style.setProperty('--transform',
                        `translateY(${yPos}px) translateZ(-1px) scale(2.1)`
                    );
                }
            }
        });
    }

    // デバイスに応じたパララックス関数を選択
    const parallaxScroll = deviceInfo.isMobile ? mobileParallaxScroll : desktopParallaxScroll;

    // CSS変数を使用したスタイル適用
    const dynamicStyle = document.createElement('style');
    dynamicStyle.textContent = `
                .bg_parallax.js-enhanced:before {
                    transform: var(--transform, translateZ(-1px) scale(2));
                }
                
                @media (max-width: 768px) {
                    .bg_parallax.js-enhanced:before {
                        transform: translateY(var(--mobile-parallax-y, 0)) scale(1.1);
                    }
                }
            `;
    document.head.appendChild(dynamicStyle);

    // パフォーマンス最適化のためのthrottle
    let ticking = false;

    function requestTick() {
        if (!ticking) {
            requestAnimationFrame(function () {
                parallaxScroll();
                ticking = false;
            });
            ticking = true;
        }
    }

    // Intersection Observer を使用したパフォーマンス最適化
    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('in-view');
            } else {
                entry.target.classList.remove('in-view');
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: deviceInfo.isMobile ? '100px' : '50px'
    });

    parallaxElements.forEach(element => {
        observer.observe(element);
    });

    // スクロールイベントリスナー
    if (deviceInfo.isMobile) {
        // モバイルでは passive スクロールリスナーを使用
        window.addEventListener('scroll', requestTick, {
            passive: true
        });

        // タッチスクロール開始時の処理
        window.addEventListener('touchstart', function () {
            parallaxElements.forEach(element => {
                element.style.setProperty('--mobile-parallax-y', '0px');
            });
        }, {
            passive: true
        });

        // タッチスクロール終了時の処理
        window.addEventListener('touchend', function () {
            setTimeout(requestTick, 50);
        }, {
            passive: true
        });
    } else {
        window.addEventListener('scroll', requestTick);
    }

    // 初期化
    if (deviceInfo.isMobile) {
        // モバイルでは軽量初期化
        parallaxElements.forEach(element => {
            element.style.setProperty('--mobile-parallax-y', '0px');
        });
    } else {
        // デスクトップでは画像読み込み開始
        checkImagesLoaded();
    }

    // 初期パララックス実行
    parallaxScroll();
});

// リサイズ対応
window.addEventListener('resize', function () {
    const isMobile = window.innerWidth <= 768;
    const parallaxElements = document.querySelectorAll('.bg_parallax');

    if (isMobile) {
        parallaxElements.forEach(element => {
            element.style.setProperty('--mobile-parallax-y', '0px');
        });
    }
});

// アクセシビリティ: motion-reduce対応
if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    const style = document.createElement('style');
    style.textContent = `
                .bg_parallax:before {
                    transform: scale(1.1) !important;
                    background-attachment: scroll !important;
                }
                .bg_parallax.js-enhanced:before {
                    transform: scale(1.1) !important;
                }
                @media (max-width: 768px) {
                    .bg_parallax.js-enhanced:before {
                        transform: scale(1.1) !important;
                    }
                }
            `;
    document.head.appendChild(style);
}
