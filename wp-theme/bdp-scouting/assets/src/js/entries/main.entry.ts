/**
 * Main entry point, loaded on every page.
 *
 * Ported from Resources/Private/Sources/Entry/Main.entry.js.
 */

import { initMenu } from "../modules/menu";

document.addEventListener("DOMContentLoaded", () => {
	initMenu();
});
