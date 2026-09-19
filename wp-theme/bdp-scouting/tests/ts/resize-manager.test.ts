import { describe, expect, test } from "bun:test";
import resizeManager from "../../assets/src/js/modules/resize-manager";

// bun's default test environment has no ResizeObserver global, matching a
// non-browser context; registerElement/destroyListener must degrade to
// no-ops rather than throw, and the module must stay a frozen singleton.

describe("resizeManager singleton", () => {
	test("is frozen", () => {
		expect(Object.isFrozen(resizeManager)).toBe(true);
	});

	test("registerElement does not throw without a real element", () => {
		expect(() => resizeManager.registerElement(null, () => {})).not.toThrow();
	});

	test("registerElement does not throw with a non-function callback", () => {
		const fakeElement = {} as unknown as Element;
		// @ts-expect-error intentionally passing a bad callback to check the guard
		expect(() => resizeManager.registerElement(fakeElement, "not-a-function")).not.toThrow();
	});

	test("destroyListener does not throw for an unregistered element", () => {
		const fakeElement = {} as unknown as Element;
		expect(() => resizeManager.destroyListener(fakeElement)).not.toThrow();
	});
});
