/**
 * Header navigation behaviour: desktop dropdown toggles, mobile off-canvas
 * panel, and animated mobile submenus.
 *
 * Ported from Resources/Private/Sources/JavaScript/menu/menu.js.
 */

import resizeManager from "./resize-manager";
import { Transition } from "./transition";

let activeSubmenu = "";

function mobileSideNavigation(): void {
	const mobileSidebar = document.getElementById("mobile-sidebar");
	if (!mobileSidebar) return;

	if (window.innerWidth <= 1024) {
		const open = !mobileSidebar.classList.contains("hidden");
		if (open) {
			document.body.style.setProperty("overflow", "hidden");
		}
	} else {
		document.body.style.removeProperty("overflow");
	}
}

export function initMenu(): void {
	const mainMenu = document.getElementById("main-menu");
	if (mainMenu) {
		mainMenu.addEventListener("click", (event) => {
			const target = event.target as HTMLElement;
			const tid = target.dataset?.tid;
			if (!tid) return;

			activeSubmenu = tid !== activeSubmenu ? tid : "";
			event.preventDefault();

			mainMenu.querySelectorAll<HTMLButtonElement>("button.group\\/navbutton").forEach((el) => {
				el.setAttribute("aria-expanded", activeSubmenu !== "" && el.dataset.tid === activeSubmenu ? "true" : "false");
			});
		});
	}

	const mobileMenuToggler = document.getElementById("mobile-main-menu-toggle");
	const mobileSidebar = document.getElementById("mobile-sidebar");
	if (mobileMenuToggler && mobileSidebar) {
		mobileMenuToggler.addEventListener("click", (event) => {
			const open = !mobileSidebar.classList.contains("hidden");
			if (open) {
				mobileMenuToggler.setAttribute("aria-expanded", "false");
				mobileSidebar.classList.add("hidden");
				document.body.style.removeProperty("overflow");
			} else {
				mobileMenuToggler.setAttribute("aria-expanded", "true");
				mobileSidebar.classList.remove("hidden");
				document.body.style.setProperty("overflow", "hidden");
			}
			event.preventDefault();
		});
	}

	const mobileMenu = document.getElementById("mobile-menu");
	if (mobileMenu) {
		const animations = new Map<string, Transition>();
		mobileMenu.querySelectorAll<HTMLElement>(".m-submenu").forEach((el) => {
			if (el.dataset.sid) {
				animations.set(
					el.dataset.sid,
					new Transition(
						el,
						"mobile-dropdown",
						"overflow-hidden grid grid-rows-[0fr]",
						"transition-[grid-template-rows] duration-400 ease-in",
						"overflow-hidden grid grid-rows-[1fr]",
						"overflow-hidden grid grid-rows-[1fr]",
						"transition-[grid-template-rows] duration-400 ease-out",
						"overflow-hidden grid grid-rows-[0fr]",
					),
				);
			}
		});

		mobileMenu.addEventListener("click", (event) => {
			const target = event.target as HTMLElement;
			const tid = target.dataset?.tid;
			if (!tid) return;

			activeSubmenu = tid !== activeSubmenu ? tid : "";
			event.preventDefault();

			mobileMenu.querySelectorAll<HTMLButtonElement>("button.group\\/mnavbutton").forEach((el) => {
				const animation = el.dataset.tid ? animations.get(el.dataset.tid) : undefined;
				if (!animation) return;

				if (activeSubmenu !== "" && el.dataset.tid === activeSubmenu) {
					el.setAttribute("aria-expanded", "true");
					animation.enter();
				} else {
					el.setAttribute("aria-expanded", "false");
					if (animation.el.style.display !== "none") {
						animation.leave();
					}
				}
			});
		});
	}

	mobileSideNavigation();
	resizeManager.registerElement(document.body, mobileSideNavigation);
}
