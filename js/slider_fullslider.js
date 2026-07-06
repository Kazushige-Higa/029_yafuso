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

        slides.forEach(function (slide, index) {
            slide.classList.toggle("active", index === currentIndex);
        });

        if (slides.length < 2) {
            return;
        }

        const interval = Number.parseInt(slider.dataset.interval, 10) || 5000;

        setInterval(function () {
            const previousIndex = currentIndex;
            currentIndex = (currentIndex + 1) % slides.length;
            slides[previousIndex].classList.remove("active");
            slides[currentIndex].classList.add("active");
        }, interval);
    });
}

if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initFullSliders);
} else {
    initFullSliders();
}
