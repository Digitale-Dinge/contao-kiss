# Changelog

> [!IMPORTANT]
> contao-kiss is in initial development (`0.y.z`). Every minor version may contain breaking changes and no automatic
> migration between minor versions is provided. See [#18] for
> the tag and branch history.

## [0.9.0] (2026-10-07)

**Tag:** [`0.9.0`][tag-0.9.0]

**Branch:** `main`

**Commit:** [`6bf6bd4`][6bf6bd4]

### Added

- [#47] Images can hide their `<git figcaption>` with `hide_figcaption`, and the new `figure_extra` block adds content
  after the caption. Images inside a card no longer show a caption ([zoglo])
- [#46] Add a possibility to clear the text image alignment floats for next elements ([zoglo])
- [#43] The Swiper can hide its navigation and let its items overflow the container ([cristiangavriliu])
- [#42] New and updated back end field icons in the `kiss_styles` select widgets ([zoglo])
- [#39] The form include element has form style options. Every unstyled input inherits them, and inputs with their own
  style keep it. The KISS input styles now also cover `tel` and `url` ([cristiangavriliu])
- [#40] The section headline of elements inside an accordion now uses the collection widget, just like the regular
  headline. Tag and appearance can differ, so an `h2` can look like an `h3`. The core `AccordionController` only passes
  `header` and `header_tag`, so the template reads the appearance through the new `kiss_content_model` Twig function
  ([zoglo])
- [#36] `contao_kiss:find-style-values` can list every value that differs from the field default ([zoglo])
- [#32] New hyperlink list content element ([cristiangavriliu])
- [#30] New `icon` and `icon-list` content elements with an additional style variant for icons ([cristiangavriliu])
- [#29] The gallery uses the KISS grid by default. The former `grid` variant now replaces the standard gallery template
  ([cristiangavriliu])
- [#27] The download elements got a makeover ([cristiangavriliu]):
  - Downloads now show a file type icon and, if you want, the file size. A `docx` gets the doc icon, an `xlsx` the xls
    icon, and unknown types fall back to `file.svg`.
  - You can place the icon left or right.
  - Download and download list share one template, which extends the KISS override of the core `_download` component.
- [#28] The player element has an `aspect-ratio` field. We recommend hiding the exact size field for editors with a
  restricted back end role ([cristiangavriliu])

### Changed

- [#45] Update the `form_wrapper_grid` template to respect AJAX submissions ([zoglo])
- [#35] `copyDcaField()` in the ConfigBuilder no longer keeps the label reference. Before, relabeling a copied field
  also relabeled the original ([zoglo])
- [#34] `_text` now hands `text: item.text` to `_rich_text` itself, so lists no longer depend on a `with` from the
  caller. Nested components already have `item` and don't need `with` at all. We removed the unnecessary wrappers in
  `_icon_rich_text` and `_media_text` and updated the docs and the skill ([zoglo])
- [#31] Fixed the tokens for `btn-circle` and `btn-square` ([cristiangavriliu])
- [#27] Fixed the English label of "als Button" ([cristiangavriliu])
- [#25] Lists get their styling back after Tailwind's preflight removed it ([cristiangavriliu])

### Breaking

> [!CAUTION]
> These changes require manual updates in your project. See [UPGRADE.md][upgrade].
>
> - [#44] The `logo` Swiper variant is gone. It belongs in the project now ([cristiangavriliu])
> - [#43] The `image_single.html.twig` Swiper variant is gone. Use an element group instead ([cristiangavriliu])
> - [#41] `btn-wide` is gone from the CSS and the style options. Use Tailwind's `w-full` or `block` on the button
>   instead ([cristiangavriliu])
> - [#40] An unlayered `.handorgel__header` rule in your project wins over the new accordion headline appearance. Move
>   it into `@layer components` ([zoglo])
> - [#38] The legacy `fe_page.html.twig` is gone. KISS builds on the modern page layout with Twig slots. If you still
>   need the legacy way, see `docs/legacy.md` ([zoglo])
> - [#37] The shared attribute templates moved to `kiss_attributes/`. The block names stay the same ([zoglo]):
>
>   | Before                                                              | After                                                           |
>   |---------------------------------------------------------------------|-----------------------------------------------------------------|
>   | `@Contao/kiss_component/card/_card_attributes.html.twig`            | `@Contao/kiss_attributes/_card_attributes.html.twig`            |
>   | `@Contao/kiss_component/action/_button_attributes.html.twig`        | `@Contao/kiss_attributes/_button_attributes.html.twig`          |
>   | `@Contao/kiss_component/text/_text_appearance_attributes.html.twig` | `@Contao/kiss_attributes/_text_appearance_attributes.html.twig` |
>
> - [#36] Rich text (TinyMCE) fields and the `rsce_media_text_list` items no longer offer `textAppearance`, and
>   `component/_rich_text.html.twig` no longer renders it. Set text sizes in TinyMCE instead. Stored values stay in the
>   database but do nothing. Form fields keep `textAppearance`. If you still need it, the method is still in the
>   CustomElementsConfigurationBuilder and you can merge `.mergeWith(block('text_appearance_attributes'))` from
>   `_text_appearance_attributes.html.twig` ([zoglo])
> - [#31] The `btn-link` variant and the button shadow are gone. Stored `link` variants are migrated to `text`
>   automatically ([cristiangavriliu])
> - [#30] The icon element no longer has an icon position. It got a style field instead, and stored positions do
>   nothing ([cristiangavriliu])
> - [#29] The `content_element/gallery/grid` template is gone because it is the default gallery template now. Switch
>   elements that used it as a custom template back to the default ([cristiangavriliu])

## [0.8.0] (2026-09-25)

**Tag:** [`0.8.0`][tag-0.8.0]

**Branch:** `main`

**Commit:** [`6b18257`][6b18257]

### Added

- Swiper settings for navigation, pagination and items per row. They work on the content slider and on every element
  that uses `kiss_swiper` through `_base` and `_content_wrapper`. `kissSwiper` isn't in any palette yet, but RSCE
  configs can add it with `->addSwiperSettings()`
- Button styles for the download elements and default styles for the table element
- `kiss_include_data()` lets include elements inherit the kiss style options. With it, `form_inline` can show a form as
  a card
- A style option registry. Put `#[AsKissStyleOption]` on an option class to register it. Without a name it registers
  under the class name. Templates, back end options and RSCE fields all resolve through the registry. See
  `docs/style-options.md` for details
- `contao_kiss:find-style-values` also finds style data in `callToAction`
- Tests for every current style option in `StyleOptionCasesTest`. Removing a case is a breaking change and needs a
  migration, and the CI will tell you. New cases only warn
- More docs. `docs/style-options.md` covers using, adding, replacing and migrating options. `docs/development.md`,
  `AGENTS.md` and the skill explain the registry, include data, component overrides and the Swiper flag

### Breaking

> [!CAUTION]
> These changes require manual updates in your project. See [UPGRADE.md][upgrade].
>
> - The `x_small` and `x_large` size modifiers and their CSS are gone
> - The `contao_kiss.style_definition_override` config is gone. Remove it from your `config.yaml`
> - The registry replaces the experimental `StyleOptionEvent` and `ContaoKissEvents`

See [UPGRADE.md][upgrade-0.8] for how to find and migrate the stored sizes.

## [0.7.2] (2026-09-24)

**Tag:** [`0.7.2`][tag-0.7.2]

**Branch:** `main`

**Commit:** [`5afcd41`][5afcd41]

- [#24] Updated the kiss skill ([cristiangavriliu])

## [0.7.1] (2026-09-24)

**Tag:** [`0.7.1`][tag-0.7.1]

**Branch:** `main`

**Commit:** [`22bac3b`][22bac3b]

- The package now ships `AGENTS.md`, the skills, the docs and `twig-cs-fixer`
- The Twig CS Fixer rules are autoloaded (`DigitaleDinge\ContaoKiss\Tools\TwigCsFixer\`), so projects can use them
- `.githooks` is no longer part of the package

## [0.7.0] (2026-09-24)

**Tag:** [`0.7.0`][tag-0.7.0]

**Branch:** `main`

**Commit:** [`b8b5695`][b8b5695]

The framework styles moved from SCSS to plain CSS/PCSS, and there's a new Vite-based build chain. Tailwind 4 doesn't
support Sass or SCSS (see the [Tailwind 4 compatibility docs][tailwind-compat]),
and native CSS nesting plus `@apply` covers everything we used SCSS for.

### Added

- `build/` is now the npm package `@digitaledinge/contao-kiss`. It exports the JS controllers (`.`), a Vite config
  factory (`./vite`) and the CSS (`./css`). It replaces the build config every project had to bring along, and the
  build time drops from about 10s to about 350ms
- A project needs one dev dependency and a two-line `vite.config.mjs`:

  ```json
  "@digitaledinge/contao-kiss": "file:vendor/digitaledinge/contao-kiss/build"
  ```

  ```js
  import { buildVite } from '@digitaledinge/contao-kiss/vite';
  export default buildVite();
  ```

- The `build` and `dev-server` scripts (`dev`, `watch`). The dev server runs with HTTPS and HMR
- `@font` and `@asset` aliases. Referenced files end up hashed in `public/layout/`
- `buildVite()` takes options and you can modify the config. The `browserslist` in your project's `package.json`
  decides the JS and CSS targets
- The new `ViteVersionStrategy` backs the `kiss_theme` asset package. Entries resolve through Reprise's
  `entrypoints.json` and everything else through `manifest.json`. The bundle prepends the Reprise config and registers
  the asset package for you
- `page/layout.html.twig` renders the assets in a `kiss_theme_assets` block. You can switch the entry per layout with
  `{% set kiss_theme_entry = 'app.whatever' %}`
- A CI workflow (`.github/workflows/ci.yml`) with Biome for JS, Stylelint for CSS and PCSS (Tailwind 4 aware), Twig CS
  Fixer 4.x with custom rules, depcheck, and PHPUnit on PHP 8.4 and 8.5. The rule tests run before the Twig lint, so a
  broken rule can't produce a green lint. Run it all locally with `composer ci` and `npm run lint` in `build/`
- Custom Twig CS Fixer rules:

  | Rule                                  | Description                                                                                                                                          |
  |---------------------------------------|------------------------------------------------------------------------------------------------------------------------------------------------------|
  | `Variable\VariableName`               | Variables use snake_case and may start with `_`. Contao's own names (`wrapperAttributes`, `bodyAttributes`, `cssID`) are ignored                     |
  | `Variable\HtmlAttributesVariableName` | `set x = attrs()` must be named `*_attributes`                                                                                                       |
  | `Variable\AttrsHookMerge` (warning)   | `attrs()` should enrich a hook instead of replacing it, with `attrs(foobar\|default)` or `.mergeWith(x\|default)`. Loop and macro bodies are skipped |
  | `Function\ComponentInclude`           | No `include()` with variables into `kiss_component/*`. Use `{% use %}` and `{{ block() }}` instead. `_icon_include` is the exception                 |
  | `Function\AddClassCondition`          | `addClass('prefix-' ~ value)` needs the condition argument                                                                                           |
  | `Tag\SetUnderscore` (fixable)         | `{% set _ = … %}` becomes `{% do … %}`                                                                                                               |
  | `Tag\ImportSelf` (warning)            | No `import _self as` or `from _self import`, because macros are auto-imported as `_self`                                                             |
  | `Node\ForbiddenRawFilter` (warning)   | `raw` is only allowed in `mod_breadcrumb`, `mod_newslist` and templates listed in `SHAME_ON_YOU` in `.twig-cs-fixer.php`                             |
  | `{% embed %}`                         | Forbidden by the upstream `ForbiddenBlockRule`. Use `{% use %}` and `extends`                                                                        |

- Docs in `docs/build-tools.md`, `docs/development.md` and `docs/best-practices.md`, plus an `AGENTS.md`
- A `.githooks/pre-commit` hook
- The Symfony packages (`^7.4 || ^8.0`), `symfony/reprise` and `twig/twig` `^3.23` are now required explicitly.
  `contao-company` moved to `0.2.*`

### Breaking

> [!CAUTION]
> These changes require manual updates in your project. See [UPGRADE.md][upgrade].
>
> - `assets/scss/**` is gone. Everything lives in `build/assets/css/**` as `.pcss` now. Replace your imports from
>   `vendor/digitaledinge/contao-kiss/assets/scss/...` with `@import "@digitaledinge/contao-kiss/css/index";`. Single
>   partials are still available, for example `@digitaledinge/contao-kiss/css/components/index`. Your project theme has
>   to be plain CSS as well
> - The Stimulus controllers moved from `assets/js/` to `build/assets/js/`. Import them from the package. The names
>   didn't change: `import { ThemeController, PopoverController } from '@digitaledinge/contao-kiss';`
> - The build runs through `vite.config.mjs` and writes to `public/layout/` with `entrypoints.json` and
>   `manifest.json`. Remove your own `<script>` and `<link>` tags for the theme
> - The project layout is fixed. You need `layout/app.js`, `layout/css/index.css`, `layout/fonts/` and
>   `layout/css/assets/`
> - Import vendor CSS (glightbox, datepicker and so on) in `index.css`, not from JavaScript
> - The datepicker styles, the `vanillajs-datepicker` dependency and the legacy icon font rules (`[data-icon]`) are gone
>   without a replacement. If you use the datepicker, add the dependency yourself and import
>   `vanillajs-datepicker/css/datepicker.css` with `layer(components)`

See [UPGRADE.md][upgrade-0.7] for the step by step upgrade.

## [0.6.0] (2026-09-17)

**Tag:** [`0.6.0`][tag-0.6.0]

**Branch:** `main`

**Commit:** [`17d311a`][17d311a]

Cleanup and the first content elements built on the design system.

### Added

- [#16] An alert content element (`rsce_alert`) with a title, text, an icon from the SVG icon picker, a color, a size
  and the soft, outline and dashed variants ([cristiangavriliu])
- [#21] The `kiss-framework-extend` skill ([FlowinBeatz])
- [#20] A badge content element (`rsce_badge`). `badge-pill` and `badge-square` simply use Tailwind utilities
  ([FlowinBeatz])
- [#17] Visual layout options for the media text elements, picked with layout icons in a radio image widget ([zoglo])
- [#17] New methods in the CustomElementsConfigurationBuilder: `addCardSettings`, `addElementLayoutField`,
  `addSelectField` and `addCheckboxField`. `inheritEvalClass` loads options from the standard fields, and dependsOn
  fields can depend on other fields ([zoglo])
- [#17] `ContentMediaTypeRsceDataMigration` for the new "show media" checkbox ([zoglo])
- The `contao_kiss:find-style-values` command and an `AbstractJsonColumnMigration` to migrate stored style values
- PHPUnit tests

### Changed

- [#22] Fewer comments where the naming already explains the intent ([zoglo])
- [#17] The media text elements are now called "Text (with medium)" and "Text-List (with medium)" and moved from the
  `media` to the `texts` category. They accept videos and "show as card" got its own palette. `media_text_list` builds
  on `media_text` and no longer overrides the attributes block. `card_attributes` lives in its own partial now ([zoglo])
- [#17] The text alignment values "Start" and "End" are now "Left aligned" and "Right aligned" ([zoglo])
- The card component uses custom properties and native CSS (`card.css`)
- The migrations moved to `Migration/Version100/`
- `contao-grid-ratio-widget` and `contao-collection-widget` are now required in `^1.0`, `contao-company` in `0.1.*`, and
  `zoglo/contao-radio-image-widget` is new
- `addDependsOnField` is deprecated

### Breaking

> [!CAUTION]
> These changes require manual updates in your project. See [UPGRADE.md][upgrade].
>
> - [#16] The alert component template takes a single `text` parameter instead of `description` and `message`
>   ([cristiangavriliu])
> - [#19] Design tokens were renamed. Stored style values are converted automatically, but classes and properties you
>   use yourself are not ([zoglo]):
>
>   | Old                                                 | New                                                |
>   |-----------------------------------------------------|----------------------------------------------------|
>   | `.responsive-display1`, `2`, `3`                    | `.responsive-display-lg`, `-md`, `-sm`             |
>   | `.responsive-headline1`, `2`, `3`                   | `.responsive-headline-lg`, `-md`, `-sm`            |
>   | `.responsive-body1`, `2`, `3`                       | `.responsive-body-lg`, `-md`, `-sm`                |
>   | `.fixed-title1`, `2`, `3`                           | `.fixed-title-lg`, `-md`, `-sm`                    |
>   | `.fixed-body1`, `2`                                 | `.fixed-body-xl`, `-lg`                            |
>   | `--kiss-sys-sizing-responsive-semantic-container-*` | `--kiss-sys-sizing-responsive-default-container-*` |
>
> - [#17] In the media text elements, `cardLayout` is now `elementLayout` and `styles.card_layout` is now
>   `styles.media_layout`. `addLinkField` is now `addImageUrlField`, and the `xs` and `xl` card sizes are gone
>   ([zoglo])
> - The `.html5` templates in `contao/_revision/` are gone, as are `block_searchable_no_headline`,
>   `block_unsearchable_no_headline`, `mod_navigation_horizontal` and `mod_navigation_vertical_md_horizontal`.
>   Modules still set to one of them fail with `Could not find template "mod_navigation_vertical_md_horizontal"`.
>   Switch them to the default navigation template, or add an override in your project's `contao/templates/`:
>
>   ```twig
>   {% extends '@Contao/mod_navigation' %}
>
>   {% set wrapperAttributes = attrs(cssID)
>       .addClass(['mod_navigation--vertical', breakpoint|default('md') ~ ':mod_navigation--horizontal'])
>       .mergeWith(wrapperAttributes|default)
>   %}
>   ```
> - Headlines no longer allow HTML. Do that in your app if you need it

## [0.5.1] (2026-09-04)

**Tag:** [`0.5.1`][tag-0.5.1]

**Former branch:** `design-system`

**Commit:** [`4a492f8`][4a492f8]

- Fixed reading the headline from `data.headline`

## [0.5.0] (2026-09-03)

**Tag:** [`0.5.0`][tag-0.5.0]

**Former branch:** `design-system`

**Commit:** [`7507743`][7507743]

The design system arrives. Components now use the `kiss-design-system` tokens.

### Added

- Contao 6 support (`^5.7 || ^6.0`)
- [#10] The components were rewritten with the design system and custom properties. Colors come from the design system
  instead of the old base colors ([cristiangavriliu])
- Cards follow the design system, with background color, element sizes and display options. Text elements can be shown
  as a card (`showAsCard`), and the configuration builder has `addShowAsCard`
- [#6] Card layout, style and color manipulators for the media text component ([FlowinBeatz])
- A `_swiper` component and a `kiss_swiper` setting on the wrapper component
- `sup` and `sub` block insert tags
- A `Responsive` typography option, a logo size field, link styles with icons and a `text` content element template
- Layout widths use the design tokens
- Migrations for the article content width and background color

### Changed

- Base font sizes, container widths, header layout classes and the badge padding follow the design system
- `form_inline` is now `form_wrapper_grid`, and `form-grid` has its own template
- Actions get the loop index and length and use `insert_tag_raw`. The CTA field accepts options

### Breaking

> [!CAUTION]
> These changes require manual updates in your project. See [UPGRADE.md][upgrade].
>
> - [#11] The `accent` color is now `tertiary`, and the `info` color is gone ([cristiangavriliu])
> - [#8] The design system typography replaces the font size options. The `textAppearance` values `x_small` to
>   `xxx_large` are now `display_one` to `body_three`, and the Twig global `styles.font_size` is now
>   `styles.font_appearance` ([cristiangavriliu])
> - `FontSize`, the old `base_old` SCSS, the Swiper SCSS, the `swiper.html.twig` element template and the Swiper bleed
>   template and CSS are gone
> - `showAsCard` replaces `imageMargin`

## [0.4.1] (2026-09-04)

**Tag:** [`0.4.1`][tag-0.4.1]

**Former branch:** `rework`

**Commit:** [`d9e1f2f`][d9e1f2f]

- Fixed reading the headline from `data.headline`

## [0.4.0] (2026-08-11)

**Tag:** [`0.4.0`][tag-0.4.0]

**Former branch:** `rework`

**Commit:** [`4efdaf2`][4efdaf2]

A complete rewrite. There is no upgrade path from 0.3.

### Requirements

- PHP `^8.3` and Contao `^5.7`
- New dependencies: RockSolid Custom Elements, `digitaledinge/contao-grid-ratio-widget`,
  `digitaledinge/contao-company`, `zoglo/contao-collection-widget`, `mvo/contao-group-widget`,
  `lukasbableck/contao-svg-icon-picker-bundle` and `twig/intl-extra`

### Added

- `kiss_styles`, the style options as PHP enums in `src/Styles/Option/`. They cover color, background, layout, margin,
  padding, typography, call to action variant and shape, and modifiers. The back end options are translated and
  templates read them through the `styles` Twig global
- DCA listeners that add the kiss style fields to the content, form field and module palettes
- The `CustomElementsConfigurationBuilder` for RSCE configs, along with the `rsce_icon`, `rsce_media_text` and
  `rsce_media_text_list` elements
- Twig components in `kiss_component/`. There are actions (`_action`, `_call_to_action`), media (`_image`, `_video`,
  `_icon`, `_icon_text`, `_text`, `_media_text`), status (`_alert`, `_badge`), theme (`_toggle`, `_init_script`), a
  grid macro and `_content_wrapper`
- A Twig page layout (`page/layout.html.twig`) and Twig templates for articles, breadcrumb, news list, navigation and
  forms (`form_row`, `form_submit`, `form_inline`, `form_explanation`)
- [#5] A rewritten button component with a much smaller CSS output. Its initial color is no longer tied to primary
  ([zoglo])
- The grid ratio widget in the content wrapper with a back end preview, and element groups with text alignment
- A Swiper with multi card rows and a logo slider, responsive videos, and lightbox and links for media text images
- Headlines are wrapped in an `<hgroup>` when a topline is set
- A color block insert tag and a preview in the back end record listing

### Breaking

> [!CAUTION]
> These changes require manual updates in your project. See [UPGRADE.md][upgrade].
>
> - Most `.html5` templates are gone (forms, `block_*`, tab control, mmenu.js, navigation dropdowns and the social media
>   list), and so are the Style Manager XML configurations
> - The `components/` and `partials/page/` templates are replaced by `kiss_component/`
> - The Composer scripts, the symlink listener and the old topline and layout palette hooks are gone

## [0.3.0] (2025-10-15)

**Tag:** [`0.3.0`][tag-0.3.0]

**Former branch:** `dev-5.6`

**Commit:** [`c1104c4`][c1104c4]

> [!NOTE]
> Before 0.4, development happened in long-lived branches instead of tagged releases. `dev-5.6` continued the work from
> `dev-5.3` and was deleted later. This tag marks its last commit so the state stays installable and the version
> history is complete. See [#18].

### Added

- Header and footer are split into `_main` and `_meta` partials in `partials/page/header/` and
  `partials/page/footer/`. They replace the single `_header` and `_footer` partials from 0.2
- Partials from the project theme are included when they exist, so a website can override parts of the layout
- `.html5` form templates for `form_row`, `form_select`, `form_submit`, `form_text`, `form_upload` and `form_captcha`,
  plus a switch variant of the checkbox (`form_checkbox__switch`)
- Navigation templates for a horizontal navigation on all viewports (`mod_navigation_horizontal-all-viewports`), a
  vertical navigation that turns horizontal from `md` (`mod_navigation_vertical_md-horizontal`) and two dropdown
  buttons (`nav_dropdown-button-default`, `nav_dropdown-button-text`)
- A social media list template without labels, with its first translation in `translations/default.de.yml`
- Card styles for basic, floating, mosaic, simple contact, slider and text image cards
- Styles for fieldsets and links, and the hamburgers vendor SCSS for the menu button
- A white background (`background-white`) for articles

### Changed

- The typography and form styles were reworked (inputs, labels, selects and range)
- Content width classes, backgrounds, logo classes and the layout styles were optimized
- FlyonUI got some optimizations, and the old basecoat utility classes and form element styles were replaced
- The page layout, `fe_page`, the hyperlink template and the tabs styles were updated

### Removed

- The `_badge` and `_badge-with-link` components
- The `nav_horizontal` template, replaced by the new navigation templates
- The `form_checkbox` and `form_radio` overrides

## [0.2.0] (2025-10-03)

**Tag:** [`0.2.0`][tag-0.2.0]

**Former branch:** `dev-5.3`

**Commit:** [`1832f78`][1832f78]

> [!NOTE]
> Before 0.4, development happened in long-lived branches instead of tagged releases. `dev-5.3` was the Contao 5.3
> branch next to `main` and was deleted later. This tag marks its last commit so the state stays installable and the
> version history is complete. See [#18].

The move to Contao 5 (`^5.3`), Twig and FlyonUI. Contao 4.13 is no longer supported. The bundle was rebuilt on the
"Peppermint" Contao 5 base and most of the old custom elements were dropped.

### Added

- Twig content element templates for `_base`, accordion, gallery, headline, hyperlink and Swiper, with a single image
  and a logo slider variant for the Swiper
- A Twig page template (`fe_page.html.twig`) with header and footer partials, and a `.twig-root`
- Twig components for button, badge (also with a link) and a form switch
- Style Manager configurations (`style-manager-contao-kiss.xml` and `style-manager-tailwind-grids.xml`) so editors can
  pick styles and grids in the back end
- The `AddLayoutFieldsToPalette` hook. It adds content width, padding and margin fields to the content elements. The
  spacing options use line based classes like `pt-line-1` to `pt-line-5`
- New content fields for icons and their position on hyperlinks, hyperlinks as buttons (type and size), downloads as
  cards and iframes in the lightbox
- Back end CSS and JS (`public/dist/backend/`), loaded in the back end only, with field icons for background color,
  content width, margin and padding
- `ComposerScripts::copyFiles`, which runs on `composer install` and `composer update`
- A Twig `AppExtension` that holds the container sizes
- `.html5` templates for tab control, `mmenu_default`, a horizontal navigation, `mod_article`, the image copyright list,
  checkbox, switch, radio and range fields
- Styles for the `ppag-contao-tabs-bundle` and range inputs
- German translations for `tl_article` and `tl_settings`

### Changed

- The SCSS moved from `public/scss/` to `public/src/scss/` and was reduced to what Contao needs
- FlyonUI replaces orangeUI, and the button classes follow FlyonUI
- The container class names and the spacing class calculation were refactored
- The article background colors are now `background-primary`, `background-secondary`, `background-additional-1` and
  `background-additional-2` instead of `color-1` to `color-4`
- `dfn`, `dt` and `caption` are no longer capitalized, and disabled Swiper buttons are no longer forced to `opacity: 0`
- The body class is no longer replaced as a whole

### Removed

- The RSCE elements for cards (simple contact, text image, floating, mosaic, slider), facts and figures, logo, quote,
  team, teaser hero and icon tiles
- The legacy `ce_*` templates for accordion, colset, slider, tiny slider, download and downloads, headline, hyperlink,
  toplink and the dismissable banner
- The pushy page and navigation templates, colorbox, the `fetchpriority-high` image and picture templates and
  `be_tinyMCE`
- The old SCSS (normalize, spacing and column utilities, nav, teaser, form, pushy and the variables)
- The `AddTextStyleToPalette` and `ParseTemplate` hooks
- The English `tl_content` translations and the `tl_anystores` translations

## [0.1.0] (2024-11-29)

**Tag:** [`0.1.0`][tag-0.1.0]

**Former branch:** `main`

**Commit:** [`2d6c2d2`][2d6c2d2]

> [!NOTE]
> Before 0.4, development happened in long-lived branches instead of tagged releases. This is the last commit of the
> original `main` branch, which was deleted later and replaced by today's `main`. It was tagged so the state stays
> installable and the version history is complete. See
> [#18].

- Initial release for Contao `^4.13 || ^5.3`

[0.9.0]: https://github.com/Digitale-Dinge/contao-kiss/compare/0.8.0...0.9.0
[0.8.0]: https://github.com/Digitale-Dinge/contao-kiss/compare/0.7.2...0.8.0
[0.7.2]: https://github.com/Digitale-Dinge/contao-kiss/compare/0.7.1...0.7.2
[0.7.1]: https://github.com/Digitale-Dinge/contao-kiss/compare/0.7.0...0.7.1
[0.7.0]: https://github.com/Digitale-Dinge/contao-kiss/compare/0.6.0...0.7.0
[0.6.0]: https://github.com/Digitale-Dinge/contao-kiss/compare/0.5.1...0.6.0
[0.5.1]: https://github.com/Digitale-Dinge/contao-kiss/compare/0.5.0...0.5.1
[0.5.0]: https://github.com/Digitale-Dinge/contao-kiss/compare/0.4.0...0.5.0
[0.4.1]: https://github.com/Digitale-Dinge/contao-kiss/compare/0.4.0...0.4.1
[0.4.0]: https://github.com/Digitale-Dinge/contao-kiss/compare/0.3.0...0.4.0
[0.3.0]: https://github.com/Digitale-Dinge/contao-kiss/compare/0.2.0...0.3.0
[0.2.0]: https://github.com/Digitale-Dinge/contao-kiss/compare/0.1.0...0.2.0
[0.1.0]: https://github.com/Digitale-Dinge/contao-kiss/releases/tag/0.1.0

[tag-0.9.0]: https://github.com/Digitale-Dinge/contao-kiss/releases/tag/0.9.0
[tag-0.8.0]: https://github.com/Digitale-Dinge/contao-kiss/releases/tag/0.8.0
[tag-0.7.2]: https://github.com/Digitale-Dinge/contao-kiss/releases/tag/0.7.2
[tag-0.7.1]: https://github.com/Digitale-Dinge/contao-kiss/releases/tag/0.7.1
[tag-0.7.0]: https://github.com/Digitale-Dinge/contao-kiss/releases/tag/0.7.0
[tag-0.6.0]: https://github.com/Digitale-Dinge/contao-kiss/releases/tag/0.6.0
[tag-0.5.1]: https://github.com/Digitale-Dinge/contao-kiss/releases/tag/0.5.1
[tag-0.5.0]: https://github.com/Digitale-Dinge/contao-kiss/releases/tag/0.5.0
[tag-0.4.1]: https://github.com/Digitale-Dinge/contao-kiss/releases/tag/0.4.1
[tag-0.4.0]: https://github.com/Digitale-Dinge/contao-kiss/releases/tag/0.4.0
[tag-0.3.0]: https://github.com/Digitale-Dinge/contao-kiss/releases/tag/0.3.0
[tag-0.2.0]: https://github.com/Digitale-Dinge/contao-kiss/releases/tag/0.2.0
[tag-0.1.0]: https://github.com/Digitale-Dinge/contao-kiss/releases/tag/0.1.0

[6bf6bd4]: https://github.com/Digitale-Dinge/contao-kiss/commit/6bf6bd4e77da7d4915fea46b9afdf55221a00665
[6b18257]: https://github.com/Digitale-Dinge/contao-kiss/commit/6b18257bed8f5629ec902f005a5766aaa6e54b8f
[5afcd41]: https://github.com/Digitale-Dinge/contao-kiss/commit/5afcd41778a3367db1961fb8d04c399951ede127
[22bac3b]: https://github.com/Digitale-Dinge/contao-kiss/commit/22bac3b8b91d7df92a9c1f901f32375f07807273
[b8b5695]: https://github.com/Digitale-Dinge/contao-kiss/commit/b8b569512e7604f97a90f1ea117aae3758d7717b
[17d311a]: https://github.com/Digitale-Dinge/contao-kiss/commit/17d311a769d8c1704429f39732d9c66732ba7d3f
[4a492f8]: https://github.com/Digitale-Dinge/contao-kiss/commit/4a492f87972034b1fda2ffac11226c2be8ccc3d3
[7507743]: https://github.com/Digitale-Dinge/contao-kiss/commit/750774307e02bb463606ddc3ed5e5c7642145683
[d9e1f2f]: https://github.com/Digitale-Dinge/contao-kiss/commit/d9e1f2f2a85c7866bf5c85d022f5e6cc31865eb9
[4efdaf2]: https://github.com/Digitale-Dinge/contao-kiss/commit/4efdaf20cfe74a1548b61617892d75d09002280d
[c1104c4]: https://github.com/Digitale-Dinge/contao-kiss/commit/c1104c466fd7061d23c96daff78897b9824d231d
[1832f78]: https://github.com/Digitale-Dinge/contao-kiss/commit/1832f78425d316df0e74baed1baed896cbf7ca81
[2d6c2d2]: https://github.com/Digitale-Dinge/contao-kiss/commit/2d6c2d2ea922be39b9709d00afd7c79f5e8ba8b7

[#5]: https://github.com/Digitale-Dinge/contao-kiss/pull/5
[#6]: https://github.com/Digitale-Dinge/contao-kiss/pull/6
[#8]: https://github.com/Digitale-Dinge/contao-kiss/pull/8
[#10]: https://github.com/Digitale-Dinge/contao-kiss/pull/10
[#11]: https://github.com/Digitale-Dinge/contao-kiss/pull/11
[#16]: https://github.com/Digitale-Dinge/contao-kiss/pull/16
[#17]: https://github.com/Digitale-Dinge/contao-kiss/pull/17
[#18]: https://github.com/Digitale-Dinge/contao-kiss/issues/18
[#19]: https://github.com/Digitale-Dinge/contao-kiss/pull/19
[#20]: https://github.com/Digitale-Dinge/contao-kiss/pull/20
[#21]: https://github.com/Digitale-Dinge/contao-kiss/pull/21
[#22]: https://github.com/Digitale-Dinge/contao-kiss/pull/22
[#24]: https://github.com/Digitale-Dinge/contao-kiss/pull/24
[#25]: https://github.com/Digitale-Dinge/contao-kiss/pull/25
[#27]: https://github.com/Digitale-Dinge/contao-kiss/pull/27
[#28]: https://github.com/Digitale-Dinge/contao-kiss/pull/28
[#29]: https://github.com/Digitale-Dinge/contao-kiss/pull/29
[#30]: https://github.com/Digitale-Dinge/contao-kiss/pull/30
[#31]: https://github.com/Digitale-Dinge/contao-kiss/pull/31
[#32]: https://github.com/Digitale-Dinge/contao-kiss/pull/32
[#34]: https://github.com/Digitale-Dinge/contao-kiss/pull/34
[#35]: https://github.com/Digitale-Dinge/contao-kiss/pull/35
[#36]: https://github.com/Digitale-Dinge/contao-kiss/pull/36
[#37]: https://github.com/Digitale-Dinge/contao-kiss/pull/37
[#38]: https://github.com/Digitale-Dinge/contao-kiss/pull/38
[#39]: https://github.com/Digitale-Dinge/contao-kiss/pull/39
[#40]: https://github.com/Digitale-Dinge/contao-kiss/pull/40
[#41]: https://github.com/Digitale-Dinge/contao-kiss/pull/41
[#42]: https://github.com/Digitale-Dinge/contao-kiss/pull/42
[#43]: https://github.com/Digitale-Dinge/contao-kiss/pull/43
[#44]: https://github.com/Digitale-Dinge/contao-kiss/pull/44
[#45]: https://github.com/Digitale-Dinge/contao-kiss/pull/45
[#46]: https://github.com/Digitale-Dinge/contao-kiss/pull/46
[#47]: https://github.com/Digitale-Dinge/contao-kiss/pull/47

[tailwind-compat]: https://tailwindcss.com/docs/compatibility#sass-less-and-stylus

[upgrade]: UPGRADE.md
[upgrade-0.8]: UPGRADE.md#from-07-to-08
[upgrade-0.7]: UPGRADE.md#from-06-to-07

[cristiangavriliu]: https://github.com/cristiangavriliu
[FlowinBeatz]: https://github.com/FlowinBeatz
[zoglo]: https://github.com/zoglo
