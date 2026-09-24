// First-party global augmentations (not vendor package types — see
// vendor.d.ts for those).

// cookieconsent/om.ts reads/writes window.dataLayer (GTM) without
// declaring it, guarding every read with `window.dataLayer || []` — so
// the property is genuinely optional here, not just absent from the types.
// This file is a script (no import/export), so a plain top-level interface
// merges directly into the global `Window` type.
interface Window {
    dataLayer?: unknown[];
}
