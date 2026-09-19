import Masonry from 'masonry-layout';

/**
 * Ported 1:1 from Resources/Private/Source/JavaScript/address/list.js
 */
const items = document.querySelectorAll<HTMLElement>('.ce-address__list__items');
items.forEach((element) => {
  new Masonry(element, {
    itemSelector: '.ce-address__list__items__item',
    columnWidth: '.ce-address__list__items__item',
    percentPosition: true,
    transitionDuration: '0.2',
  });
});
