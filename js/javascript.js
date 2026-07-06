// nav_slide_toggle
// 右スライドナビゲーションの制御
document.addEventListener("DOMContentLoaded", function () {
  const toggleBtn = document.getElementById("nav_slide_toggle");
  const navMenu = document.getElementById("nav_slide_right");
  const closeBtn = document.querySelector(".nav_close_btn");
  const overlay = document.querySelector(".nav_overlay");
  const body = document.body;

  if (!toggleBtn || !navMenu || !overlay) {
    return;
  }

  // メニューを開く
  function openMenu() {
    navMenu.classList.add("active");
    body.classList.add("nav_open");
  }

  // メニューを閉じる
  function closeMenu() {
    navMenu.classList.remove("active");
    body.classList.remove("nav_open");
  }

  // ハンバーガーボタンクリック
  toggleBtn.addEventListener("click", function (e) {
    e.preventDefault();
    if (navMenu.classList.contains("active")) {
      closeMenu();
    } else {
      openMenu();
    }
  });

  // 閉じるボタンクリック
  if (closeBtn) {
    closeBtn.addEventListener("click", function (e) {
      e.preventDefault();
      closeMenu();
    });
  }

  // オーバーレイクリック
  overlay.addEventListener("click", function (e) {
    if (e.target === overlay) {
      closeMenu();
    }
  });

  // Escキーで閉じる
  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape" && navMenu.classList.contains("active")) {
      closeMenu();
    }
  });
});

// mainviewの表示順
window.addEventListener("load", function () {
  // 表示タイミングを個別指定（ミリ秒）
  const targets = [
    { className: "act01", delay: 500 },
    { className: "act02", delay: 1000 },
    { className: "act03", delay: 1500 },
    { className: "act04", delay: 3000 },
    { className: "act05", delay: 3000 },
  ];

  targets.forEach(({ className, delay }) => {
    setTimeout(() => {
      document.querySelectorAll(`.${className}`).forEach((el) => {
        el.classList.add("first");
      });
    }, delay);
  });
});

// loading ページ読み込み時クラス変更
document.addEventListener("DOMContentLoaded", function () {
  const spinner = document.getElementById("loading");
  if (!spinner) return;

  const barFill = spinner.querySelector(".loading_bar_fill");
  const percentText = spinner.querySelector(".lorder_time");
  let progress = 0;

  const setProgress = (value) => {
    const safeValue = Math.min(Math.max(value, 0), 100);
    progress = safeValue;
    if (barFill) barFill.style.width = safeValue + "%";
    if (percentText) percentText.textContent = Math.floor(safeValue) + "%";
  };

  // ページの読み込み中はスクロールを無効にする
  document.documentElement.style.overflow = "hidden";
  setProgress(0);

  // 読み込みが完了するまでの疑似進捗
  const fakeTimer = setInterval(() => {
    setProgress(Math.min(progress + Math.random() * 6 + 1, 90));
  }, 80);

  window.addEventListener("load", () => {
    clearInterval(fakeTimer);

    // 読み込み完了後は100%まで加速させてからページを表示
    const finishTimer = setInterval(() => {
      setProgress(progress + 5);
      if (progress >= 100) {
        clearInterval(finishTimer);
        setTimeout(() => {
          spinner.classList.add("loaded");
          document.documentElement.removeAttribute("style");
        }, 300);
      }
    }, 30);
  });
});

// Get the element with class 'commonnav'
var nav = document.querySelector(".commonnav");

// If the element exists, proceed
if (nav) {
  // Clone the element
  var cloneNav = nav.cloneNode(true);

  // Select all the target containers
  var targetContainers = document.querySelectorAll(
    ".sp-nav-list, .footer_navi, .clone_nav, .footer_nav_category, .side_navi",
  );

  // Append the cloned element to each target container
  targetContainers.forEach(function (container) {
    container.appendChild(cloneNav.cloneNode(true));
  });
}

// act scroll animation
document.addEventListener("DOMContentLoaded", function () {
  const reveal = (target) => {
    target.classList.add("on");
  };
  const fastTargets = document.querySelectorAll(".txt_split.act");
  const normalTargets = document.querySelectorAll(".act:not(.txt_split)");

  if (!("IntersectionObserver" in window)) {
    fastTargets.forEach(reveal);
    normalTargets.forEach(reveal);
    return;
  }

  const observerNormal = new IntersectionObserver(
    (entries, observer) => {
      entries.forEach((entry) => {
        const target = entry.target;
        if (entry.isIntersecting) {
          reveal(target);
          observer.unobserve(target);
        }
      });
    },
    {
      threshold: 0.08,
      rootMargin: "0px 0px -6% 0px",
    },
  );

  const observerFast = new IntersectionObserver(
    (entries, observer) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          reveal(entry.target);
          observer.unobserve(entry.target);
        }
      });
    },
    {
      threshold: 0.01,
      rootMargin: "0px 0px -4% 0px",
    },
  );

  fastTargets.forEach((target) => observerFast.observe(target));
  normalTargets.forEach((target) => observerNormal.observe(target));
});

// txt_split
var elements = document.querySelectorAll(".txt_split");

elements.forEach(function (element) {
  var text = element.textContent;
  var textbox = "";

  // Split the text into characters and iterate over each character
  text.split("").forEach(function (t, i) {
    if (t !== " ") {
      const delay = (i * 0.08).toFixed(2); // ← 0.03秒間隔で高速発火
      textbox +=
        '<span style="animation-delay:' + delay + 's;">' + t + "</span>";
    } else {
      textbox += t;
    }
  });

  // Set the HTML content of the element
  element.innerHTML = textbox;
});

// tab
// Handle tab list item clicks
const tabItems = document.querySelectorAll(".tab li");
const tabPanels = document.querySelectorAll(".category_contents.panel");

if (tabItems.length && tabPanels.length) {
  tabItems.forEach(function (tabItem, index) {
    tabItem.addEventListener("click", function () {
      // Hide only the tab panels (avoid touching accordion panels)
      tabPanels.forEach(function (panel) {
        panel.style.display = "none";
      });

      // Show the clicked panel when it exists
      if (tabPanels[index]) {
        tabPanels[index].style.display = "block";
      }

      // Remove 'active' class from all tabs
      tabItems.forEach(function (tab) {
        tab.classList.remove("active");
      });

      // Add 'active' class to the clicked tab
      this.classList.add("active");
    });
  });
}

// Handle tab clicks for is-active and is-show classes
document.querySelectorAll(".tab").forEach(function (tab, index) {
  tab.addEventListener("click", function () {
    // Remove 'is-active' class from all tabs
    document.querySelectorAll(".is-active").forEach(function (activeTab) {
      activeTab.classList.remove("is-active");
    });

    // Add 'is-active' class to the clicked tab
    this.classList.add("is-active");

    // Remove 'is-show' class from all content areas
    document.querySelectorAll(".is-show").forEach(function (showContent) {
      showContent.classList.remove("is-show");
    });

    // Add 'is-show' class to the corresponding content area
    document.querySelectorAll(".content_area")[index].classList.add("is-show");
  });
});

const tabsElems = document.querySelectorAll("[data-tabs]");

if (tabsElems.length > 0) {
  for (let i = 0; i < tabsElems.length; i++) {
    let tab = tabsElems[i];
    let tabBtnElems = tab.querySelectorAll("[data-tab]");
    let tabContentElems = tab.querySelectorAll("[data-tab-content]");
    for (let i = 0; i < tabBtnElems.length; i++) {
      let tabBtn = tabBtnElems[i];
      let tabContent = tabContentElems[i];
      tabBtn.addEventListener("click", (e) => {
        e.preventDefault();
        for (let i = 0; i < tabBtnElems.length; i++) {
          tabBtnElems[i].classList.remove("active");
          tabContentElems[i].classList.remove("active");
        }
        tabBtn.classList.add("active");
        tabContent.classList.add("active");
      });
    }
  }
}

// accordion
var acc = document.getElementsByClassName("open");
var i;
for (i = 0; i < acc.length; i++) {
  acc[i].onclick = function () {
    this.classList.toggle("active");
    var panel = this.nextElementSibling;
    if (panel.style.maxHeight) {
      panel.style.maxHeight = null;
    } else {
      panel.style.maxHeight = panel.scrollHeight + "px";
    }
  };
}

//accordion
var acc = document.getElementsByClassName("accordion-header");
var i;

for (i = 0; i < acc.length; i++) {
  acc[i].onclick = function () {
    this.classList.toggle("active");
    this.nextElementSibling.classList.toggle("show");
  };
}

// nav_top_toggle accordion
document.addEventListener("DOMContentLoaded", function () {
  const navToggles = document.querySelectorAll(".js-nav-toggle");

  if (!navToggles.length) {
    return;
  }

  navToggles.forEach(function (btn) {
    const panelId = btn.getAttribute("aria-controls");
    const panel = document.getElementById(panelId);

    if (!panel) {
      return;
    }

    btn.addEventListener("click", function () {
      const isExpanded = btn.getAttribute("aria-expanded") === "true";
      btn.setAttribute("aria-expanded", (!isExpanded).toString());

      if (!isExpanded) {
        panel.removeAttribute("hidden");
        requestAnimationFrame(function () {
          panel.classList.add("is-open");
        });
      } else {
        panel.classList.remove("is-open");
        const onTransitionEnd = function (event) {
          if (event.propertyName !== "max-height") {
            return;
          }
          if (btn.getAttribute("aria-expanded") === "false") {
            panel.setAttribute("hidden", "");
          }
          panel.removeEventListener("transitionend", onTransitionEnd);
        };
        panel.addEventListener("transitionend", onTransitionEnd);
      }
    });
  });
});

// nav_top_toggle drawer toggle
document.addEventListener("DOMContentLoaded", function () {
  const drawerToggle = document.querySelector(".nav_top_toggle_anchor");
  const drawer = document.getElementById("navTopMenu");

  if (!drawerToggle || !drawer) {
    return;
  }

  drawerToggle.addEventListener("click", function (event) {
    event.preventDefault();

    const isOpen = drawer.classList.toggle("is-open");
    drawerToggle.setAttribute("aria-expanded", isOpen ? "true" : "false");

    if (!isOpen) {
      const openPanels = drawer.querySelectorAll(
        ".nav_top_toggle_panel.is-open",
      );
      openPanels.forEach(function (panel) {
        panel.classList.remove("is-open");
        panel.setAttribute("hidden", "");
        const trigger = drawer.querySelector(
          '[aria-controls="' + panel.id + '"]',
        );
        if (trigger) {
          trigger.setAttribute("aria-expanded", "false");
        }
      });
    }
  });
});

// スクロールイベントのハンドラーを結合する
window.addEventListener("scroll", function () {
  const scrollY = window.pageYOffset;
  const scrollTop =
    document.body.scrollTop || document.documentElement.scrollTop;

  function toggleViewClass(el, condition) {
    if (el) {
      if (condition) {
        el.classList.add("view");
      } else {
        el.classList.remove("view");
      }
    }
  }

  toggleViewClass(document.getElementById("pagetop"), scrollY > 300);
  toggleViewClass(document.getElementById("fixed_area_bottom"), scrollY > 300);
  toggleViewClass(document.getElementById("btnApply"), scrollY > 300);
  toggleViewClass(document.getElementById("fixed_nav"), scrollTop > 300);
  toggleViewClass(document.getElementById("fixed_sp_nav"), scrollTop > 80);
});

// btn_accordion toggle
document.addEventListener("DOMContentLoaded", function () {
  const accordionButtons = document.querySelectorAll(".btn_accordion");

  if (!accordionButtons.length) {
    return;
  }

  accordionButtons.forEach(function (button, index) {
    const content = button.nextElementSibling;
    if (
      !content ||
      !content.classList ||
      !content.classList.contains("btn_accordion_wrap")
    ) {
      return;
    }

    if (!content.id) {
      content.id =
        button.getAttribute("aria-controls") ||
        "btn-accordion-panel-" + (index + 1);
    }
    button.setAttribute("aria-controls", content.id);
    button.setAttribute(
      "aria-expanded",
      button.classList.contains("is-open") ? "true" : "false",
    );

    const setPanelHeight = function (panel, expanded) {
      panel.style.maxHeight = expanded ? panel.scrollHeight + "px" : "0px";
    };

    setPanelHeight(content, button.classList.contains("is-open"));

    button.addEventListener("click", function (event) {
      event.preventDefault();
      const nowOpen = !button.classList.contains("is-open");
      button.classList.toggle("is-open", nowOpen);
      content.classList.toggle("is-open", nowOpen);
      button.setAttribute("aria-expanded", nowOpen ? "true" : "false");
      setPanelHeight(content, nowOpen);
    });

    content.addEventListener("transitionend", function (event) {
      if (
        event.propertyName === "max-height" &&
        content.classList.contains("is-open")
      ) {
        content.style.maxHeight = content.scrollHeight + "px";
      }
    });

    if ("ResizeObserver" in window) {
      const resizeObserver = new ResizeObserver(function () {
        if (content.classList.contains("is-open")) {
          content.style.maxHeight = content.scrollHeight + "px";
        }
      });
      resizeObserver.observe(content);
    }
  });
});

// click後 class追加 view_menu
function toggleViewMenu() {
  let elements = document.getElementsByTagName("body");
  elements[0].classList.toggle("view_menu");
}

// gallery_slider
document.addEventListener("DOMContentLoaded", function () {
  const sliders = document.querySelectorAll(".gallery_slider");

  if (!sliders.length) {
    return;
  }

  const prefersReducedMotion = window.matchMedia(
    "(prefers-reduced-motion: reduce)",
  );
  const sliderStates = [];

  const getDirectLists = function (slider) {
    return Array.from(slider.children).filter(function (child) {
      return child.tagName === "UL";
    });
  };

  const setCloneAccessibility = function (list) {
    list.setAttribute("aria-hidden", "true");
    list.querySelectorAll("img").forEach(function (img) {
      img.setAttribute("alt", "");
    });
  };

  const buildSlider = function (state) {
    const lists = getDirectLists(state.slider);
    if (!lists.length) {
      return;
    }

    const sourceList = lists[0];

    if (!sourceList.children.length) {
      return;
    }

    if (!state.originalMarkup) {
      state.originalMarkup = sourceList.innerHTML;
    }

    lists.slice(1).forEach(function (list) {
      list.remove();
    });

    sourceList.innerHTML = state.originalMarkup;
    sourceList.removeAttribute("aria-hidden");

    state.slider.classList.add("is-enhanced");
    state.slider.style.setProperty("--gallery-slider-offset", "0px");

    const loopWidth = Math.round(sourceList.getBoundingClientRect().width);
    const sliderWidth = Math.round(state.slider.getBoundingClientRect().width);

    if (!loopWidth || !sliderWidth) {
      return;
    }

    const cloneCount = Math.max(2, Math.ceil(sliderWidth / loopWidth) + 2);

    for (let i = 1; i < cloneCount; i++) {
      const clone = sourceList.cloneNode(true);
      setCloneAccessibility(clone);
      state.slider.appendChild(clone);
    }

    state.loopWidth = loopWidth;
    state.offset = state.direction > 0 ? -loopWidth : 0;
    state.slider.style.setProperty(
      "--gallery-slider-offset",
      state.offset + "px",
    );
  };

  const animateSlider = function (state, now) {
    if (prefersReducedMotion.matches || !state.loopWidth) {
      state.animationFrameId = null;
      return;
    }

    if (state.lastTimestamp === null) {
      state.lastTimestamp = now;
    }

    const deltaSeconds = (now - state.lastTimestamp) / 1000;
    state.lastTimestamp = now;
    state.offset += state.direction * state.speed * deltaSeconds;

    if (state.direction < 0 && state.offset <= -state.loopWidth) {
      state.offset += state.loopWidth;
    } else if (state.direction > 0 && state.offset >= 0) {
      state.offset -= state.loopWidth;
    }

    state.slider.style.setProperty(
      "--gallery-slider-offset",
      state.offset + "px",
    );
    state.animationFrameId = window.requestAnimationFrame(function (nextNow) {
      animateSlider(state, nextNow);
    });
  };

  const startSlider = function (state) {
    if (
      state.animationFrameId ||
      prefersReducedMotion.matches ||
      !state.loopWidth
    ) {
      return;
    }

    state.lastTimestamp = null;
    state.animationFrameId = window.requestAnimationFrame(function (now) {
      animateSlider(state, now);
    });
  };

  const stopSlider = function (state) {
    if (state.animationFrameId) {
      window.cancelAnimationFrame(state.animationFrameId);
      state.animationFrameId = null;
    }
  };

  const refreshSlider = function (state) {
    stopSlider(state);
    buildSlider(state);
    startSlider(state);
  };

  sliders.forEach(function (slider) {
    const state = {
      slider: slider,
      direction: slider.classList.contains("right") ? 1 : -1,
      speed: Number(slider.dataset.sliderSpeed || 80),
      loopWidth: 0,
      offset: 0,
      originalMarkup: "",
      animationFrameId: null,
      lastTimestamp: null,
    };

    refreshSlider(state);

    if ("ResizeObserver" in window) {
      const resizeObserver = new ResizeObserver(function () {
        refreshSlider(state);
      });
      resizeObserver.observe(slider);
      state.resizeObserver = resizeObserver;
    }

    const images = slider.querySelectorAll("img");
    images.forEach(function (img) {
      if (!img.complete) {
        img.addEventListener(
          "load",
          function () {
            refreshSlider(state);
          },
          { once: true },
        );
      }
    });

    sliderStates.push(state);
  });

  if (!("ResizeObserver" in window)) {
    let resizeTimer = null;
    window.addEventListener("resize", function () {
      window.clearTimeout(resizeTimer);
      resizeTimer = window.setTimeout(function () {
        sliderStates.forEach(function (state) {
          refreshSlider(state);
        });
      }, 150);
    });
  }

  const handleReducedMotionChange = function () {
    sliderStates.forEach(function (state) {
      if (prefersReducedMotion.matches) {
        stopSlider(state);
        state.slider.style.setProperty("--gallery-slider-offset", "0px");
        return;
      }

      refreshSlider(state);
    });
  };

  if (typeof prefersReducedMotion.addEventListener === "function") {
    prefersReducedMotion.addEventListener("change", handleReducedMotionChange);
  } else if (typeof prefersReducedMotion.addListener === "function") {
    prefersReducedMotion.addListener(handleReducedMotionChange);
  }

  document.addEventListener("visibilitychange", function () {
    sliderStates.forEach(function (state) {
      if (document.hidden) {
        stopSlider(state);
        return;
      }

      startSlider(state);
    });
  });
});
