import Mmenu from './menu-mmenu';

/**
 * Ported 1:1 from Resources/Private/Source/JavaScript/modules/menu.js
 * Wires the mobile hamburger toggle to the mmenu-js off-canvas menu.
 */
document.addEventListener('DOMContentLoaded', function () {
  const mobileMenuToggler = document.getElementById('mobile-bdp-menu-toggler');
  if (!mobileMenuToggler) return;

  const menu = new Mmenu(
    '#pfadfinden-menu',
    {
      theme: 'black',
      offCanvas: {
        position: 'right-front',
      },
      navbar: {
        title: '&nbsp;',
      },
    },
    {
      offCanvas: {
        clone: true,
        page: {
          selector: 'body > .page',
        },
      },
    }
  );

  const api = menu.API;
  mobileMenuToggler.setAttribute('aria-controls', menu.node.menu.id);
  mobileMenuToggler.addEventListener('click', function (event) {
    const open = mobileMenuToggler.classList.contains('is-active');
    if (open) {
      mobileMenuToggler.setAttribute('aria-expanded', 'false');
      mobileMenuToggler.classList.remove('mobile-bdp-menu-toggler--active');
      const openLabel = mobileMenuToggler.dataset.open;
      if (openLabel) mobileMenuToggler.setAttribute('aria-label', openLabel);
      api.close();
    } else {
      mobileMenuToggler.setAttribute('aria-expanded', 'true');
      const closeLabel = mobileMenuToggler.dataset.close;
      if (closeLabel) mobileMenuToggler.setAttribute('aria-label', closeLabel);
      mobileMenuToggler.classList.add('mobile-bdp-menu-toggler--active');
      api.open();
    }

    event.preventDefault();
  });
});
