(function () {
  const body = document.body;
  const slider = document.getElementById('products_slider');
  if (!slider) return;

  const mask = slider.querySelector('.products_slider__mask');
  const track = slider.querySelector('[data-slider-track]');
  const prevBtn = slider.querySelector('[data-direction="prev"]');
  const nextBtn = slider.querySelector('[data-direction="next"]');
  if (!mask || !track || !prevBtn || !nextBtn) return;

  const slides = Array.from(track.children);
  if (!slides.length) return;

  let currentIndex = 0;
  let slidesPerView = 1;
  let cardWidth = 0;
  let gap = 0;
  let maxIndex = 0;
  let resizeRaf = null;

  const reducedMotionQuery = window.matchMedia('(prefers-reduced-motion: reduce)');

  const applyMotionPreference = () => {
    track.style.transitionDuration = reducedMotionQuery.matches ? '0ms' : '';
  };

  if (typeof reducedMotionQuery.addEventListener === 'function') {
    reducedMotionQuery.addEventListener('change', applyMotionPreference);
  } else if (typeof reducedMotionQuery.addListener === 'function') {
    reducedMotionQuery.addListener(applyMotionPreference);
  }
  applyMotionPreference();

  const parsePixels = (value) => {
    const number = parseFloat(value);
    return Number.isNaN(number) ? 0 : number;
  };

  const updateControls = () => {
    if (!prevBtn || !nextBtn) return;
    prevBtn.disabled = currentIndex <= 0;
    nextBtn.disabled = currentIndex >= maxIndex;
  };

  const translate = () => {
    const offset = (cardWidth + gap) * currentIndex;
    track.style.transform = `translateX(${-offset}px)`;
  };

  const measure = () => {
    if (!slides.length) return;

    cardWidth = slides[0].getBoundingClientRect().width;
    const trackStyles = window.getComputedStyle(track);
    const gapValue = trackStyles.gap !== 'normal' ? trackStyles.gap : trackStyles.columnGap;
    gap = parsePixels(gapValue);

    const maskStyles = window.getComputedStyle(mask);
    const paddingLeft = parsePixels(maskStyles.paddingLeft);
    const paddingRight = parsePixels(maskStyles.paddingRight);
    const visibleWidth = mask.clientWidth - paddingLeft - paddingRight;

    const denominator = cardWidth + gap;
    slidesPerView = denominator > 0 ? Math.max(1, Math.floor((visibleWidth + gap) / denominator)) : 1;
    maxIndex = Math.max(0, slides.length - slidesPerView);
    if (currentIndex > maxIndex) {
      currentIndex = maxIndex;
    }

    translate();
    updateControls();
  };

  const goToNext = () => {
    if (currentIndex >= maxIndex) return;
    currentIndex += 1;
    translate();
    updateControls();
  };

  const goToPrev = () => {
    if (currentIndex <= 0) return;
    currentIndex -= 1;
    translate();
    updateControls();
  };

  nextBtn.addEventListener('click', goToNext);
  prevBtn.addEventListener('click', goToPrev);

  window.addEventListener('resize', () => {
    if (resizeRaf) {
      window.cancelAnimationFrame(resizeRaf);
    }
    resizeRaf = window.requestAnimationFrame(measure);
  });

  window.addEventListener('load', measure);
  measure();

  // Modal logic
  const modalRoot = document.querySelector('[data-modal]');
  const modalContent = modalRoot ? modalRoot.querySelector('[data-modal-content]') : null;
  const modalCloseTargets = modalRoot ? modalRoot.querySelectorAll('[data-modal-close]') : [];
  const modalTriggers = slider.querySelectorAll('[data-modal-trigger]');
  const modalDismissButton = modalRoot ? modalRoot.querySelector('.products-modal__dismiss') : null;
  let lastFocusedElement = null;
  let hideTimer = null;

  const isModalVisible = () => modalRoot ? modalRoot.classList.contains('is-visible') : false;

  const finalizeModalClose = () => {
    if (!modalRoot) return;
    modalRoot.setAttribute('hidden', '');
    modalRoot.classList.remove('is-visible');
    if (body) {
      body.classList.remove('products-modal-open');
    }
    if (modalContent) {
      modalContent.replaceChildren();
    }
    if (lastFocusedElement && typeof lastFocusedElement.focus === 'function') {
      lastFocusedElement.focus();
    }
    lastFocusedElement = null;
  };

  const closeModal = () => {
    if (!modalRoot || !isModalVisible()) return;

    modalRoot.classList.remove('is-visible');

    if (hideTimer) {
      window.clearTimeout(hideTimer);
    }

    hideTimer = window.setTimeout(() => {
      finalizeModalClose();
      hideTimer = null;
    }, 320);
  };

  const openModal = (templateId) => {
    if (!modalRoot || !modalContent || !templateId) return;
    const template = document.getElementById(templateId);
    if (!(template instanceof HTMLTemplateElement)) return;

    if (hideTimer) {
      window.clearTimeout(hideTimer);
      hideTimer = null;
    }

    modalContent.replaceChildren(template.content.cloneNode(true));
    modalRoot.removeAttribute('hidden');
    modalRoot.scrollTop = 0;
    if (body) {
      body.classList.add('products-modal-open');
    }
    lastFocusedElement = document.activeElement instanceof HTMLElement ? document.activeElement : null;

    window.requestAnimationFrame(() => {
      modalRoot.classList.add('is-visible');
      if (modalDismissButton instanceof HTMLElement) {
        modalDismissButton.focus();
      }
    });
  };

  modalTriggers.forEach((trigger) => {
    trigger.addEventListener('click', (event) => {
      const target = event.currentTarget;
      if (!(target instanceof HTMLElement)) return;
      const templateId = target.getAttribute('data-modal-trigger');
      if (templateId) {
        openModal(templateId);
      }
    });
  });

  modalCloseTargets.forEach((closeEl) => {
    closeEl.addEventListener('click', () => {
      closeModal();
    });
  });

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && isModalVisible()) {
      event.preventDefault();
      closeModal();
    }
  });

  if (modalRoot) {
    modalRoot.addEventListener('mousedown', (event) => {
      if (event.target instanceof HTMLElement && event.target.hasAttribute('data-modal-close')) {
        event.preventDefault();
      }
    });

    modalRoot.addEventListener('transitionend', (event) => {
      if (event.target === modalRoot && !modalRoot.classList.contains('is-visible')) {
        if (hideTimer) {
          window.clearTimeout(hideTimer);
          hideTimer = null;
        }
        finalizeModalClose();
      }
    });
  }
})();
