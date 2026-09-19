/**
 * Singleton wrapper around a shared ResizeObserver, ported 1:1 from
 * Resources/Private/Source/JavaScript/modules/resize-manager.js
 */
type ResizeCallback = (entry: ResizeObserverEntry) => void;

class ResizeManager {
  private static instance: ResizeManager;
  private callbacks: Map<Element, ResizeCallback> = new Map();
  private resizeObserver: ResizeObserver | undefined;

  constructor() {
    if (ResizeManager.instance) {
      return ResizeManager.instance;
    }

    this.resizeObserver = new ResizeObserver((entries) => {
      for (const entry of entries) {
        const callback = this.callbacks.get(entry.target);
        if (callback) {
          callback(entry);
        }
      }
    });

    ResizeManager.instance = this;
  }

  registerElement(element: Element | null, callback: ResizeCallback): void {
    if (!this.resizeObserver || !element || typeof callback !== 'function') return;
    this.callbacks.set(element, callback);
    this.resizeObserver.observe(element);
  }

  destroyListener(element: Element | null): void {
    if (!this.resizeObserver || !element) return;
    this.callbacks.delete(element);
    this.resizeObserver.unobserve(element);
  }
}

const instance = new ResizeManager();
Object.freeze(instance);

export default instance;
