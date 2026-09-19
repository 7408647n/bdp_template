BdP Template – WordPress-Theme
==============================================================

Dieses Repository enthält das BdP-Webseiten-Layout als eigenständiges
**WordPress-Theme**. Es ist die Portierung des ursprünglichen
`pfadfinden/bdp_template`-TYPO3-Sitepackage (siehe Git-Historie vor diesem
Branch) auf WordPress: gleiches Design, gleiche SCSS-Basis, gleiche
Fonts/Icons – aber ohne TYPO3, Extbase oder Fluid.

Das Theme liegt bewusst im Repository-Root (nicht unter `wp-theme/`), weil
dieses Repository ab diesem Branch ausschließlich das WordPress-Theme
enthält.

## Theme-Struktur

```
style.css                  Theme-Header (Name, Autor, Lizenz, Version)
functions.php              Theme-Setup, Enqueues, Includes
inc/
  pipe-break.php           Port von Classes/ViewHelpers/PipeBreakViewHelper.php
  post-types.php           Custom Post Types bdp_event, bdp_group + Meta
  instagram.php            Nativer Instagram-Graph-API-Fetcher + Settings-Seite
  cookie-consent.php       Native Cookie-Consent-Konfiguration/Panel-Markup
  customizer.php           Customizer-Felder für Kontakt/Social-Links
header.php / footer.php    Seiten-Rahmen (Port von Layouts/Page/Default.html
                            + Partials/Frontend/Header, Partials/Page/*)
index.php                  News-Liste (WordPress `post`-Archiv)
single.php                 News-Detail (WordPress `post`)
page.php                   Generische Seite
archive-bdp_event.php      Termine-Liste
single-bdp_event.php       Termin-Detail
archive-bdp_group.php      Gruppen-/Landesverbands-Verzeichnis (Liste)
single-bdp_group.php       Gruppen-/Landesverbands-Detail
template-parts/
  content-elements/*.php   Markup-Referenz für die alten TYPO3-Content-Elemente
                            (Text, Bild, Text+Bild, CTA) als Block-Pattern-Vorlage
  content-news-list-item.php, pagination.php, footer-social.php,
  cookie-banner.php, instagram-feed.php
assets/
  src/scss/                1:1 übernommenes Design-System (0-functions … 9-stand-alone)
  src/ts/                  Nach TypeScript portiertes JavaScript
  fonts/, images/          Aleo/Immenhausen/Inter-Fonts, Icons, Hintergrundbild
  build/                   Generierte CSS/JS-Bundles (siehe unten)
scripts/build.ts           Build-Skript (sass + bun build)
tests/php/                 PHPUnit-Tests (Brain Monkey, keine echte WP-Installation nötig)
tests/ts/                  bun-test-Unit-Tests für reine TS-Logik
```

## Build

Voraussetzung: [Bun](https://bun.sh) ≥ 1.1.

```bash
bun install
bun run build      # kompiliert SCSS → assets/build/css/main.css
                    # bundlet jede *.entry.ts → assets/build/js/*.js
                    # kopiert das lightgallery-Stylesheet nach assets/build/vendor/

bun run dev         # wie build, aber im Watch-Modus
bun run typecheck   # tsc --noEmit
bun test            # bun-test-Unit-Tests (tests/ts)
```

`functions.php` bindet die Dateien aus `assets/build/` ein und funktioniert
nicht, ohne dass vorher `bun run build` gelaufen ist (die generierten
Dateien sind nicht Teil des Git-Repositories – bitte lokal/im CI bauen).

### PHP-Tests

```bash
composer install
composer test       # phpunit, via Brain Monkey ohne echte WordPress-Installation
```

## Mapping TYPO3 → WordPress

| TYPO3 (Original)                                   | WordPress (dieses Theme)                              |
|-----------------------------------------------------|---------------------------------------------------------|
| Seiten-Layout, Header/Footer, Hauptnavigation (mmenu)| `header.php`, `footer.php`, WP-Nav-Menüs                |
| Content-Elemente (Text, Bild, Text+Bild, CTA, …)     | Gutenberg-Core-Blöcke + Block-Patterns (`template-parts/content-elements/*.php` als Referenz) |
| `georgringer/news` (Liste/Detail)                    | WordPress-Core-Post-Type `post` (`index.php`, `single.php`) |
| `hdnet/calendarize` (Termine)                        | Custom Post Type `bdp_event` (`archive-bdp_event.php`, `single-bdp_event.php`) – keine Wiederholungslogik, da im Quell-Repo ebenfalls nicht vorhanden |
| `friendsoftypo3/tt-address` (Gruppen-/LV-Verzeichnis)| Custom Post Type `bdp_group` (`archive-bdp_group.php`, `single-bdp_group.php`) |
| Instagram-Business-Extension                         | Nativer `wp_remote_get`-Fetcher gegen die Instagram-Graph-API, Transient-Cache, Token-Eingabe unter *Einstellungen → BdP Instagram Feed* |
| `opfaff/om-cookie-manager`                            | Natives Cookie-Consent-Panel (`inc/cookie-consent.php` + `assets/src/ts/cookieconsent/*`) |
| `Classes/ViewHelpers/PipeBreakViewHelper.php`         | `bdp_pipe_break()` / `bdp_pipe_break_html()` in `inc/pipe-break.php` |

## Kein Plugin-Zwang – optionale Alternativen

Das Theme funktioniert bewusst **ohne** Plugin-Abhängigkeiten. Wer die
Pflege lieber über ein Plugin abwickeln möchte, kann stattdessen eines der
folgenden kostenlosen, aktiv gepflegten WordPress.org-Plugins einsetzen
(die eigenen CPTs/Funktionen dann deaktivieren bzw. die entsprechenden
Template-Dateien anpassen):

- **Termine**: [The Events Calendar](https://wordpress.org/plugins/the-events-calendar/)
  statt des eigenen `bdp_event`-Post-Types.
- **Gruppen-/Landesverbands-Verzeichnis**: [Pods](https://wordpress.org/plugins/pods/)
  (generisches Custom-Content-Type-Plugin) statt des eigenen `bdp_group`-Post-Types.
- **Instagram-Feed**: [Instagram Feed by Smash Balloon](https://wordpress.org/plugins/instagram-feed/)
  statt des eigenen Token-Feldes unter *Einstellungen → BdP Instagram Feed*.
- **Cookie-Consent**: [GDPR Cookie Consent](https://wordpress.org/plugins/gdpr-cookie-consent/)
  statt des eingebauten Panels.

### Instagram-Setup (native Variante)

1. Ein Instagram-Business- oder Creator-Konto mit einer verknüpften
   Facebook-Seite anlegen/verwenden.
2. In den [Meta for Developers](https://developers.facebook.com/) eine App
   mit dem Produkt „Instagram Graph API“ erstellen.
3. Einen **langlebigen** Access Token für das Konto generieren (siehe
   Instagram-Graph-API-Dokumentation zu `ig_exchange_token`).
4. Den Token unter *Einstellungen → BdP Instagram Feed* im
   WordPress-Backend eintragen.
5. `template-parts/instagram-feed.php` per `get_template_part()` in eine
   Seite oder ein Block-Pattern einbinden. Die Antwort wird eine Stunde
   lang in einem Transient zwischengespeichert.

## Release Management

Dieses Theme folgt weiterhin [semantic versioning](https://semver.org/) in
`style.css`:

- PATCH (z. B. 1.0.0 → 1.0.1): kleine Bugfixes ohne wesentliche Änderungen,
- MINOR (z. B. 1.0.0 → 1.1.0): neue Funktionen ohne Breaking Changes,
- MAJOR (z. B. 1.0.0 → 2.0.0): Breaking Changes.
