class f {
  constructor() {
    return f.instance || (this.callbacks = /* @__PURE__ */ new Map(), this.resizeObserver = new ResizeObserver((t) => {
      for (const e of t)
        this.callbacks.has(e.target) && this.callbacks.get(e.target)(e);
    }), f.instance = this), f.instance;
  }
  registerElement(t, e) {
    !this.resizeObserver || !t || typeof e != "function" || (this.callbacks.set(t, e), this.resizeObserver.observe(t));
  }
  destroyListener(t) {
    !this.resizeObserver || !t || (this.callbacks.delete(t), this.resizeObserver.unobserve(t));
  }
}
const E = new f();
Object.freeze(E);
const v = /* @__PURE__ */ Symbol("_vtc");
function m(i) {
  return (i ? i.ownerDocument : document).body.offsetHeight;
}
function d(i, t) {
  t.split(/\s+/).forEach((e) => e && i.classList.add(e)), (i[v] || (i[v] = /* @__PURE__ */ new Set())).add(t);
}
function h(i, t) {
  t.split(/\s+/).forEach((r) => r && i.classList.remove(r));
  const e = i[v];
  e && (e.delete(t), e.size === 0 && (i[v] = void 0));
}
function b(i) {
  requestAnimationFrame(() => {
    requestAnimationFrame(i);
  });
}
const p = (i) => {
  const t = getComputedStyle(i), e = t.transitionDuration.split(",").map((s) => parseFloat(s) * 1e3), r = t.transitionDelay.split(",").map((s) => parseFloat(s) * 1e3), o = e.map((s, n) => s + (r[n] || 0));
  return Math.max(...o, 0);
}, y = (i, ...t) => {
  t.forEach((e) => h(i, e));
};
class L {
  constructor(t, e = "slide", r = void 0, o = void 0, s = void 0, n = void 0, a = void 0, l = void 0) {
    this.el = t, this.name = e, this.isEnter = !1, this.isLeave = !1, this._cancelEnter = null, this._cancelLeave = null, this.enterFromClass = r ?? `${e}-enter-from`, this.enterActiveClass = o ?? `${e}-enter-active`, this.enterToClass = s ?? `${e}-enter-to`, this.leaveFromClass = n ?? `${e}-leave-from`, this.leaveActiveClass = a ?? `${e}-leave-active`, this.leaveToClass = l ?? `${e}-leave-to`;
  }
  _cleanupEnter() {
    const t = this.el;
    y(
      t,
      this.enterFromClass,
      this.enterActiveClass,
      this.enterToClass
    ), this.isEnter = !1, this._cancelEnter = null;
  }
  _cleanupLeave() {
    const t = this.el;
    y(
      t,
      this.leaveFromClass,
      this.leaveActiveClass,
      this.leaveToClass
    ), this.isLeave = !1, this._cancelLeave = null;
  }
  cancelEnter() {
    this._cancelEnter && (this._cancelEnter(), this._cancelEnter = null);
  }
  cancelLeave() {
    this._cancelLeave && (this._cancelLeave(), this._cancelLeave = null);
  }
  enter() {
    return this.cancelLeave(), this.isEnter ? Promise.resolve() : (this.isEnter = !0, new Promise((t) => {
      const e = this.el;
      e.style.display = "", m(e), d(e, this.enterFromClass), d(e, this.enterActiveClass), b(() => {
        h(e, this.enterFromClass), d(e, this.enterToClass);
        const r = (a = !1) => {
          clearTimeout(n), e.removeEventListener("transitionend", o), this._cleanupEnter(), a || t();
        }, o = (a) => {
          a && a.target !== e || r();
        }, s = p(e), n = setTimeout(() => r(), s + 50);
        e.addEventListener("transitionend", o), this._cancelEnter = () => r(!0);
      });
    }));
  }
  leave() {
    return this.cancelEnter(), this.isLeave ? Promise.resolve() : (this.isLeave = !0, new Promise((t) => {
      const e = this.el;
      d(e, this.leaveFromClass), d(e, this.leaveActiveClass), m(e), b(() => {
        h(e, this.leaveFromClass), d(e, this.leaveToClass);
        const r = (a = !1) => {
          clearTimeout(n), e.removeEventListener("transitionend", o), this._cleanupLeave(), a || (e.style.display = "none", t());
        }, o = (a) => {
          a && a.target !== e || r();
        }, s = p(e), n = setTimeout(() => r(), s + 50);
        e.addEventListener("transitionend", o), this._cancelLeave = () => r(!0);
      });
    }));
  }
  toggle() {
    return this.el.style.display === "none" || getComputedStyle(this.el).display === "none" ? this.enter() : this.leave();
  }
}
let c = "", u = "";
function g(i) {
  const t = document.getElementById("mobile-sidebar");
  t && (window.innerWidth <= 1024 ? !t.classList.contains("hidden") && document.querySelector("body").style.setProperty("overflow", "hidden") : document.querySelector("body").style.removeProperty("overflow"));
}
document.addEventListener(
  "DOMContentLoaded",
  function() {
    const i = document.getElementById("main-menu");
    i && i.addEventListener(
      "click",
      function(s) {
        const n = s.target.closest("[data-tid]");
        n && n.dataset.tid && (n.dataset.tid !== c ? (c = n.dataset.tid, s.preventDefault()) : (c = "", s.preventDefault()), i.querySelectorAll("button.group\\/navbutton").forEach(function(a) {
          c !== "" && a.dataset.tid === c ? a.setAttribute("aria-expanded", "true") : a.setAttribute("aria-expanded", "false");
        }));
      }
    );
    const t = document.getElementById("mobile-main-menu-toggle"), e = document.getElementById("mobile-sidebar");
    t && e && t.addEventListener(
      "click",
      function(s) {
        !e.classList.contains("hidden") ? (t.setAttribute("aria-expanded", "false"), e.classList.add("hidden"), document.querySelector("body").style.removeProperty("overflow")) : (t.setAttribute("aria-expanded", "true"), e.classList.remove("hidden"), document.querySelector("body").style.setProperty("overflow", "hidden")), s.preventDefault();
      }
    );
    const r = document.getElementById("mobile-menu");
    if (r) {
      const s = /* @__PURE__ */ new Map();
      r.querySelectorAll(".m-submenu").forEach(function(n) {
        n.dataset.sid && s.set(
          n.dataset.sid,
          new L(
            n,
            "mobile-dropdown",
            "overflow-hidden grid grid-rows-[0fr]",
            "transition-[grid-template-rows] duration-400 ease-in",
            "overflow-hidden grid grid-rows-[1fr]",
            "overflow-hidden grid grid-rows-[1fr]",
            "transition-[grid-template-rows] duration-400 ease-out",
            "overflow-hidden grid grid-rows-[0fr]"
          )
        );
      }), r.addEventListener(
        "click",
        function(n) {
          const a = n.target.closest("[data-tid]");
          a && a.dataset.tid && (a.dataset.tid !== u ? (u = a.dataset.tid, n.preventDefault()) : (u = "", n.preventDefault()), r.querySelectorAll("button.group\\/mnavbutton").forEach(function(l) {
            u !== "" && l.dataset.tid === u ? s.has(l.dataset.tid) && (l.setAttribute("aria-expanded", "true"), s.get(l.dataset.tid).enter()) : s.has(l.dataset.tid) && (l.setAttribute("aria-expanded", "false"), s.get(l.dataset.tid).el.style.display !== "none" && s.get(l.dataset.tid).leave());
          }));
        }
      );
    }
    const o = document.querySelector("body");
    o && (g(), E.registerElement(o, g));
  }
);
