# BdP Scouting — WordPress theme

A standalone WordPress theme converted from the **`bdp_template`** TYPO3
sitepackage (this repository, `cd-2026` redesign branch) built for the
*Bund der Pfadfinder*innen e.V.* (BdP). It reproduces the visual design —
Jost typeface, brand colors, header/meta bar/navigation, footer, news list
& detail, group directory, cookie banner and Instagram feed — **without any
required plugin**. Every TYPO3-only piece (the address-book field renderer,
the Instagram feed, the cookie banner) is reimplemented natively in PHP/TS,
because the source extension carried no business logic for them either
(`Classes/Controller`, `Classes/Domain/Model`, `Classes/Domain/Repository`
are empty `.gitkeep` placeholders — TYPO3 delegated to third-party
extensions for behaviour and only supplied Fluid presentation markup).

## Location & why

The theme lives at `wp-theme/bdp-scouting/` in this repository, next to the
TYPO3 extension it was converted from, so the two can be diffed/compared
easily and CI for either can target its own subtree.

## Important: this branch's actual structure differs from a typical TYPO3 sitepackage

`cd-2026` does **not** use the classic `Resources/Private/Layouts/Page` +
`Partials/Frontend/Header` + Extbase `News`/`Calendarize` layout that an
older TYPO3 sitepackage might have. Instead it is built on TYPO3 v13's
**PageView** (`Resources/Private/PageView/*`) for the page shell and
**ContentBlocks** (`ContentBlocks/ContentElements/*`) for content elements,
and it uses **Tailwind CSS v4** (via `@tailwindcss/vite`) instead of a
hand-rolled SCSS design system. Two things assumed by a generic conversion
brief turned out not to exist in this branch at all, and were adjusted for:

- **No Calendarize/event templates.** There is no
  `Resources/Private/Templates/Calendarize` anywhere in `cd-2026`; the
  extension has no event/calendar feature. No `bdp_event` custom post type
  was built, since there is nothing in the source to port and inventing an
  events feature from nothing would not be a "conversion".
- **No SCSS design system.** `Resources/Private/Sources/Css/*` is plain CSS
  consumed by Tailwind v4 (`@import "tailwindcss" source(...)`,
  `@theme { --color-...: ...; }` design tokens), not a SCSS 7-1 structure.
  The theme's build therefore uses the **Tailwind CSS v4 CLI** (via
  `bunx @tailwindcss/cli`), not `sass`, to compile
  `assets/src/css/main.css` — ported verbatim from
  `Resources/Private/Sources/Css/{main,theme}.css` and
  `variants/forced-colors.css`, with only the font `url()` paths and the
  Tailwind `source()` scan path adjusted for the new file layout.
- **No `PipeBreakViewHelper`.** `Classes/ViewHelpers/` on this branch only
  has `InlineSvgViewHelper` and `UriHostViewHelper` (both re-implemented,
  see below) — there is no pipe-splitting title ViewHelper to port.
- **No bespoke cookie-banner design.**
  `Resources/Private/CookieManager/Templates/CookieFrontend/List.html` is an
  empty stub — the real banner UI lived in the separate, TYPO3-only
  `codingfreaks/cf-cookiemanager` extension (a `composer.json` "suggest"),
  which isn't part of this repo. `template-parts/cookie/banner.php` /
  `assets/src/js/entries/cookie-consent.entry.ts` are therefore a
  from-scratch, minimal, dependency-free banner in the site's brand colors
  rather than a port.
- **No address list/filter JS.** `Resources/Private/Sources/JavaScript`
  only contains `menu/menu.js`, `menu/animation.js` and
  `modules/resize-manager.js` — there is no `address/list.js` or
  `address/lv.js` in this branch to port; the group directory
  (`archive-bdp_group.php`) is server-rendered only.

## Structure

```
wp-theme/bdp-scouting/
├── style.css                  # WP theme header block
├── functions.php               # bootstraps inc/*.php
├── header.php / footer.php     # page shell (see PageView mapping below)
├── index.php / single.php      # News list / detail  -> WP core `post`
├── page.php                    # generic page -> the_content() (Gutenberg)
├── archive-bdp_group.php       # Group/team directory list
├── single-bdp_group.php        # Group/team directory detail
├── template-parts/
│   ├── news/list-item.php
│   ├── address/team-item.php
│   ├── content-blocks/headerslider.php
│   ├── instagram/feed.php
│   └── cookie/banner.php
├── inc/
│   ├── setup.php               # theme supports, nav menus, Customizer
│   ├── enqueue.php              # asset loading (see Build below)
│   ├── nav-walker.php           # dropdown-aware Walker_Nav_Menu
│   ├── cpt-bdp-group.php        # "bdp_group" CPT (tt_address replacement)
│   ├── instagram.php            # native Instagram Graph API fetch + cache
│   ├── cookie-consent.php       # cookie category config
│   ├── template-tags.php        # inline-SVG / uri-host / nav helpers
│   └── block-patterns.php       # ContentBlocks -> Gutenberg block patterns
├── assets/
│   ├── src/css/                 # ported Tailwind v4 source (theme.css, …)
│   ├── src/js/{entries,modules}/ # TypeScript (see JS/TS below)
│   ├── fonts/Jost/               # ported variable font files
│   ├── images/                   # ported brand SVGs, icons, clip masks
│   └── build/                    # generated by `bun run build` (gitignored)
├── scripts/build.ts             # bun-based build (replaces vite.config.js)
├── tests/php/                   # PHPUnit + Brain Monkey
├── tests/ts/                    # bun test
├── package.json / bun.lock
├── composer.json / composer.lock
├── tsconfig.json
└── phpunit.xml.dist
```

## TYPO3 → WordPress mapping

| TYPO3 (`cd-2026`) | WordPress |
| --- | --- |
| `Resources/Private/PageView/Layouts/Default.html` + `Partials/Header.html` | `header.php` |
| `Resources/Private/PageView/Partials/Footer.html` + `MobileMenuPanel.html` | `footer.php` |
| `mainnavigation` (doktype `1775308392` "dropdown-group" pages become dropdowns) | `inc/nav-walker.php` — any menu item **with children** becomes a dropdown button; no special doktype needed since WP menus don't have page types |
| `metaleftnavigation`, `footernavigation` | `wp_nav_menu()` calls against the `meta` / `footer` theme locations |
| `Configuration/Sets/SitePackage/settings.definitions.yaml` (`pfadfinden.settings`, `pfadfinden.contact`) | Customizer settings in `inc/setup.php` (`bdp_infotype`, `bdp_contact_*`) |
| `Classes/ViewHelpers/InlineSvgViewHelper.php` | `bdp_scouting_inline_svg()` in `inc/template-tags.php` |
| `Classes/ViewHelpers/UriHostViewHelper.php` | `bdp_scouting_uri_host()` in `inc/template-tags.php` |
| `Resources/Private/News/Templates/News/{List,Detail}.html` (georgringer/news, no custom logic) | WP core `post` type: `index.php` / `single.php` |
| `Resources/Private/Address/Templates/Address/List.html` "team" mode + `Partials/Team/ListItem.html` (tt_address, no custom logic) | `bdp_group` CPT (`inc/cpt-bdp-group.php`) + `archive-bdp_group.php` / `single-bdp_group.php` |
| `Resources/Private/InstagramBusiness/Templates/Post/List.html` | `template-parts/instagram/feed.php` + `inc/instagram.php` (native fetch/cache) |
| `Resources/Private/CookieManager/*` (empty stub; see above) | `template-parts/cookie/banner.php` + `inc/cookie-consent.php` (native, from scratch) |
| `ContentBlocks/ContentElements/*` (13 Fluid-only elements: text, image, textmedia, table, text-left-image, text-top-image, text-image-mask, text-images-mask, text-mask, text-mask-image, quote-image-text-mask, small-cta-image, header-block) | Gutenberg core blocks (paragraph/image/media-text/columns/table/quote/cover) composed by editors; two representative layouts (`small-cta-image`, `text-left-image`) are provided as registered **block patterns** in `inc/block-patterns.php`. The remaining 11 variants differ only in Tailwind layout/mask classes with no unique markup or logic, so they are not each reimplemented as a separate PHP template — see the "content elements" note above for why. |
| `ContentBlocks/ContentElements/headear-slider` | `template-parts/content-blocks/headerslider.php` (Swiper) |
| `ContentBlocks/PageTypes/dropdown-group` | Not needed — see nav-walker row above |

## Build (bun, no Vite/pnpm)

```bash
bun install
bun run build      # compiles Tailwind CSS + bundles every *.entry.ts
bun run dev        # same, in --watch mode
bun run typecheck  # tsc --noEmit
bun test           # TypeScript unit tests
```

`scripts/build.ts`:

1. Runs the **Tailwind CSS v4 CLI** (`@tailwindcss/cli`) over
   `assets/src/css/main.css` → `assets/build/Css/main.css`.
2. Runs `Bun.build()` over every `assets/src/js/entries/*.entry.ts` (IIFE
   bundles, one per entry, minified for production) → `assets/build/Js/*.js`
   (the `.entry` suffix is stripped so `functions.php` can enqueue stable
   file names).

`assets/build/` is gitignored — run `bun install && bun run build` after
cloning, exactly as you would `npm install && npm run build` for the
original Vite setup.

## JavaScript → TypeScript

Every file under the original
`Resources/Private/Sources/JavaScript/**` was ported to TypeScript with the
same behaviour, under `assets/src/js/`:

| Original | Ported to |
| --- | --- |
| `Entry/Main.entry.js` | `entries/main.entry.ts` |
| `Entry/Headerslider.entry.js` | `entries/headerslider.entry.ts` |
| `Entry/InstagramBusiness.entry.js` | `entries/instagram.entry.ts` |
| `Entry/Cookieman.entry.js` (MicroModal/Accordion, unrelated to this branch's actual empty cookie template) | replaced by `entries/cookie-consent.entry.ts` (native banner, no dependency) |
| `menu/menu.js` | `modules/menu.ts` |
| `menu/animation.js` | `modules/transition.ts` |
| `modules/resize-manager.js` | `modules/resize-manager.ts` |

Swiper ships its own TypeScript types, so no `@types/swiper` shim is
needed. `bun test` covers the DOM-free pure-logic modules
(`cookie-consent-state.ts`, `resize-manager.ts` singleton behaviour);
`transition.ts` and `menu.ts` are DOM-driven and are exercised manually via
`bun run dev` + a browser, the same way the original vanilla-JS menu code
was.

## PHP tests

```bash
composer install
composer test    # = vendor/bin/phpunit
```

Uses PHPUnit 11 + [Brain Monkey](https://brain-wp.github.io/BrainMonkey/) to
stub WordPress core functions, so the suite runs without a WordPress
install. Covered: `bdp_scouting_group_meta_fields()` /
`bdp_scouting_save_group_meta()` (CPT meta), `bdp_scouting_inline_svg()` /
`bdp_scouting_uri_host()` / `bdp_scouting_infotype_label()`,
`bdp_scouting_cookie_categories()`, and the Instagram token/cache helpers
(`bdp_scouting_instagram_feed_is_active()`,
`bdp_scouting_normalize_instagram_item()`,
`bdp_scouting_get_instagram_feed()`).

## No plugins required — optional free alternatives

Every feature works out of the box with no plugin. If a site would rather
manage one of these with a dedicated plugin instead of the native code
here, these free options are drop-in compatible with the equivalent
feature:

| Feature | Native implementation | Optional plugin |
| --- | --- | --- |
| Events / calendar | *(not built — the source TYPO3 extension has no Calendarize templates on this branch; nothing to convert)* | **The Events Calendar** |
| Group / address directory | `bdp_group` CPT (`inc/cpt-bdp-group.php`) | **Pods** |
| Instagram feed | `inc/instagram.php` (Graph API token via Settings API → **Settings → General**, cached in a transient for one hour) | **Instagram Feed by Smash Balloon** |
| Cookie consent banner | `inc/cookie-consent.php` + `template-parts/cookie/banner.php` | **GDPR Cookie Consent** |

### Instagram setup (native)

1. Create a long-lived **Instagram Graph API** access token for the
   account's connected Instagram Business/Creator profile.
2. Paste it into **wp-admin → Settings → General → Instagram Access
   Token**.
3. Call `get_template_part( 'template-parts/instagram/feed' )` anywhere in
   a template. The feed is cached in a transient for one hour
   (`inc/instagram.php`); a failed request is cached for 5 minutes to avoid
   hammering a broken token.
