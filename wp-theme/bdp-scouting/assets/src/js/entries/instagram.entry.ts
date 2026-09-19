/**
 * Instagram feed slider.
 *
 * Ported from Resources/Private/Sources/Entry/InstagramBusiness.entry.js.
 */

import Swiper from "swiper";
import { Navigation } from "swiper/modules";
import "swiper/css";
import "swiper/css/navigation";

// eslint-disable-next-line no-new
new Swiper(".ct-instagram", {
	modules: [Navigation],
	loop: false,
	slidesPerView: "auto",
	spaceBetween: 28,
	navigation: {
		nextEl: ".swiper-button-next",
		prevEl: ".swiper-button-prev",
	},
});
