/**
 * Cookie consent banner behaviour.
 *
 * Native implementation (see inc/cookie-consent.php for why there is no
 * TYPO3 markup/JS to port here). Reads the category list injected by
 * wp_localize_script() as `window.bdpCookieConsent`.
 */

import {
	acceptAll,
	acceptNecessaryOnly,
	type ConsentCategory,
	type ConsentState,
	parseConsent,
	serializeConsent,
} from "../modules/cookie-consent-state";

declare global {
	interface Window {
		bdpCookieConsent?: {
			cookieName: string;
			categories: ConsentCategory[];
		};
	}
}

function applyConsent(state: ConsentState): void {
	document.dispatchEvent(new CustomEvent("bdp-cookie-consent-changed", { detail: state }));
}

function init(): void {
	const config = window.bdpCookieConsent;
	const banner = document.getElementById("bdp-cookie-banner");
	if (!config || !banner) {
		return;
	}

	const storageKey = `bdp_cookie_consent_${config.cookieName}`;
	const existing = parseConsent(localStorage.getItem(storageKey));

	if (existing) {
		applyConsent(existing.categories);
		return;
	}

	banner.hidden = false;
	banner.classList.remove("hidden");

	const save = (state: ConsentState) => {
		localStorage.setItem(storageKey, serializeConsent(state));
		banner.hidden = true;
		banner.classList.add("hidden");
		applyConsent(state);
	};

	banner.querySelector("[data-bdp-cookie-accept-all]")?.addEventListener("click", () => {
		save(acceptAll(config.categories));
	});

	banner.querySelector("[data-bdp-cookie-accept-none]")?.addEventListener("click", () => {
		save(acceptNecessaryOnly(config.categories));
	});

	const settingsToggle = banner.querySelector<HTMLButtonElement>("[data-bdp-cookie-settings]");
	const categoriesPanel = document.getElementById("bdp-cookie-categories");
	const saveButton = banner.querySelector<HTMLButtonElement>("[data-bdp-cookie-save]");

	settingsToggle?.addEventListener("click", () => {
		const expanded = settingsToggle.getAttribute("aria-expanded") === "true";
		settingsToggle.setAttribute("aria-expanded", String(!expanded));
		categoriesPanel?.classList.toggle("hidden", expanded);
		if (categoriesPanel) {
			categoriesPanel.hidden = expanded;
		}
		if (saveButton) {
			saveButton.hidden = expanded;
		}
	});

	saveButton?.addEventListener("click", () => {
		const state: ConsentState = {};
		banner.querySelectorAll<HTMLInputElement>("[data-bdp-cookie-category]").forEach((input) => {
			const key = input.dataset.bdpCookieCategory;
			if (key) {
				state[key] = input.checked;
			}
		});
		save(state);
	});
}

document.addEventListener("DOMContentLoaded", init);
