import { m as y, c as W, e as X, s as V, a as Y, b as j, d as K, S as J, N as Q } from "./navigation-Cyfumqw5.js";
function G(e = "") {
  return `.${e.trim().replace(/([.:!+/()[\]#>~*^$|=,'"@{}\\])/g, "\\$1").replace(/ /g, ".")}`;
}
const Z = (e) => !!e.virtual && !!e.params.virtual?.enabled, w = (e) => !!e.params.freeMode?.enabled, U = (e) => {
  if (Z(e))
    return e.virtual.slides.length;
  const P = e.params.grid?.rows;
  return e.grid && P && P > 1 ? e.slides.length / Math.ceil(P) : e.slides.length;
}, ee = ({ swiper: e, extendParams: P, on: p, emit: d }) => {
  const u = "swiper-pagination";
  P({
    pagination: {
      el: null,
      bulletElement: "span",
      clickable: !1,
      hideOnClick: !1,
      renderBullet: null,
      renderProgressbar: null,
      renderFraction: null,
      renderCustom: null,
      progressbarOpposite: !1,
      type: "bullets",
      // 'bullets' or 'progressbar' or 'fraction' or 'custom'
      dynamicBullets: !1,
      dynamicMainBullets: 1,
      formatFractionCurrent: (t) => t,
      formatFractionTotal: (t) => t,
      bulletClass: `${u}-bullet`,
      bulletActiveClass: `${u}-bullet-active`,
      modifierClass: `${u}-`,
      currentClass: `${u}-current`,
      totalClass: `${u}-total`,
      hiddenClass: `${u}-hidden`,
      progressbarFillClass: `${u}-progressbar-fill`,
      progressbarOppositeClass: `${u}-progressbar-opposite`,
      clickableClass: `${u}-clickable`,
      lockClass: `${u}-lock`,
      horizontalClass: `${u}-horizontal`,
      verticalClass: `${u}-vertical`,
      paginationDisabledClass: `${u}-disabled`
    }
  }), e.pagination = {
    el: null,
    bullets: []
  };
  let E, T = 0;
  function o() {
    return e.params.pagination;
  }
  function C() {
    return !o().el || !e.pagination.el || Array.isArray(e.pagination.el) && e.pagination.el.length === 0;
  }
  function z(t, a) {
    const { bulletActiveClass: c } = o();
    if (!t)
      return;
    let l = t[`${a === "prev" ? "previous" : "next"}ElementSibling`];
    l && (l.classList.add(`${c}-${a}`), l = l[`${a === "prev" ? "previous" : "next"}ElementSibling`], l && l.classList.add(`${c}-${a}-${a}`));
  }
  function N(t, a, c) {
    if (t = t % c, a = a % c, a === t + 1)
      return "next";
    if (a === t - 1)
      return "previous";
  }
  function A(t) {
    const c = t.target.closest(G(o().bulletClass));
    if (!c)
      return;
    t.preventDefault();
    const l = (j(c) ?? 0) * (e.params.slidesPerGroup ?? 1);
    if (e.params.loop) {
      if (e.realIndex === l)
        return;
      const g = N(e.realIndex, l, e.slides.length);
      g === "next" ? e.slideNext() : g === "previous" ? e.slidePrev() : e.slideToLoop(l);
    } else
      e.slideTo(l);
  }
  function M() {
    const t = e.rtl, a = o();
    if (C())
      return;
    const c = y(e.pagination.el);
    let l, g;
    const $ = U(e), R = e.params.loop ? Math.ceil($ / (e.params.slidesPerGroup ?? 1)) : e.snapGrid.length;
    if (e.params.loop ? (g = e.previousRealIndex || 0, l = (e.params.slidesPerGroup ?? 1) > 1 ? Math.floor(e.realIndex / (e.params.slidesPerGroup ?? 1)) : e.realIndex) : typeof e.snapIndex < "u" ? (l = e.snapIndex, g = e.previousSnapIndex) : (g = e.previousIndex || 0, l = e.activeIndex || 0), a.type === "bullets" && e.pagination.bullets && e.pagination.bullets.length > 0) {
      const m = e.pagination.bullets;
      let L = 0, v = 0, q = 0;
      if (a.dynamicBullets) {
        E = Y(m[0], e.isHorizontal() ? "width" : "height");
        const b = e.isHorizontal() ? "width" : "height";
        c.forEach((h) => {
          h.style[b] = `${(E ?? 0) * (a.dynamicMainBullets + 4)}px`;
        }), a.dynamicMainBullets > 1 && g !== void 0 && (T += l - (g || 0), T > a.dynamicMainBullets - 1 ? T = a.dynamicMainBullets - 1 : T < 0 && (T = 0)), L = Math.max(l - T, 0), v = L + (Math.min(m.length, a.dynamicMainBullets) - 1), q = (v + L) / 2;
      }
      if (m.forEach((b) => {
        const h = [
          "",
          "-next",
          "-next-next",
          "-prev",
          "-prev-prev",
          "-main"
        ].map((x) => `${a.bulletActiveClass}${x}`).flatMap((x) => typeof x == "string" && x.includes(" ") ? x.split(" ") : [x]);
        b.classList.remove(...h);
      }), c.length > 1)
        m.forEach((b) => {
          const h = j(b);
          h === l ? b.classList.add(...a.bulletActiveClass.split(" ")) : e.isElement && b.setAttribute("part", "bullet"), a.dynamicBullets && h !== void 0 && (h >= L && h <= v && b.classList.add(...`${a.bulletActiveClass}-main`.split(" ")), h === L && z(b, "prev"), h === v && z(b, "next"));
        });
      else {
        const b = m[l];
        if (b && b.classList.add(...a.bulletActiveClass.split(" ")), e.isElement && m.forEach((h, x) => {
          h.setAttribute("part", x === l ? "bullet-active" : "bullet");
        }), a.dynamicBullets) {
          const h = m[L], x = m[v];
          for (let n = L; n <= v; n += 1)
            m[n] && m[n].classList.add(...`${a.bulletActiveClass}-main`.split(" "));
          z(h, "prev"), z(x, "next");
        }
      }
      if (a.dynamicBullets) {
        const b = Math.min(m.length, a.dynamicMainBullets + 4), h = ((E ?? 0) * b - (E ?? 0)) / 2 - q * (E ?? 0), x = t ? "right" : "left", n = e.isHorizontal() ? x : "top";
        m.forEach((s) => {
          s.style[n] = `${h}px`;
        });
      }
    }
    c.forEach((m, L) => {
      if (a.type === "fraction" && (m.querySelectorAll(G(a.currentClass)).forEach((v) => {
        v.textContent = String(a.formatFractionCurrent(l + 1));
      }), m.querySelectorAll(G(a.totalClass)).forEach((v) => {
        v.textContent = String(a.formatFractionTotal(R));
      })), a.type === "progressbar") {
        let v;
        a.progressbarOpposite ? v = e.isHorizontal() ? "vertical" : "horizontal" : v = e.isHorizontal() ? "horizontal" : "vertical";
        const q = (l + 1) / R;
        let b = 1, h = 1;
        v === "horizontal" ? b = q : h = q, m.querySelectorAll(G(a.progressbarFillClass)).forEach((x) => {
          x.style.transform = `translate3d(0,0,0) scaleX(${b}) scaleY(${h})`, x.style.transitionDuration = `${e.params.speed}ms`;
        });
      }
      a.type === "custom" && a.renderCustom ? (V(m, a.renderCustom(e, l + 1, R)), L === 0 && d("paginationRender", m)) : (L === 0 && d("paginationRender", m), d("paginationUpdate", m)), e.params.watchOverflow && e.enabled && m.classList[e.isLocked ? "add" : "remove"](a.lockClass);
    });
  }
  function F() {
    const t = o();
    if (C())
      return;
    const a = U(e), c = y(e.pagination.el);
    let l = "";
    if (t.type === "bullets") {
      let g = e.params.loop ? Math.ceil(a / (e.params.slidesPerGroup ?? 1)) : e.snapGrid.length;
      e.params.freeMode && w(e) && g > a && (g = a);
      for (let $ = 0; $ < g; $ += 1)
        t.renderBullet ? l += t.renderBullet.call(e, $, t.bulletClass) : l += `<${t.bulletElement} ${e.isElement ? 'part="bullet"' : ""} class="${t.bulletClass}"></${t.bulletElement}>`;
    }
    t.type === "fraction" && (t.renderFraction ? l = t.renderFraction.call(e, t.currentClass, t.totalClass) : l = `<span class="${t.currentClass}"></span> / <span class="${t.totalClass}"></span>`), t.type === "progressbar" && (t.renderProgressbar ? l = t.renderProgressbar.call(e, t.progressbarFillClass) : l = `<span class="${t.progressbarFillClass}"></span>`), e.pagination.bullets = [], c.forEach((g) => {
      t.type !== "custom" && V(g, l || ""), t.type === "bullets" && e.pagination.bullets.push(...Array.from(g.querySelectorAll(G(t.bulletClass))));
    }), t.type !== "custom" && d("paginationRender", c[0]);
  }
  function k() {
    e.params.pagination = W(e, e.originalParams.pagination, e.params.pagination, { el: "swiper-pagination" });
    const t = o();
    if (!t.el)
      return;
    let a;
    if (typeof t.el == "string" && e.isElement && (a = e.el.querySelector(t.el)), !a && typeof t.el == "string" && (a = [...document.querySelectorAll(t.el)]), a || (a = t.el), !a || Array.isArray(a) && a.length === 0)
      return;
    if (e.params.uniqueNavElements && typeof t.el == "string" && Array.isArray(a) && a.length > 1 && (a = [...e.el.querySelectorAll(t.el)], a.length > 1)) {
      const l = a.find((g) => X(g, ".swiper")[0] === e.el);
      l && (a = l);
    }
    Array.isArray(a) && a.length === 1 && (a = a[0]), Object.assign(e.pagination, {
      el: a
    }), y(a).forEach((l) => {
      t.type === "bullets" && t.clickable && l.classList.add(...(t.clickableClass || "").split(" ")), l.classList.add(t.modifierClass + t.type), l.classList.add(e.isHorizontal() ? t.horizontalClass : t.verticalClass), t.type === "bullets" && t.dynamicBullets && (l.classList.add(`${t.modifierClass}${t.type}-dynamic`), T = 0, t.dynamicMainBullets < 1 && (t.dynamicMainBullets = 1)), t.type === "progressbar" && t.progressbarOpposite && l.classList.add(t.progressbarOppositeClass), t.clickable && l.addEventListener("click", A), e.enabled || l.classList.add(t.lockClass);
    });
  }
  function B() {
    const t = o();
    if (C())
      return;
    const a = e.pagination.el;
    a && y(a).forEach((l) => {
      l.classList.remove(t.hiddenClass), l.classList.remove(t.modifierClass + t.type), l.classList.remove(e.isHorizontal() ? t.horizontalClass : t.verticalClass), t.clickable && (l.classList.remove(...(t.clickableClass || "").split(" ")), l.removeEventListener("click", A));
    }), e.pagination.bullets && e.pagination.bullets.forEach((c) => c.classList.remove(...t.bulletActiveClass.split(" ")));
  }
  p("changeDirection", () => {
    if (!e.pagination || !e.pagination.el)
      return;
    const t = o();
    y(e.pagination.el).forEach((c) => {
      c.classList.remove(t.horizontalClass, t.verticalClass), c.classList.add(e.isHorizontal() ? t.horizontalClass : t.verticalClass);
    });
  }), p("init", () => {
    o().enabled === !1 ? D() : (k(), F(), M());
  }), p("activeIndexChange", () => {
    typeof e.snapIndex > "u" && M();
  }), p("snapIndexChange", () => {
    M();
  }), p("snapGridLengthChange", () => {
    F(), M();
  }), p("destroy", () => {
    B();
  }), p("enable disable", () => {
    const { el: t } = e.pagination;
    if (t) {
      const a = o();
      y(t).forEach((l) => l.classList[e.enabled ? "remove" : "add"](a.lockClass));
    }
  }), p("lock unlock", () => {
    M();
  }), p("click", (t, a) => {
    const c = a.target, l = y(e.pagination.el), g = o();
    if (g.el && g.hideOnClick && l && l.length > 0 && !c.classList.contains(g.bulletClass)) {
      if (e.navigation && (e.navigation.nextEl && c === e.navigation.nextEl || e.navigation.prevEl && c === e.navigation.prevEl))
        return;
      const $ = l[0].classList.contains(g.hiddenClass);
      d($ === !0 ? "paginationShow" : "paginationHide"), l.forEach((R) => R.classList.toggle(g.hiddenClass));
    }
  });
  const H = () => {
    const t = o();
    e.el.classList.remove(t.paginationDisabledClass);
    const { el: a } = e.pagination;
    a && y(a).forEach((l) => l.classList.remove(t.paginationDisabledClass)), k(), F(), M();
  }, D = () => {
    const t = o();
    e.el.classList.add(t.paginationDisabledClass);
    const { el: a } = e.pagination;
    a && y(a).forEach((l) => l.classList.add(t.paginationDisabledClass)), B();
  };
  Object.assign(e.pagination, {
    enable: H,
    disable: D,
    render: F,
    update: M,
    init: k,
    destroy: B
  });
}, te = (e) => !!e.virtual && !!e.params.virtual?.enabled, ae = ({ swiper: e, extendParams: P, on: p }) => {
  P({
    a11y: {
      enabled: !0,
      notificationClass: "swiper-notification",
      prevSlideMessage: "Previous slide",
      nextSlideMessage: "Next slide",
      firstSlideMessage: "This is the first slide",
      lastSlideMessage: "This is the last slide",
      paginationBulletMessage: "Go to slide {{index}}",
      slideLabelMessage: "{{index}} / {{slidesLength}}",
      containerMessage: null,
      containerRoleDescriptionMessage: null,
      containerRole: null,
      itemRoleDescriptionMessage: null,
      slideRole: "group",
      id: null,
      scrollOnFocus: !0,
      wrapperLiveRegion: !0
    }
  }), e.a11y = {
    clicked: !1
  };
  let d = null, u = !1, E, T = (/* @__PURE__ */ new Date()).getTime();
  function o() {
    return e.params.a11y;
  }
  function C(n) {
    const s = d;
    !s || !n || V(s, n);
  }
  function z(n = 16) {
    const s = () => Math.round(16 * Math.random()).toString(16);
    return "x".repeat(n).replace(/x/g, s);
  }
  function N(n) {
    y(n).forEach((r) => {
      r.setAttribute("tabIndex", "0");
    });
  }
  function A(n) {
    y(n).forEach((r) => {
      r.setAttribute("tabIndex", "-1");
    });
  }
  function M(n, s) {
    y(n).forEach((i) => {
      i.setAttribute("role", s);
    });
  }
  function F(n, s) {
    y(n).forEach((i) => {
      i.setAttribute("aria-roledescription", s);
    });
  }
  function k(n, s) {
    y(n).forEach((i) => {
      i.setAttribute("aria-label", s);
    });
  }
  function B(n, s) {
    y(n).forEach((i) => {
      i.setAttribute("id", s);
    });
  }
  function H(n, s) {
    y(n).forEach((i) => {
      i.setAttribute("aria-live", s);
    });
  }
  function D(n) {
    y(n).forEach((r) => {
      r.setAttribute("aria-disabled", "true");
    });
  }
  function t(n) {
    y(n).forEach((r) => {
      r.removeAttribute("aria-disabled");
    });
  }
  function a(n) {
    if (n.keyCode !== 13 && n.keyCode !== 32)
      return;
    const s = o(), r = e.params.pagination, i = n.target;
    if (!(e.pagination && e.pagination.el && (i === e.pagination.el || e.pagination.el.contains(i)) && !i.matches(G(r?.bulletClass)))) {
      if (e.navigation && e.navigation.prevEl && e.navigation.nextEl) {
        const f = y(e.navigation.prevEl);
        y(e.navigation.nextEl).includes(i) && (e.isEnd && !e.params.loop || e.slideNext(), e.isEnd ? C(s.lastSlideMessage) : C(s.nextSlideMessage)), f.includes(i) && (e.isBeginning && !e.params.loop || e.slidePrev(), e.isBeginning ? C(s.firstSlideMessage) : C(s.prevSlideMessage));
      }
      e.pagination && i.matches(G(r?.bulletClass)) && i.click();
    }
  }
  function c() {
    if (e.params.loop || e.params.rewind || !e.navigation)
      return;
    const { nextEl: n, prevEl: s } = e.navigation;
    s && (e.isBeginning ? (D(s), A(s)) : (t(s), N(s))), n && (e.isEnd ? (D(n), A(n)) : (t(n), N(n)));
  }
  function l() {
    return !!(e.pagination && e.pagination.bullets && e.pagination.bullets.length);
  }
  function g() {
    const n = e.params.pagination;
    return l() && !!n?.clickable;
  }
  function $() {
    const n = o();
    if (!l())
      return;
    const s = e.params.pagination;
    e.pagination.bullets.forEach((r) => {
      s.clickable && (N(r), s.renderBullet || (M(r, "button"), k(r, n.paginationBulletMessage.replace(/\{\{index\}\}/, String((j(r) ?? 0) + 1))))), r.matches(G(s.bulletActiveClass)) ? r.setAttribute("aria-current", "true") : r.removeAttribute("aria-current");
    });
  }
  const R = (n, s, r) => {
    N(n), n.tagName !== "BUTTON" && (M(n, "button"), n.addEventListener("keydown", a)), k(n, r);
  }, m = (n) => {
    E && E !== n.target && !E.contains(n.target) && (u = !0), e.a11y.clicked = !0;
  }, L = () => {
    u = !1, requestAnimationFrame(() => {
      requestAnimationFrame(() => {
        e.destroyed || (e.a11y.clicked = !1);
      });
    });
  }, v = (n) => {
    T = (/* @__PURE__ */ new Date()).getTime();
  }, q = (n) => {
    const s = o();
    if (e.a11y.clicked || !s.scrollOnFocus || (/* @__PURE__ */ new Date()).getTime() - T < 100)
      return;
    const i = n.target.closest(`.${e.params.slideClass}, swiper-slide`);
    if (!i || !e.slides.includes(i))
      return;
    E = i;
    const f = te(e), S = (f ? parseInt(i.getAttribute("data-swiper-slide-index") || "0", 10) : e.slides.indexOf(i)) === e.activeIndex, O = e.params.watchSlidesProgress && e.visibleSlides && e.visibleSlides.includes(i);
    if (S || O)
      return;
    const I = n.sourceCapabilities;
    I && I.firesTouchEvents || (e.isHorizontal() ? e.el.scrollLeft = 0 : e.el.scrollTop = 0, requestAnimationFrame(() => {
      u || (e.params.loop ? e.slideToLoop(e.getSlideIndexWhenGrid(parseInt(i.getAttribute("data-swiper-slide-index") || "0", 10)), 0) : f ? e.slideTo(e.getSlideIndexWhenGrid(parseInt(i.getAttribute("data-swiper-slide-index") || "0", 10)), 0) : e.slideTo(e.getSlideIndexWhenGrid(e.slides.indexOf(i)), 0), u = !1);
    }));
  }, b = () => {
    const n = o();
    n.itemRoleDescriptionMessage && F(e.slides, n.itemRoleDescriptionMessage), n.slideRole && M(e.slides, n.slideRole);
    const s = e.slides.length, r = n.slideLabelMessage;
    r && e.slides.forEach((i, f) => {
      const S = e.params.loop ? parseInt(i.getAttribute("data-swiper-slide-index") || "0", 10) : f, O = r.replace(/\{\{index\}\}/, String(S + 1)).replace(/\{\{slidesLength\}\}/, String(s));
      k(i, O);
    });
  }, h = () => {
    const n = o();
    d && e.el.append(d);
    const s = e.el;
    n.containerRoleDescriptionMessage && F(s, n.containerRoleDescriptionMessage), n.containerMessage && k(s, n.containerMessage), n.containerRole && M(s, n.containerRole);
    const r = e.wrapperEl, i = String(n.id || r.getAttribute("id") || `swiper-wrapper-${z(16)}`);
    if (B(r, i), n.wrapperLiveRegion) {
      const I = e.params.autoplay, _ = e.params.autoplay && I?.enabled ? "off" : "polite";
      H(r, _);
    }
    b();
    const f = e.navigation ? e.navigation : { nextEl: void 0, prevEl: void 0 }, S = y(f.nextEl), O = y(f.prevEl);
    S && S.forEach((I) => R(I, i, n.nextSlideMessage)), O && O.forEach((I) => R(I, i, n.prevSlideMessage)), g() && y(e.pagination.el).forEach((_) => {
      _.addEventListener("keydown", a);
    }), document.addEventListener("visibilitychange", v), e.el.addEventListener("focus", q, !0), e.el.addEventListener("pointerdown", m, !0), e.el.addEventListener("pointerup", L, !0);
  };
  function x() {
    d && d.remove();
    const n = e.navigation ? e.navigation : { nextEl: void 0, prevEl: void 0 }, s = y(n.nextEl), r = y(n.prevEl);
    s && s.forEach((i) => i.removeEventListener("keydown", a)), r && r.forEach((i) => i.removeEventListener("keydown", a)), g() && y(e.pagination.el).forEach((f) => {
      f.removeEventListener("keydown", a);
    }), document.removeEventListener("visibilitychange", v), e.el && typeof e.el != "string" && (e.el.removeEventListener("focus", q, !0), e.el.removeEventListener("pointerdown", m, !0), e.el.removeEventListener("pointerup", L, !0));
  }
  p("beforeInit", () => {
    d = K("span", o().notificationClass), d.setAttribute("aria-live", "assertive"), d.setAttribute("aria-atomic", "true");
  }), p("afterInit", () => {
    o().enabled && h();
  }), p("slidesLengthChange snapGridLengthChange slidesGridLengthChange", () => {
    o().enabled && b();
  }), p("fromEdge toEdge afterInit lock unlock", () => {
    o().enabled && c();
  }), p("paginationUpdate", () => {
    o().enabled && $();
  }), p("destroy", () => {
    o().enabled && x();
  });
}, ne = ({ swiper: e, extendParams: P, on: p, emit: d, params: u }) => {
  e.autoplay = {
    running: !1,
    paused: !1,
    timeLeft: 0
  }, P({
    autoplay: {
      enabled: !1,
      delay: 3e3,
      waitForTransition: !0,
      disableOnInteraction: !1,
      stopOnLastSlide: !1,
      reverseDirection: !1,
      pauseOnMouseEnter: !1
    }
  });
  function E() {
    return e.params.autoplay;
  }
  const T = typeof u.autoplay == "object" && u.autoplay && typeof u.autoplay.delay == "number" ? u.autoplay.delay : 3e3;
  let o, C, z = T, N = T, A = 0, M = (/* @__PURE__ */ new Date()).getTime(), F = !1, k = !1, B = !1, H, D = !1, t = !1;
  function a(i) {
    if (!e || e.destroyed || !e.wrapperEl || i.target !== e.wrapperEl)
      return;
    e.wrapperEl.removeEventListener("transitionend", a);
    const f = i.detail;
    t || f && f.bySwiperTouchMove || v();
  }
  const c = () => {
    if (e.destroyed || !e.autoplay.running)
      return;
    e.autoplay.paused ? F = !0 : F && (N = A, F = !1);
    const i = e.autoplay.paused ? A : M + N - (/* @__PURE__ */ new Date()).getTime();
    e.autoplay.timeLeft = i, d("autoplayTimeLeft", i, i / z), C = requestAnimationFrame(() => {
      c();
    });
  }, l = () => {
    let i;
    const f = !!e.params.virtual?.enabled;
    if (e.virtual && f ? i = e.slides.find((O) => O.classList.contains("swiper-slide-active")) : i = e.slides[e.activeIndex], !i)
      return;
    const S = i.getAttribute("data-swiper-autoplay");
    if (S != null)
      return parseInt(S, 10);
  }, g = () => {
    let i = E().delay;
    const f = l();
    return typeof f == "number" && !Number.isNaN(f) && f > 0 && (i = f), i;
  }, $ = (i) => {
    if (e.destroyed || !e.autoplay.running)
      return 0;
    C !== void 0 && cancelAnimationFrame(C), c();
    let f = i;
    typeof f > "u" && (f = g(), z = f, N = f), A = f;
    const S = e.params.speed, O = () => {
      if (!e || e.destroyed)
        return;
      const I = E();
      I.reverseDirection ? !e.isBeginning || e.params.loop || e.params.rewind ? (e.slidePrev(S, !0, !0), d("autoplay")) : I.stopOnLastSlide || (e.slideTo(e.slides.length - 1, S, !0, !0), d("autoplay")) : !e.isEnd || e.params.loop || e.params.rewind ? (e.slideNext(S, !0, !0), d("autoplay")) : I.stopOnLastSlide || (e.slideTo(0, S, !0, !0), d("autoplay")), e.params.cssMode && (M = (/* @__PURE__ */ new Date()).getTime(), requestAnimationFrame(() => {
        $();
      }));
    };
    return f > 0 ? (o !== void 0 && clearTimeout(o), o = setTimeout(() => {
      O();
    }, f)) : requestAnimationFrame(() => {
      O();
    }), f;
  }, R = () => (M = (/* @__PURE__ */ new Date()).getTime(), e.autoplay.running = !0, $(), d("autoplayStart"), !0), m = () => (e.autoplay.running = !1, o !== void 0 && clearTimeout(o), C !== void 0 && cancelAnimationFrame(C), d("autoplayStop"), !0), L = (i, f) => {
    if (e.destroyed || !e.autoplay.running)
      return;
    o !== void 0 && clearTimeout(o), i || (D = !0);
    const S = () => {
      d("autoplayPause"), E().waitForTransition ? e.wrapperEl.addEventListener("transitionend", a) : v();
    };
    if (e.autoplay.paused = !0, f) {
      S();
      return;
    }
    A = (A || E().delay) - ((/* @__PURE__ */ new Date()).getTime() - M), !(e.isEnd && A < 0 && !e.params.loop) && (A < 0 && (A = 0), S());
  }, v = () => {
    e.isEnd && A < 0 && !e.params.loop || e.destroyed || !e.autoplay.running || (M = (/* @__PURE__ */ new Date()).getTime(), D ? (D = !1, $(A)) : $(), e.autoplay.paused = !1, d("autoplayResume"));
  }, q = () => {
    e.destroyed || !e.autoplay.running || (document.visibilityState === "hidden" && (D = !0, L(!0)), document.visibilityState === "visible" && v());
  }, b = (i) => {
    i.pointerType === "mouse" && (D = !0, t = !0, !(e.animating || e.autoplay.paused) && L(!0));
  }, h = (i) => {
    i.pointerType === "mouse" && (t = !1, e.autoplay.paused && v());
  }, x = () => {
    E().pauseOnMouseEnter && (e.el.addEventListener("pointerenter", b), e.el.addEventListener("pointerleave", h));
  }, n = () => {
    e.el && typeof e.el != "string" && (e.el.removeEventListener("pointerenter", b), e.el.removeEventListener("pointerleave", h));
  }, s = () => {
    document.addEventListener("visibilitychange", q);
  }, r = () => {
    document.removeEventListener("visibilitychange", q);
  };
  p("init", () => {
    E().enabled && (x(), s(), R());
  }), p("destroy", () => {
    n(), r(), e.autoplay.running && m();
  }), p("_freeModeStaticRelease", () => {
    (B || D) && v();
  }), p("_freeModeNoMomentumRelease", () => {
    E().disableOnInteraction ? m() : L(!0, !0);
  }), p("beforeTransitionStart", (i, f, S) => {
    e.destroyed || !e.autoplay.running || (S || !E().disableOnInteraction ? L(!0, !0) : m());
  }), p("sliderFirstMove", () => {
    if (!(e.destroyed || !e.autoplay.running)) {
      if (E().disableOnInteraction) {
        m();
        return;
      }
      k = !0, B = !1, D = !1, H = setTimeout(() => {
        D = !0, B = !0, L(!0);
      }, 200);
    }
  }), p("touchEnd", () => {
    if (!(e.destroyed || !e.autoplay.running || !k)) {
      if (H !== void 0 && clearTimeout(H), o !== void 0 && clearTimeout(o), E().disableOnInteraction) {
        B = !1, k = !1;
        return;
      }
      B && e.params.cssMode && v(), B = !1, k = !1;
    }
  }), p("slideChange", () => {
    e.destroyed || !e.autoplay.running || e.autoplay.paused && (A = g(), z = g());
  }), Object.assign(e.autoplay, {
    start: R,
    stop: m,
    pause: L,
    resume: v
  });
}, le = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
document.querySelectorAll(".ct-headerslider").forEach(function(e) {
  const P = e.querySelectorAll(".swiper-slide").length > 1, p = P && e.dataset.edit !== "true" && !le ? {
    delay: 15e3,
    disableOnInteraction: !1,
    pauseOnMouseEnter: !0
  } : !1, d = new J(e, {
    // configure Swiper to use modules
    modules: [Q, ee, ne, ae],
    loop: P,
    a11y: !0,
    autoplay: p,
    pagination: {
      el: e.querySelector(".swiper-pagination")
    },
    navigation: {
      nextEl: e.querySelector(".swiper-button-next"),
      prevEl: e.querySelector(".swiper-button-prev")
    }
  }), u = e.querySelector(".ct-headerslider__toggle");
  if (!p || !u)
    return;
  const E = u.querySelector(".ct-headerslider__icon-pause"), T = u.querySelector(".ct-headerslider__icon-play"), o = function() {
    const C = d.autoplay.running;
    u.setAttribute("aria-label", (C ? u.dataset.labelPause : u.dataset.labelPlay) ?? ""), E?.toggleAttribute("hidden", !C), T?.toggleAttribute("hidden", C);
  };
  u.addEventListener("click", function() {
    d.autoplay.running ? d.autoplay.stop() : d.autoplay.start();
  }), e.addEventListener("focusin", function(C) {
    C.target !== u && d.autoplay.stop();
  }), d.on("autoplayStart", o), d.on("autoplayStop", o), u.hidden = !1, o();
});
