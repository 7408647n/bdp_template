/**
 * Small enter/leave CSS-class transition helper (Vue-style transition
 * classes applied manually), used to animate the mobile dropdown submenus.
 *
 * Ported from Resources/Private/Sources/JavaScript/menu/animation.js.
 */

const vtcKey = Symbol("_vtc");

interface TransitionTarget extends HTMLElement {
	[vtcKey]?: Set<string>;
}

function forceReflow(el: HTMLElement): number {
	const targetDocument = el.ownerDocument ?? document;
	return targetDocument.body.offsetHeight;
}

function addTransitionClass(el: TransitionTarget, cls: string): void {
	cls.split(/\s+/).forEach((c) => c && el.classList.add(c));
	(el[vtcKey] ??= new Set()).add(cls);
}

function removeTransitionClass(el: TransitionTarget, cls: string): void {
	cls.split(/\s+/).forEach((c) => c && el.classList.remove(c));
	const set = el[vtcKey];
	if (set) {
		set.delete(cls);
		if (set.size === 0) {
			delete el[vtcKey];
		}
	}
}

function nextFrame(cb: () => void): void {
	requestAnimationFrame(() => {
		requestAnimationFrame(cb);
	});
}

function getTransitionDuration(el: HTMLElement): number {
	const style = getComputedStyle(el);
	const durations = style.transitionDuration.split(",").map((s) => parseFloat(s) * 1000);
	const delays = style.transitionDelay.split(",").map((s) => parseFloat(s) * 1000);
	const totalDurations = durations.map((d, i) => d + (delays[i] || 0));
	return Math.max(...totalDurations, 0);
}

function removeAllTransitionClasses(el: TransitionTarget, ...classes: string[]): void {
	classes.forEach((cls) => removeTransitionClass(el, cls));
}

export class Transition {
	el: TransitionTarget;
	name: string;
	isEnter = false;
	isLeave = false;
	private cancelEnterFn: (() => void) | null = null;
	private cancelLeaveFn: (() => void) | null = null;
	enterFromClass: string;
	enterActiveClass: string;
	enterToClass: string;
	leaveFromClass: string;
	leaveActiveClass: string;
	leaveToClass: string;

	constructor(
		el: HTMLElement,
		name = "slide",
		enterFromClass?: string,
		enterActiveClass?: string,
		enterToClass?: string,
		leaveFromClass?: string,
		leaveActiveClass?: string,
		leaveToClass?: string,
	) {
		this.el = el;
		this.name = name;
		this.enterFromClass = enterFromClass ?? `${name}-enter-from`;
		this.enterActiveClass = enterActiveClass ?? `${name}-enter-active`;
		this.enterToClass = enterToClass ?? `${name}-enter-to`;
		this.leaveFromClass = leaveFromClass ?? `${name}-leave-from`;
		this.leaveActiveClass = leaveActiveClass ?? `${name}-leave-active`;
		this.leaveToClass = leaveToClass ?? `${name}-leave-to`;
	}

	private cleanupEnter(): void {
		removeAllTransitionClasses(this.el, this.enterFromClass, this.enterActiveClass, this.enterToClass);
		this.isEnter = false;
		this.cancelEnterFn = null;
	}

	private cleanupLeave(): void {
		removeAllTransitionClasses(this.el, this.leaveFromClass, this.leaveActiveClass, this.leaveToClass);
		this.isLeave = false;
		this.cancelLeaveFn = null;
	}

	cancelEnter(): void {
		this.cancelEnterFn?.();
		this.cancelEnterFn = null;
	}

	cancelLeave(): void {
		this.cancelLeaveFn?.();
		this.cancelLeaveFn = null;
	}

	enter(): Promise<void> {
		this.cancelLeave();

		if (this.isEnter) {
			return Promise.resolve();
		}
		this.isEnter = true;

		return new Promise((resolve) => {
			const el = this.el;

			el.style.display = "";
			forceReflow(el);

			addTransitionClass(el, this.enterFromClass);
			addTransitionClass(el, this.enterActiveClass);

			nextFrame(() => {
				removeTransitionClass(el, this.enterFromClass);
				addTransitionClass(el, this.enterToClass);

				const done = (cancelled = false) => {
					clearTimeout(fallback);
					el.removeEventListener("transitionend", onEnd);
					this.cleanupEnter();
					if (!cancelled) {
						resolve();
					}
				};

				const onEnd = (e: Event) => {
					if (e.target !== el) return;
					done();
				};

				const duration = getTransitionDuration(el);
				const fallback = window.setTimeout(() => done(), duration + 50);

				el.addEventListener("transitionend", onEnd);
				this.cancelEnterFn = () => done(true);
			});
		});
	}

	leave(): Promise<void> {
		this.cancelEnter();

		if (this.isLeave) {
			return Promise.resolve();
		}
		this.isLeave = true;

		return new Promise((resolve) => {
			const el = this.el;

			addTransitionClass(el, this.leaveFromClass);
			addTransitionClass(el, this.leaveActiveClass);
			forceReflow(el);

			nextFrame(() => {
				removeTransitionClass(el, this.leaveFromClass);
				addTransitionClass(el, this.leaveToClass);

				const done = (cancelled = false) => {
					clearTimeout(fallback);
					el.removeEventListener("transitionend", onEnd);
					this.cleanupLeave();
					if (!cancelled) {
						el.style.display = "none";
						resolve();
					}
				};

				const onEnd = (e: Event) => {
					if (e.target !== el) return;
					done();
				};

				const duration = getTransitionDuration(el);
				const fallback = window.setTimeout(() => done(), duration + 50);

				el.addEventListener("transitionend", onEnd);
				this.cancelLeaveFn = () => done(true);
			});
		});
	}

	toggle(): Promise<void> {
		if (this.el.style.display === "none" || getComputedStyle(this.el).display === "none") {
			return this.enter();
		}
		return this.leave();
	}
}
