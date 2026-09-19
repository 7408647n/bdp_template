import { describe, expect, test } from "bun:test";
import {
	acceptAll,
	acceptNecessaryOnly,
	type ConsentCategory,
	isCategoryAllowed,
	parseConsent,
	serializeConsent,
} from "../../assets/src/js/modules/cookie-consent-state";

const categories: ConsentCategory[] = [
	{ key: "necessary", label: "Necessary", description: "", locked: true },
	{ key: "statistics", label: "Statistics", description: "", locked: false },
	{ key: "marketing", label: "Marketing", description: "", locked: false },
];

describe("acceptAll", () => {
	test("turns every category on", () => {
		expect(acceptAll(categories)).toEqual({
			necessary: true,
			statistics: true,
			marketing: true,
		});
	});
});

describe("acceptNecessaryOnly", () => {
	test("keeps only locked categories on", () => {
		expect(acceptNecessaryOnly(categories)).toEqual({
			necessary: true,
			statistics: false,
			marketing: false,
		});
	});
});

describe("serializeConsent / parseConsent", () => {
	test("round-trips a consent decision", () => {
		const state = acceptAll(categories);
		const raw = serializeConsent(state);
		const parsed = parseConsent(raw);

		expect(parsed).not.toBeNull();
		expect(parsed?.categories).toEqual(state);
		expect(parsed?.version).toBe(1);
	});

	test("returns null for missing input", () => {
		expect(parseConsent(null)).toBeNull();
		expect(parseConsent(undefined)).toBeNull();
		expect(parseConsent("")).toBeNull();
	});

	test("returns null for malformed JSON", () => {
		expect(parseConsent("{not json")).toBeNull();
	});

	test("returns null for an incompatible version", () => {
		expect(parseConsent(JSON.stringify({ version: 99, categories: {}, timestamp: 1 }))).toBeNull();
	});
});

const necessaryCategory = categories[0]!;
const statisticsCategory = categories[1]!;
const marketingCategory = categories[2]!;

describe("isCategoryAllowed", () => {
	test("locked categories are always allowed", () => {
		expect(isCategoryAllowed(null, necessaryCategory)).toBe(true);
	});

	test("unlocked categories need a stored decision", () => {
		expect(isCategoryAllowed(null, statisticsCategory)).toBe(false);
	});

	test("unlocked categories follow the stored decision", () => {
		const stored = { version: 1 as const, categories: { statistics: true }, timestamp: Date.now() };
		expect(isCategoryAllowed(stored, statisticsCategory)).toBe(true);
		expect(isCategoryAllowed(stored, marketingCategory)).toBe(false);
	});
});
