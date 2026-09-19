import { describe, expect, test, beforeAll } from 'bun:test';

// resize-manager.ts instantiates a real `ResizeObserver` at import time,
// which does not exist in bun's test runtime (no DOM). A tiny stub lets us
// exercise the callback-registration logic without a browser.
class FakeResizeObserver {
  static instances: FakeResizeObserver[] = [];
  callback: ResizeObserverCallback;
  observed = new Set<Element>();

  constructor(callback: ResizeObserverCallback) {
    this.callback = callback;
    FakeResizeObserver.instances.push(this);
  }

  observe(element: Element): void {
    this.observed.add(element);
  }

  unobserve(element: Element): void {
    this.observed.delete(element);
  }

  disconnect(): void {
    this.observed.clear();
  }

  trigger(target: Element): void {
    this.callback([{ target } as ResizeObserverEntry], this as unknown as ResizeObserver);
  }
}

beforeAll(() => {
  (global as unknown as { ResizeObserver: unknown }).ResizeObserver = FakeResizeObserver;
});

describe('resizeManager', () => {
  test('is a singleton and dispatches to the registered callback for its element', async () => {
    const { default: resizeManager } = await import('../../assets/src/ts/modules/resize-manager');
    const { default: resizeManager2 } = await import('../../assets/src/ts/modules/resize-manager');
    expect(resizeManager).toBe(resizeManager2);

    const element = {} as Element;
    let calls = 0;
    resizeManager.registerElement(element, () => {
      calls += 1;
    });

    const observer = FakeResizeObserver.instances[0];
    observer.trigger(element);
    expect(calls).toBe(1);

    resizeManager.destroyListener(element);
    observer.trigger(element);
    expect(calls).toBe(1);
  });

  test('ignores a null element or a non-function callback', async () => {
    const { default: resizeManager } = await import('../../assets/src/ts/modules/resize-manager');
    expect(() => resizeManager.registerElement(null, () => {})).not.toThrow();
    expect(() => resizeManager.registerElement({} as Element, undefined as never)).not.toThrow();
  });
});
