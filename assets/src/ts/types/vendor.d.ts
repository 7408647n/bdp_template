/**
 * Minimal local type shims for third-party libraries that ship no
 * `@types` package (mirrors what the original TYPO3 sitepackage used
 * via plain JS imports). Kept intentionally loose.
 */

declare module 'masonry-layout' {
  interface MasonryOptions {
    itemSelector?: string;
    columnWidth?: string | number;
    percentPosition?: boolean;
    transitionDuration?: string | number;
    [key: string]: unknown;
  }

  export default class Masonry {
    constructor(element: Element | string, options?: MasonryOptions);
    layout(): void;
    destroy(): void;
  }
}

declare module 'lightgallery' {
  interface LightGalleryOptions {
    selector?: string;
    download?: boolean;
    subHtml?: string;
    subHtmlSelectorRelative?: boolean;
    [key: string]: unknown;
  }

  interface LightGalleryInstance {
    destroy(): void;
  }

  export default function lightGallery(
    element: Element,
    options?: LightGalleryOptions
  ): LightGalleryInstance;
}

declare module 'mmenu-js/dist/core/oncanvas/mmenu.oncanvas' {
  export default class Mmenu {
    static addons: Record<string, unknown>;
    API: {
      open(): void;
      close(): void;
    };
    node: {
      menu: HTMLElement;
      [key: string]: unknown;
    };
    constructor(
      selector: string,
      options?: Record<string, unknown>,
      configs?: Record<string, unknown>
    );
  }
}

declare module 'mmenu-js/dist/core/offcanvas/mmenu.offcanvas' {
  const offcanvas: unknown;
  export default offcanvas;
}
declare module 'mmenu-js/dist/core/scrollbugfix/mmenu.scrollbugfix' {
  const scrollBugFix: unknown;
  export default scrollBugFix;
}
declare module 'mmenu-js/dist/core/theme/mmenu.theme' {
  const theme: unknown;
  export default theme;
}
declare module 'mmenu-js/dist/addons/backbutton/mmenu.backbutton' {
  const backButton: unknown;
  export default backButton;
}

interface Window {
  Mmenu?: unknown;
  dataLayer?: Record<string, unknown>[];
}
