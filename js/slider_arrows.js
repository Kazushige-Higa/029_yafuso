(function () {
    const sliderStates = [];

    document.addEventListener("DOMContentLoaded", () => {
        document.querySelectorAll('.slider_arrows').forEach((slider) => {
            const state = initSlider(slider);
            sliderStates.push(state);
        });

        window.addEventListener("resize", () => {
            sliderStates.forEach((state) => {
                updateSlideParams(state);
                updateSlidePosition(state);
                if (state.maxIndex === 0) {
                    clearInterval(state.autoSlideInterval);
                    state.autoSlideInterval = null;
                } else {
                    resetAutoSlide(state);
                }
            });
        });
    });

    function initSlider(slider) {
        const state = {
            slider,
            currentIndex: 0,
            autoSlideInterval: null,
            slidesToShow: 1,
            maxIndex: 0,
            slidesContainer: slider.querySelector('.arrows_slides'),
            slides: slider.querySelectorAll('.arrows_list'),
            pager: slider.querySelector('.pager'),
            prevButton: slider.querySelector('.arrow.prev'),
            nextButton: slider.querySelector('.arrow.next'),
        };

        updateSlideParams(state);
        setInitialPosition(state);
        createPager(state);
        updatePager(state);
        startAutoSlide(state);

        state.prevButton?.addEventListener('click', () => {
            prevSlide(state);
            resetAutoSlide(state);
        });
        state.nextButton?.addEventListener('click', () => {
            nextSlide(state);
            resetAutoSlide(state);
        });

        return state;
    }

    function updateSlideParams(state) {
        state.slidesToShow = window.innerWidth <= 599 ? 1 : 3;
        state.maxIndex = Math.max(state.slides.length - state.slidesToShow, 0);
        state.currentIndex = Math.min(state.currentIndex, state.maxIndex);

        if (state.maxIndex === 0 && state.autoSlideInterval) {
            clearInterval(state.autoSlideInterval);
            state.autoSlideInterval = null;
        }
    }

    function setInitialPosition(state) {
        state.currentIndex = 0;
        updateSlidePosition(state);
    }

    function updateSlidePosition(state) {
        if (!state.slidesContainer) return;
        const percent = (100 / state.slidesToShow) * state.currentIndex;
        state.slidesContainer.style.transform = `translateX(-${percent}%)`;
        updatePager(state);
    }

    function nextSlide(state) {
        if (state.maxIndex === 0) return;
        state.currentIndex = (state.currentIndex + 1) % (state.maxIndex + 1);
        updateSlidePosition(state);
    }

    function prevSlide(state) {
        if (state.maxIndex === 0) return;
        state.currentIndex = (state.currentIndex - 1 + (state.maxIndex + 1)) % (state.maxIndex + 1);
        updateSlidePosition(state);
    }

    function createPager(state) {
        if (!state.pager) return;
        state.pager.innerHTML = '';
        for (let i = 0; i <= state.maxIndex; i++) {
            const button = document.createElement('button');
            button.addEventListener('click', () => {
                state.currentIndex = i;
                updateSlidePosition(state);
                resetAutoSlide(state);
            });
            state.pager.appendChild(button);
        }
    }

    function updatePager(state) {
        if (!state.pager) return;
        const pagerButtons = state.pager.querySelectorAll('button');
        pagerButtons.forEach((button, index) => {
            button.classList.toggle('active', index === state.currentIndex);
        });
    }

    function startAutoSlide(state) {
        if (state.maxIndex === 0) return;
        state.autoSlideInterval = setInterval(() => {
            nextSlide(state);
        }, 5000);
    }

    function resetAutoSlide(state) {
        if (state.autoSlideInterval) {
            clearInterval(state.autoSlideInterval);
        }
        startAutoSlide(state);
    }
})();