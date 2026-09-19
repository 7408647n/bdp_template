import Masonry from 'masonry-layout';

/**
 * Ported 1:1 from Resources/Private/Source/JavaScript/news/news-list.js
 */
const container = document.querySelector('.news-list__items');
if (container) {
  new Masonry(container, {
    itemSelector: '.news-list__items__item',
    columnWidth: '.news-list__items__item',
    percentPosition: true,
    transitionDuration: '0.2',
  });
}
