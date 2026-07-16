function initFullSliders() {
    document.querySelectorAll(".slider_fullslider").forEach(function (slider) {
        if (slider.dataset.fullsliderInitialized === "true") {
            return;
        }

        const slides = Array.from(slider.children).filter(function (child) {
            return child.classList.contains("slide");
        });

        if (slides.length === 0) {
            return;
        }

        slider.dataset.fullsliderInitialized = "true";

        let currentIndex = slides.findIndex(function (slide) {
            return slide.classList.contains("active");
        });

        if (currentIndex < 0) {
            currentIndex = 0;
        }

        function getSlideImage(index) {
            const slide = slides[index];
            return slide ? slide.querySelector("img") : null;
        }

        function preloadSlide(index) {
            const img = getSlideImage(index);

            if (!img || img.dataset.fullsliderPreloaded === "true") {
                return;
            }

            img.dataset.fullsliderPreloaded = "true";
            img.loading = "eager";
            img.decoding = "async";

            const preload = new Image();
            preload.decoding = "async";
            preload.src = img.currentSrc || img.src;
        }

        slides.forEach(function (slide, index) {
            const img = getSlideImage(index);

            if (img) {
                img.decoding = "async";

                if (index === currentIndex) {
                    img.loading = "eager";
                    img.fetchPriority = "high";
                }
            }

            slide.classList.toggle("active", index === currentIndex);
        });

        if (slides.length < 2) {
            return;
        }

        const waitTicks = slides.map(function () {
            return 0;
        });
        const maxWaitTicks = 2;

        preloadSlide(currentIndex);
        preloadSlide((currentIndex + 1) % slides.length);

        const interval = Number.parseInt(slider.dataset.interval, 10) || 5000;

        function isReady(img) {
            return !img || (img.complete && img.naturalWidth > 0);
        }

        function isBroken(img) {
            return img && img.complete && img.naturalWidth === 0;
        }

        function findNextIndex() {
            for (let offset = 1; offset < slides.length; offset += 1) {
                const index = (currentIndex + offset) % slides.length;
                const img = getSlideImage(index);

                preloadSlide(index);

                if (isBroken(img)) {
                    waitTicks[index] = 0;
                    continue;
                }

                if (isReady(img)) {
                    waitTicks[index] = 0;
                    return index;
                }

                if (waitTicks[index] < maxWaitTicks) {
                    waitTicks[index] += 1;
                    return null;
                }

                waitTicks[index] = 0;
            }

            return null;
        }

        setInterval(function () {
            const nextIndex = findNextIndex();

            if (nextIndex === null) {
                return;
            }

            const previousIndex = currentIndex;
            currentIndex = nextIndex;
            slides[previousIndex].classList.remove("active");
            slides[currentIndex].classList.add("active");
            preloadSlide((currentIndex + 1) % slides.length);
        }, interval);
    });
}

if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initFullSliders);
} else {
    initFullSliders();
}
