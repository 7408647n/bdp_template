/**
 * Pure, DOM-free consent state helpers for the cookie banner
 * (template-parts/cookie/banner.php + inc/cookie-consent.php).
 *
 * Kept separate from entries/cookie-consent.entry.ts so the logic can be
 * unit tested without a DOM.
 */

export interface ConsentCategory {
	key: string;
	label: string;
	description: string;
	locked: boolean;
}

export type ConsentState = Record<string, boolean>;

export interface StoredConsent {
	version: 1;
	categories: ConsentState;
	timestamp: number;
}

const CONSENT_VERSION = 1 as const;

/**
 * Build the "accept all" state for a given category list.
 */
export function acceptAll(categories: ConsentCategory[]): ConsentState {
	const state: ConsentState = {};
	for (const category of categories) {
		state[category.key] = true;
	}
	return state;
}

/**
 * Build the "necessary only" state: locked categories stay on, everything
 * else is off.
 */
export function acceptNecessaryOnly(categories: ConsentCategory[]): ConsentState {
	const state: ConsentState = {};
	for (const category of categories) {
		state[category.key] = category.locked;
	}
	return state;
}

/**
 * Serialize a consent decision to a JSON string suitable for localStorage.
 */
export function serializeConsent(categories: ConsentState): string {
	const payload: StoredConsent = {
		version: CONSENT_VERSION,
		categories,
		timestamp: Date.now(),
	};
	return JSON.stringify(payload);
}

/**
 * Parse a previously stored consent string. Returns null when the value is
 * missing, malformed, or from an incompatible version — callers should then
 * treat the visitor as having made no decision yet.
 */
export function parseConsent(raw: string | null | undefined): StoredConsent | null {
	if (!raw) {
		return null;
	}

	try {
		const parsed = JSON.parse(raw);
		if (
			parsed &&
			typeof parsed === "object" &&
			parsed.version === CONSENT_VERSION &&
			parsed.categories &&
			typeof parsed.categories === "object" &&
			typeof parsed.timestamp === "number"
		) {
			return parsed as StoredConsent;
		}
	} catch {
		// fall through to null
	}

	return null;
}

/**
 * Whether a given category is currently allowed under a stored consent
 * decision (locked categories are always allowed).
 */
export function isCategoryAllowed(
	stored: StoredConsent | null,
	category: ConsentCategory,
): boolean {
	if (category.locked) {
		return true;
	}
	if (!stored) {
		return false;
	}
	return Boolean(stored.categories[category.key]);
}
