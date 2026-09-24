// lightgallery ships its own types, but the core settings interface omits
// `subHtml` (a documented, supported core option: a CSS selector for
// per-slide caption HTML) even though `subHtmlSelectorRelative` right next
// to it is declared. Augmenting the existing interface rather than casting
// to `any`. The `import` below is what makes this a module augmentation
// instead of a fresh (and conflicting) ambient module declaration.
import 'lightgallery/lg-settings';

declare module 'lightgallery/lg-settings' {
    interface LightGalleryCoreSettings {
        subHtml?: string;
    }
}
