<p align="center">
    <picture>
        <source media="(prefers-color-scheme: dark)" srcset="./assets/markdown/logo_dark.svg">
        <source media="(prefers-color-scheme: light)" srcset="./assets/markdown/logo_light.svg">
        <img src="./assets/markdown/logo_light.svg" width="600" alt="contao kiss">
    </picture>
</p>

<h1 align="center">Contao-KISS</h1>

<p align="center">
    <i>Das super einfache Starter-Kit für Contao. Gebaut, um zu bleiben.</i><br>
    KISS (<i>Keep It Simple, Stupid</i>) vereint flexible Komponenten, einen klaren Design-System-Ansatz und ein
    intuitives Backend in einem Paket.
</p>

<p align="center">
    <a href="README.md">English</a> · <b>Deutsch</b>
</p>

<p align="center">
    <a href="https://packagist.org/packages/digitaledinge/contao-kiss"><img src="https://img.shields.io/packagist/v/digitaledinge/contao-kiss" alt="packagist version"/></a>
    <a href="https://packagist.org/packages/digitaledinge/contao-kiss"><img src="https://img.shields.io/packagist/dt/digitaledinge/contao-kiss?color=f47c00" alt="amount of downloads"/></a>
    <a href="https://packagist.org/packages/digitaledinge/contao-kiss"><img src="https://img.shields.io/packagist/dependency-v/digitaledinge/contao-kiss/php?color=474A8A" alt="minimum php version"></a>
    <a href="https://phpstan.org/user-guide/rule-levels"><img src="https://img.shields.io/badge/PHPStan-level%2010-516CB3" alt="phpstan level 10"></a>
</p>

---

## Beschreibung

- **Keep it simple**: entstanden aus jahrelanger Erfahrung mit Contao, einfach zu bedienen für Redakteure, einfach zu
  pflegen für Agenturen und einfach zu erweitern für Entwickler

- **Updates ohne Kopfschmerzen**
  - Contao `^5.7` und `6.*`, erweitert den Core über Twig-Vererbung, statt ihn zu überschreiben

- **User Experience**
    - Das Contao, das du kennst, man muss nichts Neues lernen
    - Keeping it simple: jede Option, die Redakteure brauchen, nichts, was im Weg steht
    - Visuelle Widgets und Grids, die du schon beim Aufbauen siehst

- **Performance first**
  - Nur das CSS, welches man braucht (siehe [Build-Tools](docs/build-tools.md))
  - Gehashte, versionierte Assets, CSS Cascade Layers und ein `browserslist`-gesteuerter Build ohne Altlasten

- **Contao erweitern**
    - Vorgestylte Twig-Komponenten und neue Inhaltselemente auf Basis der Contao-eigenen Elemente
    - Ein Framework für viele Marken und Websites, bereit für dein Design-System

- **Moderner Tech-Stack**
    - [PHP] `^8.4`, [Symfony] `^7.4 || ^8.0` und [Contao] `^5.7 || ^6.0`
    - [Vite] mit [Hot Module Replacement] und [Reprise]
    - [Tailwind CSS] 4
    - [Stimulus]
    - [Hotwired Turbo] kompatibel

- **Auf Qualität gebaut**
  - Typsichere Style-Optionen: Enum-basierte Value Objects, registriert mit einem einzigen PHP-Attribut
  - Kein Schema-Ballast: jede Style-Option liegt in einer einzigen `kiss_styles`-Spalte, gespeichert als persistenter
    Bezeichner statt als CSS-Klasse
  - Erweiterbar und überschreibbar: jedes Template austauschen, einen einzelnen Block überschreiben oder eigene
    Style-Optionen mitbringen
  - Getestet: [PHPUnit], Abhängigkeitsanalyse, eigene [Twig CS Fixer]-Regeln und Linting in der CI

> [!IMPORTANT]
> KISS funktioniert nicht out of the box. Es muss zusammen mit dem Asset-Build deines Projekts installiert werden.
> Siehe [Erste Schritte](#erste-schritte).
>
> Brauchst du Hilfe? Schreib uns an [hallo@digitaledin.ge](mailto:hallo@digitaledin.ge).

---

+ [Noch ein Contao-Framework?](#noch-ein-contao-framework)
+ [Zielgruppen](#wer-ist-die-zielgruppe-all-of-them)
    + [Agenturen](#agenturen)
    + [Redakteure](#redakteure)
    + [Entwickler](#entwickler)
+ [Features](#features)
    + [Erweiterte Contao-Elemente](#erweiterte-contao-elemente)
    + [Neue Inhaltselemente](#neue-inhaltselemente)
    + [Eigene Komponenten](#eigene-komponenten)
    + [Style-Optionen](#style-optionen)
    + [Backend](#backend)
+ [Erste Schritte](#erste-schritte)
+ [Dokumentation](#dokumentation)
+ [Individuelle Projekte und Anwendungen](#individuelle-projekte-und-anwendungen)

---

## Noch ein Contao-Framework?

Viele Themes ersetzen große Teile von Contao durch eigene Templates und eigene Logik, und jedes Contao-Update wird zum
Risiko. KISS geht den umgekehrten Weg: Es erweitert Contao und fügt sich nahtlos ein. Templates ändern sich über die
Twig-Vererbungskette, Komponenten liegen auf dem Core und Style-Optionen hängen sich in Contaos eigene DCA ein. Deine
Projekte bleiben update-sicher, heute und mit der nächsten Contao-Version. Keep it simple, stupid.

---

## Wer ist die Zielgruppe? All of them!

### Agenturen

- Nahtlose Integration in Contao: Komponenten erweitern den Core, statt ihn zu überschreiben
- Update-Kompatibilität by Design, damit Contao- und KISS-Updates einfach bleiben
- Deine Design-Systeme und Tokens, ein Framework für viele Marken und Websites
- Ein Backend, einfach gehalten, mit genau den Optionen, die Redakteure brauchen

### Redakteure

- Das Contao, das du kennst, man muss nichts Neues lernen
- Aus jahrelanger Erfahrung mit Contao entstanden, Lösungen, die jeder Contao-Nutzer sofort versteht
- Gerade genug Felder für Layout, Abstände, Farben und Typografie, nie überladen
- Gruppierte Style-Optionen, visuelle Select-Widgets und Feld-Icons für ein schnelleres, klareres Backend

### Entwickler

- Moderner Build-Stack mit Vite, Reprise und Tailwind 4
- Hot Module Replacement: Änderungen sehen, während du codest, kein Neuladen nötig
- Einsatzbereite Stimulus-Controller für interaktive Komponenten, Hotwired Turbo kompatibel
- Vorgestylte Twig-Komponenten und CSS-Erweiterungen: Cards, Media & Text, Call-to-Action, Swiper, Accordion und mehr
- Twig-Vererbung durchgehend: einen einzelnen Block überschreiben, den Rest behalten
- Style-Optionen als Value Objects, in jedem Template über die Twig-Global `styles` verfügbar
- Persistente Bezeichner statt CSS-Klassen in der Datenbank: die Klassen hinter einer Option jederzeit tauschen, ohne
  ein einziges Inhaltselement anzufassen
- Style-Optionen mit einem einzigen Attribut hinzufügen, ersetzen oder migrieren, Migrations-Helfer für schmerzfreie
  Updates inklusive
- Bereit für deine Design-Tokens und dein Design-System
- [Biome], [Stylelint] und eigene Twig CS Fixer-Regeln für konsistente Projekte
- Lässt sich sauber in deine CI und Build-Chains integrieren

---

## Features

### Erweiterte Contao-Elemente

KISS erweitert Contaos eigene Inhaltselemente und Module über die Twig-Vererbungskette:

| Element             | Erweiterung                                                                                |
|---------------------|--------------------------------------------------------------------------------------------|
| Jedes Inhaltselement | Topline, Überschrift mit Tag und Darstellung, Breite, Außen- und Innenabstand              |
| Überschrift         | Darstellung der Überschrift unabhängig vom Tag                                             |
| Text                | Call-to-Action, als Card mit Hintergrund, Größe und Variante, Umfluss aufheben usw.        |
| Elementgruppe       | Interaktives Grid-Widget, Grid mit Spalten, Abstand und Ausrichtung, Textausrichtung       |
| Hyperlink           | Icon, Icon-Position, Textausrichtung, Darstellung als Button                               |
| Download(s)         | Icon-Position, Textausrichtung, Dateigröße ausblenden, Darstellung als Button              |
| Swiper              | Slides pro Ansicht, Navigation, Pagination ausblenden, sichtbarer Überlauf                 |
| Akkordeon           | Abschnittsüberschrift mit eigenem Tag und eigener Darstellung                              |
| Player              | Seitenverhältnis für Videos                                                                |
| Galerie             | Responsives Grid anhand der Elemente pro Reihe                                             |
| Tabelle             | Vorgestylte Tabelle, Beschriftung, Kopf und Zeilen                                         |
| Formular (Include)  | Farbe, Größe und Variante der Formularfelder, von jedem ungestylten Feld geerbt; als Card  |
| Artikel             | Breite, Innenabstand, Hintergrundfarbe, Textausrichtung                                    |
| Listen-Module       | Grid mit Spalten und Abstand für Nachrichten-, Event-, FAQ- und Newsletter-Listen          |
| Nachrichten         | Nachrichten-Teaser als Cards                                                               |
| Und mehr            |                                                                                            |

### Neue Inhaltselemente

| Element              | Beschreibung                                                    |
|----------------------|-----------------------------------------------------------------|
| Media & Text         | Bild, Video oder Icon mit Überschrift, Text und Call-to-Action  |
| Media & Text Liste   | Eine Liste, ein Grid oder ein Swiper aus Media & Text Einträgen |
| Icon                 | Ein einzelnes Icon                                              |
| Icon-Liste           | Eine Liste von Icons mit Text                                   |
| Hyperlink-Liste      | Eine Liste von Links                                            |
| Alert                | Info-, Erfolgs-, Warn- und Fehlermeldungen                      |
| Badge                | Labels und Tags                                                 |

### Eigene Komponenten

Vorgestylte Twig-Komponenten in `kiss_component/` für deine eigenen Templates: Call-to-Action, Alert, Badge, Icon,
Bild, Video, Media & Text und Switch. Stimulus-Controller für einen Theme-Toggle, Popovers, Range- und
Zahlen-Eingaben sind ebenfalls dabei.

### Style-Optionen

Jede Option wird als persistenter Bezeichner in einer einzigen `kiss_styles`-Spalte gespeichert und über die
Twig-Global `styles` zu CSS-Klassen aufgelöst:

- Layout: Inhaltsbreite, Grid-Spalten, Abstand und Querausrichtung, Element-Layout
- Abstände: Außen- und Innenabstand oben und unten
- Farben: Hintergrund und semantische Farben
- Typografie: Textausrichtung, Textdarstellung
- Komponenten: Größe, Variante, Call-to-Action-Typ, -Farbe, -Größe und -Form, Icon-Position und -Stil

### Backend

- Visuelle Select-Widgets mit Feld-Icons
- Gruppierte Legenden für Layout-, Darstellungs-, Grid- und Card-Einstellungen
- KISS-Felder für ausgewählte Inhaltselemente in den Systemeinstellungen ausblenden
- Insert-Tags für farbigen Text, `{{sup}}` und `{{sub}}`

---

## Erste Schritte

1. **Bundle installieren**

   ```bash
   composer require digitaledinge/contao-kiss
   ```

2. **npm-Paket** in die `package.json` deines Projekts aufnehmen

   ```json
   {
       "dependencies": {
           "@hotwired/stimulus": "^3.2"
       },
       "devDependencies": {
           "@digitaledinge/contao-kiss": "file:vendor/digitaledinge/contao-kiss/build"
       },
       "scripts": {
           "dev-server": "vite",
           "watch": "vite build --mode development --watch",
           "build": "vite build"
       }
   }
   ```

3. **`vite.config.mjs` anlegen**

   ```js
   import { buildVite } from '@digitaledinge/contao-kiss/vite';

   export default buildVite();
   ```

4. **Einstiegspunkte anlegen**

   `layout/app.js` registriert die Stimulus-Controller, `layout/css/index.css` importiert Tailwind und die KISS-Styles.
   `layout/fonts/` und `layout/css/assets/` müssen ebenfalls existieren:

   ```js
   import { Application } from '@hotwired/stimulus';
   import { ThemeController } from '@digitaledinge/contao-kiss';

   Application.start().register('theme', ThemeController);
   ```

   ```css
   @layer theme, base, typography, components, utilities;

   @import "tailwindcss";
   @import "@digitaledinge/contao-kiss/css/index";

   @source "../../vendor/digitaledinge/*/contao/templates";
   ```

5. **Bauen**

   ```bash
   npm install && npm run build
   ```

Die vollständige Projektstruktur, Aliase, den Dev-Server mit HMR und mehr beschreibt
[docs/build-tools.md](docs/build-tools.md) (Englisch).

---

## Dokumentation

Die Dokumentation ist auf Englisch.

| Dokument                                 | Inhalt                                                           |
|------------------------------------------|------------------------------------------------------------------|
| [Build tools](docs/build-tools.md)       | Projekt-Setup, Vite, Tailwind, Stimulus, Dev-Server mit HMR      |
| [Style options](docs/style-options.md)   | Style-Optionen nutzen, hinzufügen, ersetzen und migrieren        |
| [Twig templates](docs/twig-templates.md) | Vererbungskette, Blöcke, Attribut-Hooks und Komponenten          |
| [Best practices](docs/best-practices.md) | Linting und CI für dein Projekt                                  |
| [Legacy page layout](docs/legacy.md)     | KISS mit `fe_page.html.twig` nutzen                              |
| [Development](docs/development.md)       | An contao-kiss selbst arbeiten                                   |
| [Changelog](CHANGELOG.md)                | Release Notes                                                    |

---

## Individuelle Projekte und Anwendungen

Du brauchst ein individuelles Projekt oder eine Anwendung, oder willst KISS mit deinem Design-System und deinen
Tokens integrieren? Schreib uns an [hallo@digitaledin.ge](mailto:hallo@digitaledin.ge).

[php]: https://www.php.net
[symfony]: https://symfony.com
[contao]: https://contao.org
[vite]: https://vite.dev
[hot module replacement]: https://vite.dev/guide/features#hot-module-replacement
[reprise]: https://symfony.com/reprise
[tailwind css]: https://tailwindcss.com
[stimulus]: https://stimulus.hotwired.dev
[hotwired turbo]: https://turbo.hotwired.dev
[phpunit]: https://phpunit.de
[twig cs fixer]: https://twigcsfixer.github.io
[biome]: https://biomejs.dev
[stylelint]: https://stylelint.io
