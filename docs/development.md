# Development

Working on contao-kiss itself. For using it in a project, see
[build-tools.md](build-tools.md).

## Setup

```bash
composer install
composer bin all install
cd build && npm install
```

## Tools

| Command                      | Does                                    |
|------------------------------|-----------------------------------------|
| `composer twig-cs-fixer`     | fixes `contao/templates`                |
| `composer depcheck`          | checks the Composer dependencies        |
| `composer unit-tests`        | runs PHPUnit                            |
| `composer ci`                | all of the above                        |
| `npm run lint` (in `build/`) | Biome and Stylelint over `build/assets` |

Twig CS Fixer is configured in `.twig-cs-fixer.php`, the linters in
`build/biome.json` and `build/stylelint.config.js`.

## Repository layout

```
build/                                      npm package, @digitaledinge/contao-kiss
├── vite.mjs                                buildVite()
├── package.json                            exports ".", "./vite", "./css"
├── biome.json
├── stylelint.config.js
└── assets/
    ├── js/                                 Stimulus controllers, exported as "."
    └── css/                                stylesheets, exported as "./css"

src/
├── Asset/VersionStrategy/                  asset() resolution
├── ContaoManager/Plugin.php
├── DigitaleDingeContaoKissBundle.php
├── Styles/Option/                          style options: Modifier/, Color/, Layout/,
│                                           Typography/, Padding/, Margin/, Component/
├── Twig/Global/StylesVariable.php          the `styles` Twig global
├── EventListener/DataContainer/            backend options callbacks
└── CustomElementsConfigurationBuilder.php  rsce element builder

contao/
├── dca/tl_content.php                      fields on kiss_styles, subpalettes
└── templates/
    ├── page/layout.html.twig
    ├── content_element/_base.html.twig      KISS base template
    ├── kiss_component/
    │   ├── _content_wrapper.html.twig       list / grid / swiper wrapper
    │   ├── media/                           media_text, image, video, icon, text
    │   └── action/                          call-to-action
    └── rsce_*_config.php                    rsce element configs

translations/{*}/                       style_options.*, contao_tl_content.*, rsce.*
config/services.yaml
vendor-bin/                                 isolated tooling
```

## Assets

`ViteVersionStrategy` backs the `kiss_theme` asset package, registered in
`prependExtension()`. `<entry>.js` and `<entry>.css` resolve through Reprise's
`entrypoints.json`, everything else through `manifest.json`. The theme
stylesheet is an entry of its own, named `<entry>.css`.

`contao/templates/page/layout.html.twig` renders the tags in a
`kiss_theme_assets` block. Projects set `kiss_theme_entry`.

## CI

See `.github/workflows/ci.yml`.
Locally: `composer ci` and `npm run lint`.
