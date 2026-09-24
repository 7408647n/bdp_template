import Swiper from 'swiper';
import { Navigation, Pagination, Autoplay, A11y } from 'swiper/modules';
import type { SwiperOptions } from 'swiper/types';
// import Swiper and modules styles
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import 'swiper/css/autoplay';
import 'swiper/css/a11y';

const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

// init one Swiper per slider, so every slider decides about autoplay on its own
document.querySelectorAll<HTMLElement>('.ct-headerslider').forEach(function (sliderElement) {
    const hasMultipleSlides = sliderElement.querySelectorAll('.swiper-slide').length > 1;
    // no rotation in the backend (edit mode), for a single slide or when the user prefers reduced motion
    const autoplay: SwiperOptions['autoplay'] = hasMultipleSlides && sliderElement.dataset.edit !== 'true' && !prefersReducedMotion
        ? {
            delay: 15000,
            disableOnInteraction: false,
            pauseOnMouseEnter: true,
        }
        : false;

    const swiper = new Swiper(sliderElement, {
        // configure Swiper to use modules
        modules: [Navigation, Pagination, Autoplay, A11y],
        loop: hasMultipleSlides,
        a11y: true,
        autoplay: autoplay,
        pagination: {
            el: sliderElement.querySelector<HTMLElement>('.swiper-pagination'),
        },
        navigation: {
            nextEl: sliderElement.querySelector<HTMLElement>('.swiper-button-next'),
            prevEl: sliderElement.querySelector<HTMLElement>('.swiper-button-prev'),
        }
    });

    // WCAG 2.2.2: auto-rotating content needs a way to pause it
    const toggle = sliderElement.querySelector<HTMLButtonElement>('.ct-headerslider__toggle');
    if (!autoplay || !toggle) {
        return;
    }
    const pauseIcon = toggle.querySelector('.ct-headerslider__icon-pause');
    const playIcon = toggle.querySelector('.ct-headerslider__icon-play');
    const renderToggle = function () {
        const running = swiper.autoplay.running;
        toggle.setAttribute('aria-label', (running ? toggle.dataset.labelPause : toggle.dataset.labelPlay) ?? '');
        pauseIcon?.toggleAttribute('hidden', !running);
        playIcon?.toggleAttribute('hidden', running);
    };

    toggle.addEventListener('click', function () {
        if (swiper.autoplay.running) {
            swiper.autoplay.stop();
        } else {
            swiper.autoplay.start();
        }
    });
    // stop rotating once keyboard focus moves into the slider (the toggle itself decides on its own)
    sliderElement.addEventListener('focusin', function (event) {
        if (event.target !== toggle) {
            swiper.autoplay.stop();
        }
    });
    swiper.on('autoplayStart', renderToggle);
    swiper.on('autoplayStop', renderToggle);
    toggle.hidden = false;
    renderToggle();
});
