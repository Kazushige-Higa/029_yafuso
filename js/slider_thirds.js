(() => {
  const SELECTOR = '.slider_thirds[data-slider="thirds"]';

  const debounce = (fn, delay = 140) => {
    let timer = null;
    return (...args) => {
      if (timer) clearTimeout(timer);
      timer = setTimeout(() => fn(...args), delay);
    };
  };

  const initSlider = (root) => {
    const track = root.querySelector(".slider_thirds__track");
    const viewport = root.querySelector(".slider_thirds__viewport");
    const dotsHolder = root.querySelector(".slider_thirds__dots");
    const toggle = root.querySelector(".slider_thirds__toggle");
    const originals = Array.from(
      root.querySelectorAll(".slider_thirds__slide")
    );

    if (!track || !viewport || !dotsHolder || !originals.length) return;

    // Clone edges for seamless loop (head-tail)
    const prependClone = originals[originals.length - 1].cloneNode(true);
    prependClone.dataset.clone = "head";
    const appendClone = originals[0].cloneNode(true);
    appendClone.dataset.clone = "tail";
    track.insertBefore(prependClone, originals[0]);
    track.appendChild(appendClone);

    const slides = Array.from(track.children);
    const logicalCount = originals.length;

    let position = 1; // physical index within slides (with clones)
    let isLocked = false;
    let autoTimer = null;
    let slideWidth = 0;
    let gap = 0;

    let touchStartX = null;
    let touchStartY = null;

    let mouseStartX = null;
    let mouseStartY = null;
    let isMouseDown = false;

    const transitionValue = "transform 0.4s cubic-bezier(0.45, 0, 0.2, 1)";

    let viewportContentWidth = 0;

    const readViewportMetrics = () => {
      const rect = viewport.getBoundingClientRect();
      const styles = getComputedStyle(viewport);
      const paddingLeft = parseFloat(styles.paddingLeft || "0") || 0;
      const paddingRight = parseFloat(styles.paddingRight || "0") || 0;
      const width = viewport.clientWidth || rect.width;
      viewportContentWidth = Math.max(width - paddingLeft - paddingRight, 0);
    };

    const readSlideWidth = () => {
      const slide = slides[position] || slides[1] || slides[0];
      if (!slide) return 0;
      return (
        slide.offsetWidth ||
        parseFloat(getComputedStyle(slide).width) ||
        slide.getBoundingClientRect().width ||
        0
      );
    };

    const applyTransform = (offset) => {
      track.style.transform = `translate3d(${offset}px, 0, 0)`;
    };

    const readTranslateX = () => {
      const transform = getComputedStyle(track).transform;
      if (!transform || transform === "none") return 0;
      const match = transform.match(/matrix(3d)?\((.+)\)/);
      if (!match) return 0;
      const values = match[2].split(",").map((value) => parseFloat(value));
      return match[1] ? values[12] || 0 : values[4] || 0;
    };

    const recenterFromRects = (opts = { instant: true }) => {
      const activeSlide = slides[position];
      if (!activeSlide) return false;
      const viewportRect = viewport.getBoundingClientRect();
      const slideRect = activeSlide.getBoundingClientRect();
      const viewportCenter = viewportRect.left + viewportRect.width / 2;
      const slideCenter = slideRect.left + slideRect.width / 2;
      const delta = viewportCenter - slideCenter;
      if (Math.abs(delta) < 0.5) return false;
      const current = readTranslateX();
      const target = current + delta;
      if (opts.instant) {
        track.style.transition = "none";
        applyTransform(target);
        track.getBoundingClientRect();
        track.style.transition = transitionValue;
      } else {
        applyTransform(target);
      }
      return true;
    };

    const logicalFromPosition = (pos) =>
      (pos - 1 + logicalCount) % logicalCount;

    const buildDots = () => {
      dotsHolder.innerHTML = "";
      originals.forEach((_, idx) => {
        const dot = document.createElement("button");
        dot.type = "button";
        dot.addEventListener("click", () => {
          if (isLocked) return;
          position = idx + 1; // compensate for prepended clone
          update();
          restart();
        });
        dotsHolder.appendChild(dot);
      });
    };

    const updateDots = () => {
      dotsHolder.querySelectorAll("button").forEach((dot, idx) => {
        dot.classList.toggle(
          "is-active",
          idx === logicalFromPosition(position)
        );
      });
    };

    // ✅ 安定版 update：instant 時に「reflow + 2回rAF」+ 「is-jumping」で浮きアニメ抑制
    const update = (opts = { instant: false }) => {
      const instant = Boolean(opts.instant);

      readViewportMetrics();

      const activeSlide = slides[position];
      let offset = 0;

      if (activeSlide) {
        const activeWidth = activeSlide.offsetWidth || readSlideWidth();
        offset =
          -activeSlide.offsetLeft + (viewportContentWidth - activeWidth) / 2;
      } else {
        if (!slideWidth) {
          slideWidth = readSlideWidth();
        }
        const centerOffset = (viewportContentWidth - slideWidth) / 2;
        offset = centerOffset - position * (slideWidth + gap);
      }

      // is-activeの付け替えは毎回行う（ただし instant 中は CSS 側で transition を切る）
      slides.forEach((slide, idx) => {
        slide.classList.toggle("is-active", idx === position);
      });
      updateDots();

      if (instant) {
        // ★ 瞬間リセット中だけ、slideのtransition/animationを無効化するためのフラグ
        root.classList.add("is-jumping");

        // ① transition を切る
        track.style.transition = "none";
        isLocked = true;

        // ② transform を適用（瞬間移動）
        applyTransform(offset);

        // ③ 強制 reflow で確定（重要）
        track.getBoundingClientRect();

        // ④ 次のフレーム以降で transition を戻す（2回rAFが安定）
        requestAnimationFrame(() => {
          requestAnimationFrame(() => {
            track.style.transition = transitionValue;
            isLocked = false;

            // ★ 念押し：解除を次フレームに送るとさらに安定
            requestAnimationFrame(() => {
              root.classList.remove("is-jumping");
            });
          });
        });
      } else {
        track.style.transition = transitionValue;
        applyTransform(offset);
        isLocked = true; // transition 中はロック
      }
    };

    const next = () => {
      if (isLocked) return;
      position += 1;
      update();
    };

    const prev = () => {
      if (isLocked) return;
      position -= 1;
      update();
    };

    const updateToggleUI = () => {
      const playing = Boolean(autoTimer);
      toggle?.classList.toggle("is-paused", !playing);
      if (toggle) {
        toggle.setAttribute("aria-label", playing ? "ストップ" : "スタート");
      }
    };

    const start = () => {
      if (autoTimer) return;
      autoTimer = window.setInterval(next, 4800);
      updateToggleUI();
    };

    const stop = () => {
      if (!autoTimer) return;
      window.clearInterval(autoTimer);
      autoTimer = null;
      updateToggleUI();
    };

    const restart = () => {
      stop();
      start();
    };

    // 初期化
    buildDots();
    update({ instant: true });
    start();
    updateToggleUI();

    toggle?.addEventListener("click", () => {
      if (autoTimer) stop();
      else start();
    });

    root.addEventListener("mouseenter", stop);
    root.addEventListener("mouseleave", start);

    // --- Touch swipe ---
    const touchThreshold = 40;

    const handleTouchStart = (event) => {
      if (event.touches.length !== 1) return;
      const touch = event.touches[0];
      touchStartX = touch.clientX;
      touchStartY = touch.clientY;
      stop();
    };

    const handleTouchMove = (event) => {
      if (touchStartX === null || touchStartY === null) return;
      const touch = event.touches[0];
      const dx = touch.clientX - touchStartX;
      const dy = Math.abs(touch.clientY - touchStartY);

      // 横優勢なら縦スクロールを止める
      if (Math.abs(dx) > dy && Math.abs(dx) > 10) {
        event.preventDefault();
      }
    };

    const handleTouchEnd = (event) => {
      if (touchStartX === null || touchStartY === null) return;
      const touch = event.changedTouches[0];
      const dx = touch.clientX - touchStartX;
      const dy = Math.abs(touch.clientY - touchStartY);

      if (Math.abs(dx) > touchThreshold && Math.abs(dx) > dy) {
        if (dx < 0) next();
        else prev();
      }

      touchStartX = null;
      touchStartY = null;
      restart();
    };

    viewport.addEventListener("touchstart", handleTouchStart, {
      passive: true,
    });
    viewport.addEventListener("touchmove", handleTouchMove, { passive: false });
    viewport.addEventListener("touchend", handleTouchEnd, { passive: true });

    // --- Mouse drag ---
    const handleMouseDown = (event) => {
      if (event.button !== 0) return;
      isMouseDown = true;
      mouseStartX = event.clientX;
      mouseStartY = event.clientY;
      stop();
    };

    const handleMouseMove = (event) => {
      if (!isMouseDown || mouseStartX === null || mouseStartY === null) return;
      const dx = event.clientX - mouseStartX;
      const dy = Math.abs(event.clientY - mouseStartY);

      if (Math.abs(dx) > dy && Math.abs(dx) > 10) {
        event.preventDefault();
      }
    };

    const handleMouseUp = (event) => {
      if (!isMouseDown || mouseStartX === null || mouseStartY === null) return;

      const dx = event.clientX - mouseStartX;
      const dy = Math.abs(event.clientY - mouseStartY);

      if (Math.abs(dx) > touchThreshold && Math.abs(dx) > dy) {
        if (dx < 0) next();
        else prev();
      }

      isMouseDown = false;
      mouseStartX = null;
      mouseStartY = null;
      restart();
    };

    viewport.addEventListener("mousedown", handleMouseDown);
    viewport.addEventListener("mousemove", handleMouseMove);
    viewport.addEventListener("mouseup", handleMouseUp);
    viewport.addEventListener("mouseleave", handleMouseUp);

    // transitionend を transform の時だけ拾う（多重発火対策）
    track.addEventListener("transitionend", (e) => {
      if (e.propertyName !== "transform") return;

      const currentSlide = slides[position];

      // クローンに到達したら瞬間移動で本物へ
      if (currentSlide?.dataset.clone === "tail") {
        position = 1; // 最初の本物
        update({ instant: true });
        return;
      }

      if (currentSlide?.dataset.clone === "head") {
        position = logicalCount; // 最後の本物
        update({ instant: true });
        return;
      }

      // 通常の遷移が終わったらロック解除
      isLocked = false;
      recenterFromRects();
    });

    const recalc = () => {
      const styles = getComputedStyle(track);
      gap = parseFloat(styles.columnGap || styles.gap || "0") || 0;
      readViewportMetrics();
      slideWidth = readSlideWidth();

      update({ instant: true });
      recenterFromRects();
    };

    let recalcQueued = false;
    const scheduleRecalc = () => {
      if (recalcQueued) return;
      recalcQueued = true;
      requestAnimationFrame(() => {
        requestAnimationFrame(() => {
          recalcQueued = false;
          recalc();
        });
      });
    };

    let lastWindowWidth = window.innerWidth;
    const handleResize = debounce(() => {
      if (window.innerWidth === lastWindowWidth) return;
      lastWindowWidth = window.innerWidth;
      scheduleRecalc();
    }, 80);

    window.addEventListener("resize", handleResize);
    window.addEventListener("orientationchange", handleResize);
    window.addEventListener("pageshow", handleResize);
    window.addEventListener("load", scheduleRecalc);

    scheduleRecalc();
  };

  document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(SELECTOR).forEach(initSlider);
  });
})();
