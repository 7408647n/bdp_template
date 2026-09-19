/**
 * Singleton ResizeObserver dispatcher.
 *
 * Ported from Resources/Private/Sources/JavaScript/modules/resize-manager.js.
 */

export type ResizeCallback = (entry: ResizeObserverEntry) => void;

class ResizeManager {
	private static instance: ResizeManager;
	private readonly callbacks: Map<Element, ResizeCallback> = new Map();
	private readonly resizeObserver!: ResizeObserver | null;

	constructor() {
		if (ResizeManager.instance) {
			// eslint-disable-next-line no-constructor-return
			return ResizeManager.instance;
		}

		this.resizeObserver =
			typeof ResizeObserver !== "undefined"
				? new ResizeObserver((entries) => {
						for (const entry of entries) {
							const callback = this.callbacks.get(entry.target);
							if (callback) {
								callback(entry);
							}
						}
					})
				: null;

		ResizeManager.instance = this;
	}

	registerElement(element: Element | null, callback: ResizeCallback): void {
		if (!this.resizeObserver || !element || typeof callback !== "function") {
			return;
		}
		this.callbacks.set(element, callback);
		this.resizeObserver.observe(element);
	}

	destroyListener(element: Element | null): void {
		if (!this.resizeObserver || !element) {
			return;
		}
		this.callbacks.delete(element);
		this.resizeObserver.unobserve(element);
	}
}

const instance = new ResizeManager();
Object.freeze(instance);

export default instance;
