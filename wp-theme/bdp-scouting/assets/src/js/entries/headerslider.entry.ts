/**
 * Header image slider (hero teaser / "headear-slider" content block).
 *
 * Ported from Resources/Private/Sources/Entry/Headerslider.entry.js.
 */

import Swiper from "swiper";
import { A11y, Autoplay, Navigation, Pagination } from "swiper/modules";
import "swiper/css";
import "swiper/css/navigation";
import "swiper/css/pagination";
import "swiper/css/autoplay";
import "swiper/css/a11y";

const headerSliderElement = document.getElementsByClassName("ct-headerslider")[0] as HTMLElement | undefined;

const autoplayOptions = {
	delay: 15000,
	disableOnInteraction: false,
};

const autoplay = headerSliderElement?.dataset.edit === "true" ? false : autoplayOptions;

// eslint-disable-next-line no-new
new Swiper(".ct-headerslider", {
	modules: [Navigation, Pagination, Autoplay, A11y],
	loop: true,
	a11y: true,
	autoplay,
	pagination: {
		el: ".swiper-pagination",
	},
	navigation: {
		nextEl: ".swiper-button-next",
		prevEl: ".swiper-button-prev",
	},
});
