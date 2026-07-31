/**
 * visitor_tracker.js — 個別訪問者行動トラッキング
 *
 * ・Cookie ID を発行/読み込み（有効期限 90日）
 * ・ページ閲覧・スクロール深度・滞在時間・CTAクリックを検知
 * ・/visitor_tracker.php へ POST して熱量スコアを更新
 */

(function () {
  'use strict';

  /* ---- 設定 ---- */
  const API_URL     = '/visitor_tracker.php';
  const COOKIE_NAME = 'yafuso_vid';
  const COOKIE_DAYS = 90;
  const TRACKER_TOKEN = document.querySelector('meta[name="yafuso-visitor-token"]')?.content || '';
  const FORM_SUBMIT_TOKEN = document.querySelector('meta[name="yafuso-form-submit-token"]')?.content || '';

  /* ---- analyticsページは計測しない ---- */
  if (location.pathname.startsWith('/analytics') || !TRACKER_TOKEN) return;

  /* ================================================================
   * Cookie ユーティリティ
   * ================================================================ */
  function getCookie(name) {
    const match = document.cookie.match(new RegExp('(?:^|; )' + name + '=([^;]*)'));
    return match ? decodeURIComponent(match[1]) : null;
  }

  function setCookie(name, value, days) {
    const expires = new Date(Date.now() + days * 864e5).toUTCString();
    document.cookie = name + '=' + encodeURIComponent(value)
      + '; expires=' + expires
      + '; path=/; SameSite=Lax'
      + (location.protocol === 'https:' ? '; Secure' : '');
  }

  function generateId() {
    return 'v-' + Date.now().toString(36) + '-' + Math.random().toString(36).slice(2, 9);
  }

  /* ---- 訪問者ID取得 or 発行 ---- */
  let visitorId = getCookie(COOKIE_NAME);
  if (!visitorId) {
    visitorId = generateId();
    setCookie(COOKIE_NAME, visitorId, COOKIE_DAYS);
  } else {
    // 有効期限を延長
    setCookie(COOKIE_NAME, visitorId, COOKIE_DAYS);
  }

  /* ================================================================
   * イベント送信
   * ================================================================ */
  function send(event) {
    const payload = {
      visitor_id: visitorId,
      event:      event,
      page:       location.pathname,
      ref:        document.referrer || '',   // 流入元判定用（初回のみサーバーが使用）
      token:      TRACKER_TOKEN,
    };
    if (event === 'form_submit' && FORM_SUBMIT_TOKEN) {
      payload.form_token = FORM_SUBMIT_TOKEN;
    }
    // Beacon API（ページ離脱時も確実に送信）
    if (navigator.sendBeacon) {
      navigator.sendBeacon(API_URL, JSON.stringify(payload));
    } else {
      fetch(API_URL, {
        method:  'POST',
        headers: { 'Content-Type': 'application/json' },
        body:    JSON.stringify(payload),
        keepalive: true,
      }).catch(() => {});
    }
  }

  /* ================================================================
   * ページ種別を判定してイベントを発火
   * ================================================================ */
  const path = location.pathname;

  function detectPageType() {
    if (/\/concept(?:\.php)?$/.test(path))       return 'concept_page';
    if (/\/market_stalls(?:\.php)?$/.test(path)) return 'market_page';
    if (/\/karaoke(?:\.php)?$/.test(path))       return 'karaoke_page';
    if (/\/vendors(?:\.php)?$/.test(path))       return 'vendors_page';
    return null;
  }

  /* ---- ページ閲覧（基本 +1） ---- */
  send('pageview');

  /* ---- ページ種別ボーナス ---- */
  const pageType = detectPageType();
  if (pageType) send(pageType);

  /* ---- 送信完了（再読込による重複を防ぐ） ---- */
  if (/\/thanks(?:\.php)?$/.test(path) && new URLSearchParams(location.search).has('type')) {
    const submitKey = 'yafuso_form_submit_' + location.search;
    if (!sessionStorage.getItem(submitKey)) {
      sessionStorage.setItem(submitKey, '1');
      send('form_submit');
    }
  }

  /* ---- フォーム入力開始（フォームごとに1セッション1回） ---- */
  const startedForms = new Set();
  document.addEventListener('focusin', function (e) {
    const form = e.target.closest('form[action*="mailform/send.php"]');
    if (!form || startedForms.has(form)) return;
    startedForms.add(form);
    const formName = form.querySelector('[name="form_type"]')?.value || '';
    if (typeof gtag === 'function') {
      gtag('event', 'form_start', { form_name: formName });
    }
    send('form_start');
  }, true);

  /* ================================================================
   * スクロール深度
   * ================================================================ */
  let scrollSent50 = false;
  let scrollSent90 = false;

  function onScroll() {
    const el       = document.documentElement;
    const scrolled  = el.scrollTop + window.innerHeight;
    const total     = el.scrollHeight;
    if (total <= 0) return;
    const pct = (scrolled / total) * 100;

    if (!scrollSent50 && pct >= 50) { scrollSent50 = true; send('scroll_50'); }
    if (!scrollSent90 && pct >= 90) { scrollSent90 = true; send('scroll_90'); }
  }

  window.addEventListener('scroll', onScroll, { passive: true });

  /* ================================================================
   * 滞在時間
   * ================================================================ */
  let stay60Sent  = false;
  let stay120Sent = false;

  setTimeout(function () {
    if (!stay60Sent)  { stay60Sent  = true; send('stay_60');  }
  }, 60000);

  setTimeout(function () {
    if (!stay120Sent) { stay120Sent = true; send('stay_120'); }
  }, 120000);

  /* ================================================================
   * CTAクリック
   * ================================================================ */
  document.addEventListener('click', function (e) {
    const el = e.target.closest('a, button');
    if (!el) return;

    const href = el.href || '';
    const text = (el.textContent || '').trim();

    if (/karaoke|vendors|申込|予約|応募|問い合わせ/.test(href + text)) {
      send('application_click');
    }
  }, true);

})();
