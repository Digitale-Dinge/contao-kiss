<p align="center">
    <picture>
        <source media="(prefers-color-scheme: dark)" srcset="./assets/markdown/logo_dark.svg">
        <source media="(prefers-color-scheme: light)" srcset="./assets/markdown/logo_light.svg">
        <img src="./assets/markdown/logo_light.svg" width="600" alt="contao kiss">
    </picture>
</p>

<h1 align="center">Contao-KISS</h1>

<p align="center">
    <i>The super simple starter kit for Contao. Built to last.</i><br>
    KISS (<i>Keep It Simple, Stupid</i>) brings flexible components, a clean design system approach and an intuitive
    back end together in one package.
</p>

<p align="center">
    <b>English</b> · <a href="README.de.md">Deutsch</a>
</p>

<p align="center">
    <a href="https://packagist.org/packages/digitaledinge/contao-kiss"><img src="https://img.shields.io/packagist/v/digitaledinge/contao-kiss" alt="packagist version"/></a>
    <a href="https://packagist.org/packages/digitaledinge/contao-kiss"><img src="https://img.shields.io/packagist/dt/digitaledinge/contao-kiss?color=f47c00" alt="amount of downloads"/></a>
    <a href="https://packagist.org/packages/digitaledinge/contao-kiss"><img src="https://img.shields.io/packagist/dependency-v/digitaledinge/contao-kiss/php?color=474A8A" alt="minimum php version"></a>
    <a href="https://phpstan.org/user-guide/rule-levels"><img src="https://img.shields.io/badge/PHPStan-level%2010-516CB3" alt="phpstan level 10"></a>
</p>

---

## Description

- **Keep it simple**: built on years of Contao experience, simple to use for editors, simple to maintain for
  agencies and simple to extend for developers

- **Seamless updates**
  - Contao `^5.7` and `6.*`, extends the core through Twig inheritance and enhancement, for updates without
    headaches

- **User experience**
    - The Contao feeling you know, nothing new to learn
    - Keeping it simple: every option an editor needs, nothing that gets in the way
    - Visual widgets and grids you can see while you build

- **Performance first**
  - Only the CSS you use, nothing else (see [build tools](docs/build-tools.md))
  - Hashed, versioned assets, CSS cascade layers and a `browserslist`-driven build without legacy bloat

- **Enhancing Contao**
    - Pre-styled Twig components and new content elements on top of Contao's own
    - One framework for many brands and websites, ready for your design system

- **Modern tech stack**
    - [PHP] `^8.4`, [Symfony] `^7.4 || ^8.0` and [Contao] `^5.7 || ^6.0`
    - [Vite] with [hot module replacement] and [Reprise]
    - [Tailwind CSS] 4
    - [Stimulus]
    - [Hotwired Turbo] compatible

- **Built for quality**
  - Type-safe style options: enum-backed value objects, registered with a single PHP attribute
  - Zero schema bloat: every style option lives in one `kiss_styles` column, stored as a persistent identifier
    instead of a CSS class
  - Extensible and overridable: swap any template under the hood, override a single block or bring your own style
    options
  - Tested: [PHPUnit], dependency analysis, custom [Twig CS Fixer] rules and linting in CI

> [!IMPORTANT]
> KISS does not work out of the box. It has to be installed alongside your project's own asset build. See
> [Getting started](#getting-started).
>
> Need help? Contact us at [hallo@digitaledin.ge](mailto:hallo@digitaledin.ge).

---

+ [Yet another Contao framework?](#yet-another-contao-framework)
+ [Target groups](#target-group-all-of-them)
    + [Agencies](#agencies)
    + [Content editors](#content-editors)
    + [Developers](#developers)
+ [Features](#features)
    + [Enhanced Contao elements](#enhanced-contao-elements)
    + [New content elements](#new-content-elements)
    + [Custom components](#custom-components)
    + [Style options](#style-options)
    + [Back end](#back-end)
+ [Getting started](#getting-started)
+ [Documentation](#documentation)
+ [Individual projects and applications](#individual-projects-and-applications)

---

## Yet another Contao framework?

Many themes replace large parts of Contao with their own templates and logic, and every Contao update becomes a risk.
KISS takes the opposite approach: it extends and enhances Contao and integrates seamlessly. Templates change through the
Twig inheritance chain, components are layered on top of the core and style options plug into Contao's own data
containers. Your projects stay update-safe today and with the next Contao version. Keep it simple, stupid.

---

## Target group? All of them

### Agencies

- Seamless integration into Contao: components extend and enhance the core instead of overwriting it
- Upgrade compatibility by design, so Contao and KISS updates stay simple
- Your design systems and tokens, one framework for many brands and websites
- A back end kept simple, with exactly the options editors need

### Content editors

- The Contao feeling you know, nothing new to learn
- Built on years of experience with Contao, solutions anyone using Contao understands right away
- Just enough fields for layout, spacing, colors and typography, never cluttered
- Grouped style options, visual select widgets and field icons for a faster, clearer back end

### Developers

- Modern build stack with Vite, Reprise and Tailwind 4
- Hot module replacement: see your changes while you code, no reload needed
- Ready-to-use Stimulus controllers for interactive components, Hotwired Turbo compatible
- Pre-styled Twig components and CSS enhancements: cards, media & text, call-to-action, swiper, accordion and more
- Twig inheritance all the way: override a single block, keep the rest
- Style options as value objects, available in every template through the `styles` Twig global
- Persistent identifiers instead of CSS classes in the database: swap the CSS classes behind an option at any time,
  without touching a single content element
- Add, replace or migrate style options with a single attribute, migration helpers included for painless upgrades
- Ready for your design tokens and design system
- [Biome], [Stylelint] and custom Twig CS Fixer rules for consistent projects
- Possible to integrate neatly into your CI and build chains

---

## Features

### Enhanced Contao elements

KISS extends Contao's own content elements and modules through the Twig inheritance chain:

| Element               | Enhancement                                                                         |
|-----------------------|-------------------------------------------------------------------------------------|
| Every content element | Topline, headline with tag and appearance, width, margin and padding                |
| Headline              | Headline appearance independent of the tag                                          |
| Text                  | Call-to-action, show as card with background, size and variant, float clear etc.    |
| Element group         | Interactive grid widget, grid with columns, gap and alignment, text alignment       |
| Hyperlink             | Icon, icon position, text alignment, render as button                               |
| Download(s)           | Icon position, text alignment, hide file size, render as button                     |
| Swiper                | Slides per view, navigation, hide pagination, visible overflow                      |
| Accordion             | Section headline with independent tag and appearance                                |
| Player                | Aspect ratio for videos                                                             |
| Gallery               | Responsive grid based on the items per row                                          |
| Table                 | Pre-styled table, caption, header and rows                                           |
| Form (include)        | Form field color, size and variant, inherited by every unstyled input; show as card |
| Article               | Width, padding, background color, text alignment                                    |
| List modules          | Grid with columns and gap for news, event, FAQ and newsletter lists                 |
| News                  | News teasers rendered as cards                                                      |
| And more              |                                                                                     |

### New content elements

| Element              | Description                                                   |
|----------------------|---------------------------------------------------------------|
| Media & text         | Image, video or icon with headline, text and call-to-action   |
| Media & text list    | A list, grid or swiper of media & text items                  |
| Icon                 | A single icon                                                 |
| Icon list            | A list of icons with text                                     |
| Hyperlink list       | A list of links                                               |
| Alert                | Info, success, warning and error messages                     |
| Badge                | Labels and tags                                               |

### Custom components

Pre-styled Twig components in `kiss_component/` for your own templates: call-to-action, alert, badge, icon, image,
video, media & text and switch. Stimulus controllers for a theme toggle, popovers, range and number inputs are
included as well.

### Style options

Every option is stored as a persistent identifier in a single `kiss_styles` column and resolved to CSS classes through
the `styles` Twig global:

- Layout: content width, grid columns, gap and cross alignment, element layout
- Spacing: margin and padding top and bottom
- Colors: background and semantic colors
- Typography: text alignment, text appearance
- Components: size, variant, call-to-action type, color, size and shape, icon position and style

### Back end

- Visual select widgets with field icons
- Grouped legends for layout, appearance, grid and card settings
- Hide the KISS fields for selected content elements in the system settings
- Insert tags for colored text, `{{sup}}` and `{{sub}}`

---

## Getting started

1. **Install the bundle**

   ```bash
   composer require digitaledinge/contao-kiss
   ```

2. **Add the npm package** to your project's `package.json`

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

3. **Create `vite.config.mjs`**

   ```js
   import { buildVite } from '@digitaledinge/contao-kiss/vite';

   export default buildVite();
   ```

4. **Create your entry points**

   `layout/app.js` registers the Stimulus controllers, `layout/css/index.css` imports Tailwind and the KISS styles.
   `layout/fonts/` and `layout/css/assets/` have to exist as well:

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

5. **Build**

   ```bash
   npm install && npm run build
   ```

The full project structure, aliases, the dev server with HMR and more are described in
[docs/build-tools.md](docs/build-tools.md).

---

## Documentation

| Document                                 | Content                                                      |
|------------------------------------------|--------------------------------------------------------------|
| [Build tools](docs/build-tools.md)       | Project setup, Vite, Tailwind, Stimulus, dev server with HMR |
| [Style options](docs/style-options.md)   | Using, adding, replacing and migrating style options         |
| [Twig templates](docs/twig-templates.md) | Inheritance chain, blocks, attribute hooks and components    |
| [Best practices](docs/best-practices.md) | Linting and CI for your project                              |
| [Legacy page layout](docs/legacy.md)     | Using KISS with `fe_page.html.twig`                          |
| [Development](docs/development.md)       | Working on contao-kiss itself                                |
| [Changelog](CHANGELOG.md)                | Release notes                                                |

---

## Individual projects and applications

Need an individual project or application, or want KISS integrated with your design system and tokens? Contact us at
[hallo@digitaledin.ge](mailto:hallo@digitaledin.ge).

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
