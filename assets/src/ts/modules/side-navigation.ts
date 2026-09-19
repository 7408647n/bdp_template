import resizeManager from './resize-manager';

/**
 * Ported 1:1 from Resources/Private/Source/JavaScript/modules/side-navigation.js
 * Moves the side navigation between the main nav column and just above the
 * footer, depending on viewport width.
 */
function mobileSideNavigation(entry: { target: Element }): void {
  if (window.innerWidth <= 768) {
    const footer = document.querySelector('.page__footer');
    footer?.parentNode?.insertBefore(entry.target, footer);
  } else {
    document.querySelector('.page__navigation')?.appendChild(entry.target);
  }
}

document.addEventListener('DOMContentLoaded', () => {
  const node = document.querySelector('.page__navigation__side');
  if (node) {
    mobileSideNavigation({ target: node });
    resizeManager.registerElement(node, mobileSideNavigation as unknown as (entry: ResizeObserverEntry) => void);
  }
});
