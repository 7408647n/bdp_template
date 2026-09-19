import Masonry from 'masonry-layout';
import lightGallery from 'lightgallery';

/**
 * Ported 1:1 from Resources/Private/Source/JavaScript/news/news-detail.js
 * lightgallery's bundled CSS is imported globally via main.scss instead of
 * a JS side-effect import (see assets/src/scss/9-stand-alone/news-detail.scss).
 */
const mediaContainer = document.querySelector('.news-article__media');
if (mediaContainer) {
  new Masonry(mediaContainer, {
    itemSelector: '.news-article__media__item',
    columnWidth: '.news-article__media__item',
    percentPosition: true,
    transitionDuration: '0.2',
  });

  lightGallery(mediaContainer, {
    selector: '.mediaelement__link',
    download: false,
    subHtml: '.mediaelement__caption',
    subHtmlSelectorRelative: true,
  });
}
