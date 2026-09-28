# Development

Working on contao-kiss itself. For using it in a project, see
[build-tools.md](build-tools.md).

## Setup

```bash
composer install
composer bin all install
cd build && npm install
```

## Pre-commit hook
Enable the pre-commit hook. It runs the checks for what you staged:

```bash
git config core.hooksPath .githooks
```

Skip it once with `git commit --no-verify`, disable it with `git config --unset core.hooksPath`.

## Tools

| Command                        | Does                                      |
|--------------------------------|-------------------------------------------|
| `composer twig-cs-fixer`       | fixes `contao/templates`                  |
| `composer twig-cs-fixer-lint`  | lints `contao/templates`, changes nothing |
| `composer twig-cs-fixer-tests` | runs the tests of the custom Twig rules   |
| `composer depcheck`            | checks the Composer dependencies          |
| `composer unit-tests`          | runs PHPUnit                              |
| `composer ci`                  | all of the above                          |
| `npm run lint` (in `build/`)   | Biome and Stylelint over `build/assets`   |

Twig CS Fixer is configured in `.twig-cs-fixer.php`, the linters in
`build/biome.json` and `build/stylelint.config.js`.

## Twig CS Fixer rules

The custom rules live in `vendor-bin/twig-cs-fixer/`, in the same layout as twig-cs-fixer itself:

```
vendor-bin/twig-cs-fixer/
├── src/Rules/<Category>/<Name>Rule.php
└── tests/Rules/<Category>/<Name>/
    ├── <Name>RuleTest.php          extends TwigCsFixer\Test\AbstractRuleTestCase
    ├── <Name>RuleTest.twig         fixture, found by the test's class name
    └── <Name>RuleTest.fixed.twig   fixable rules only
```

Categories follow upstream: `Variable`, `Function`, `Node`, plus `Tag` for rules on `set`, `import` and `from`.
Expected violations are keyed `<Name>.<Error|Warning>:<line>:<column>`.

Adding a rule: write the rule and its test, run `composer twig-cs-fixer-tests`, register it in `.twig-cs-fixer.php`,
run `composer twig-cs-fixer-lint`. Rules target twig-cs-fixer 4.x, where `.` and `|` are `Token::OPERATOR_TYPE`
(they were `PUNCTUATION_TYPE` in 3.x).

Exceptions go into the rule's `ignore` list in `.twig-cs-fixer.php`, never into an inline
`{# twig-cs-fixer-disable #}`: Twig comments are reserved for component docblocks. Templates that need `|raw` go into
`SHAME_ON_YOU`.

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
└── twig-cs-fixer/                          custom Twig rules and their tests
```

## Assets

`ViteVersionStrategy` backs the `kiss_theme` asset package, registered in
`prependExtension()`. `<entry>.js` and `<entry>.css` resolve through Reprise's
`entrypoints.json`, everything else through `manifest.json`. The theme
stylesheet is an entry of its own, named `<entry>.css`.

`contao/templates/page/layout.html.twig` renders the tags in a
`kiss_theme_assets` block. Projects set `kiss_theme_entry`.

## Style options

`tests/Styles/Option/StyleOptionCasesTest.php` pins the case names of every
enum in `src/Styles/Option/`, because those names are being stored for the appearance.

- **Changing a value** is free.
- **A new case** is reported as incomplete until it's added to `CASES`.
- **Removing or renaming a case** fails the test. It's a breaking change: update
  `CASES`, add a migration mapping the old case in
  `src/Migration/Version<NNN>/` with a test next to the existing ones, and list
  it under breaking changes in the PR.

`contao_kiss:find-style-values` finds the records that store a value. How to
write the migration is in `docs/style-options.md`.

## CI

See `.github/workflows/ci.yml`.
Locally: `composer ci` and `npm run lint`.
