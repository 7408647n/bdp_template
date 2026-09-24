// Ambient declarations for third-party packages that ship no TypeScript
// types. Kept intentionally minimal — just enough surface for how this
// codebase actually uses each package.

declare module 'masonry-layout' {
    export default class Masonry {
        constructor(element: Element | string, options?: Record<string, unknown>);
    }
}

declare module 'mmenu-js/dist/core/oncanvas/mmenu.oncanvas' {
    export default class Mmenu {
        static addons: Record<string, unknown>;

        constructor(
            selector: string | Element,
            options?: Record<string, unknown>,
            configs?: Record<string, unknown>
        );

        API: {
            open(): void;
            close(): void;
        };
        node: { menu: HTMLElement };
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
