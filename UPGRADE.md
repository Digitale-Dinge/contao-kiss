# Upgrade

This file explains how to upgrade a project from one major version of contao-kiss to the next. What changed and why is
in the [changelog](CHANGELOG.md). Upgrade one major version at a time. Before 1.0, every minor version was considered a
major one (SemVer).

> [!CAUTION]
> Always create a database backup before you run a migration. No backup, no sorry (=.

## Backwards compatibility

Minor and patch versions keep what projects build on:

- Stored values: style option case names in `kiss_styles` and DCA field names
- PHP classes and methods not marked `@internal`, and service IDs
- Template names, the documented template variables (`attributes`, `media_attributes`, `show_as_card`, …) and the
  getters of the `styles` Twig global

Not covered are template blocks and markup, the CSS classes a style option emits, and CSS custom properties. Inherit
through the template variables instead of overriding blocks. When one of these changes, this file says how to adapt.

## Finding and migrating stored values

When a style option case is dropped or renamed, content that stored it renders without a class until someone re-saves
it. You have two ways to deal with that.

**Find the content and re-save it by hand.** `contao_kiss:find-style-values` takes comma-separated keys followed by the
values to look for. It searches `kiss_styles`, `headline`, `rsce_data` and `callToAction`:

```bash
vendor/bin/contao-console contao_kiss:find-style-values ctaSize,elementSize x_small x_large
```

Leave out the values to list everything that differs from the field default. `--table=tl_content` limits the search to
one table, and `--backend-prefix=https://example.org/contao` adds an edit link to every record.

**Or write a migration.** Extend `AbstractJsonColumnMigration` in your app. It walks nested lists, JSON and serialized
values and keeps every value in its original format. Columns a table doesn't have are skipped, mapping to `''` clears a
value and `getKeyRenames()` renames a key. The migration runs with `contao:migrate`.

A good routine is to run the command, run the migration, then run the command again. The second run should find
nothing. More on writing migrations is in [`docs/style-options.md`](docs/style-options.md#migrating-stored-values).

## From 0.9 to 1.0

### Removed deprecations

`addDependsOnField()` is gone from the CustomElementsConfigurationBuilder. Use `addSelectField()` with the options, or
`addCheckboxField()` if you passed none. Both take the same `$eval` and `$dependsOn` arguments:

```php
// Before
->addDependsOnField('mediaType', ['image', 'video'])

// After
->addSelectField('mediaType', ['image', 'video'])
```

The `includeImageSizeField` argument of `addImageField()` is gone. Use `includeSizeField` instead.

### Button variant

`styles.cta_type` is now `styles.cta_variant`. It returns `soft`, `outline` or `text` instead of `btn-soft`,
`btn-outline` or `btn-text`, so the template adds the prefix. Stored values stay the same and need no migration. Only
your own template overrides need updating:

```twig
{# Before #}
.addClass(styles.cta_type(data.ctaType|default))

{# After #}
.addClass('btn-' ~ styles.cta_variant(data.ctaType|default), data.ctaType|default)
```

### Card padding

`--card-px` and `--card-py` are gone. A project that still sets them falls back to the default card padding without a
warning. Set the four sides instead:

| Before       | After                      |
|--------------|----------------------------|
| `--card-px`  | `--card-pr`, `--card-pl`   |
| `--card-py`  | `--card-pt`, `--card-pb`   |

### Typed `getTranslatedOptions()`

`TranslatableEnumTrait::getTranslatedOptions()` requires the enum class name as a `string`. If you pass
`$this->registry->getEnum(…)` or `<Enum>::class` as documented, nothing changes.

## From 0.8 to 0.9

### Migrated automatically

- Buttons and form fields that stored the `link` variant become `text` (`CallToActionLinkMigration`)

### Wide buttons and form fields

The `wide` shape is gone. Content that stored it falls back to the default shape. Use Tailwind's `w-full` or `block` on
the button instead.

```bash
vendor/bin/contao-console contao_kiss:find-style-values ctaShape,fieldShape wide
```

```php
namespace App\Migration;

use DigitaleDinge\ContaoKiss\Migration\AbstractJsonColumnMigration;

class WideShapeMigration extends AbstractJsonColumnMigration
{
    private const array SHAPE_MAP = ['wide' => ''];

    protected function getTables(): array
    {
        return ['tl_content', 'tl_form_field'];
    }

    protected function getColumns(): array
    {
        return ['kiss_styles', 'rsce_data'];
    }

    protected function getValueMaps(): array
    {
        return [
            'kiss_styles' => ['ctaShape' => self::SHAPE_MAP, 'fieldShape' => self::SHAPE_MAP],
            'rsce_data' => ['ctaShape' => self::SHAPE_MAP],
        ];
    }
}
```

### Text appearance on rich text

Rich text elements and `rsce_media_text_list` items no longer render `textAppearance`. The stored values do nothing,
so you can set the text size in TinyMCE and clean them up. Form fields keep `textAppearance`, so leave `tl_form_field`
alone.

```bash
vendor/bin/contao-console contao_kiss:find-style-values textAppearance --table=tl_content
```

To clean up, use a migration on `tl_content` that maps every value you found to `''`:

```php
class RichTextAppearanceMigration extends AbstractJsonColumnMigration
{
    private const array TEXT_APPEARANCE_MAP = [
        'display_one' => '',
        'display_two' => '',
        'display_three' => '',
        'headline_one' => '',
        'headline_two' => '',
        'headline_three' => '',
        'body_one' => '',
        'body_two' => '',
        'body_three' => '',
    ];

    protected function getTables(): array
    {
        return ['tl_content'];
    }

    protected function getColumns(): array
    {
        return ['kiss_styles', 'rsce_data'];
    }

    protected function getValueMaps(): array
    {
        return [
            'kiss_styles' => ['textAppearance' => self::TEXT_APPEARANCE_MAP],
            'rsce_data' => ['textAppearance' => self::TEXT_APPEARANCE_MAP],
        ];
    }
}
```

### Templates

- Update every `{% use %}` of the attribute templates. They moved from `kiss_component/card/`,
  `kiss_component/action/` and `kiss_component/text/` to `kiss_attributes/`. A missed path fails with
  `Unable to find template "@Contao/kiss_component/…"`.
- `fe_page.html.twig` is gone. If you still need the legacy page template, see [`docs/legacy.md`](docs/legacy.md).
- The icon element no longer has an icon position. Stored positions do nothing, so check icons that relied on it.
- The `content_element/gallery/grid` template is gone because it is the default gallery template now. Switch elements
  that used it as a custom template back to the default.
- The `image_single` Swiper template is gone. Switch elements that use it to an element group.
- The `logo` Swiper template is gone. Move it into your project's `contao/templates/content_element/swiper/` if you
  still use it.
- The accordion section headline now has an appearance. An unlayered `.handorgel__header` rule in your CSS wins over
  it, so move that rule into `@layer components`.

## From 0.7 to 0.8

### Sizes

The `x_small` and `x_large` sizes are gone. Content that stored them renders no size class and falls back to the
default size. This affects buttons (`ctaSize`, `callToAction.size`, form submit), cards (`elementSize`), form fields
(`fieldSize`), and the alert and badge RSCE elements.

```bash
bin/console contao_kiss:find-style-values ctaSize,elementSize,fieldSize,alertSize,badgeSize x_small x_large
```

```php
class ContentSizeKissStylesMigration extends AbstractJsonColumnMigration
{
    private const array SIZE_MAP = [
        'x_small' => 'small',
        'x_large' => 'large',
    ];

    protected function getTables(): array
    {
        return ['tl_content', 'tl_form_field'];
    }

    protected function getColumns(): array
    {
        return ['kiss_styles', 'rsce_data', 'callToAction'];
    }

    protected function getValueMaps(): array
    {
        return [
            'kiss_styles' => ['ctaSize' => self::SIZE_MAP, 'elementSize' => self::SIZE_MAP, 'fieldSize' => self::SIZE_MAP],
            'rsce_data' => ['alertSize' => self::SIZE_MAP, 'badgeSize' => self::SIZE_MAP],
            'callToAction' => ['ctaSize' => self::SIZE_MAP],
        ];
    }
}
```

### Configuration and code

- Remove `contao_kiss.style_definition_override` from your `config.yaml`.
- If you listened to `StyleOptionEvent`, register or replace the option with `#[AsKissStyleOption]` instead. See
  [`docs/style-options.md`](docs/style-options.md).

## From 0.6 to 0.7

0.7 moves the framework from SCSS to plain CSS and ships its own build setup with Vite and Symfony Reprise. How your
project built its assets before is up to your project. The full setup is documented in [`docs/build-tools.md`](docs/build-tools.md).

1. Update the package together with its dependencies with `composer update digitaledinge/contao-kiss -W`.
2. Add the KISS npm package to your `package.json` and build with `buildVite()` in `vite.config.mjs`:

   ```json
   "@digitaledinge/contao-kiss": "file:vendor/digitaledinge/contao-kiss/build"
   ```

   ```js
   import { buildVite } from '@digitaledinge/contao-kiss/vite';

   export default buildVite();
   ```

   `buildVite()` turns every `layout/*.js` into an entry and gives each one `layout/css/index.css` as its stylesheet.
   For a second stylesheet, add it to the config:

   ```js
   export default async (env) => {
       const config = await buildVite()(env);
       config.input['app.other.css'] = './layout/css/index.other.css';

       return config;
   };
   ```

3. Make sure `layout/app.js`, `layout/css/index.css`, `layout/fonts/` and `layout/css/assets/` exist. The output goes
   to `public/layout/` with `entrypoints.json` and `manifest.json`.
4. `assets/scss/` is gone. Replace imports from `vendor/digitaledinge/contao-kiss/assets/scss/...` with
   `@import "@digitaledinge/contao-kiss/css/index";`. Single partials are still available, for example
   `@digitaledinge/contao-kiss/css/components/index`. Tailwind 4 doesn't run behind a preprocessor, so your theme has to
   be plain CSS as well. Spell out the extension in imports (`@import "./header.pcss";`) and use native nesting.
5. The Stimulus controllers moved from `assets/js/` to `build/assets/js/`. Import them from the package. The names
   didn't change: `import { ThemeController, PopoverController } from '@digitaledinge/contao-kiss';`
6. Import vendor CSS in CSS, not from JavaScript, for example
   `@import "glightbox/dist/css/glightbox.min.css" layer(components);`.
7. The datepicker styles, the `vanillajs-datepicker` dependency and the legacy icon font rules (`[data-icon]`) are gone.
   If you still need them, add the dependency and the CSS to your project.
8. `page/layout.html.twig` now renders the theme assets in the `kiss_theme_assets` block. Remove your own theme
   `<script>` and `<link>` tags. To use another entry in a layout variant, extend `@Contao/page/layout.html.twig` and set
   `{% set kiss_theme_entry = 'app.other' %}`.
9. Run the KISS Twig CS Fixer rules over your templates and fix what they report. Old overrides often pass variables
   into KISS components with `include()`. Replace those with `{% use %}` and `{% with {…} %}{{ block(…) }}{% endwith %}`.
10. Run `npm run build`. `public/layout/entrypoints.json` should start with `"isProd": true`.

| Error                                 | Fix                                                                      |
|---------------------------------------|--------------------------------------------------------------------------|
| `The "isProd" key must be a boolean.` | `public/layout/entrypoints.json` isn't from Reprise. Run `npm run build` |
| `Could not find the entrypoints file` | Run `npm run build` or start the dev server                              |
| Vendor styles are missing             | Move the vendor CSS import from JavaScript into your CSS                 |

## From 0.5 to 0.6

### Migrated automatically

- Media text elements get the new "show media" checkbox enabled, and their `type` key becomes `mediaType`
  (`ContentMediaTypeRsceDataMigration`)

### Media text layout

`cardLayout` is now `elementLayout`, and the `media_full` layout is now `media_background`. Check what is stored:

```bash
bin/console contao_kiss:find-style-values cardLayout,elementLayout media_full --table=tl_content
```

```php
class MediaTextLayoutMigration extends AbstractJsonColumnMigration
{
    protected function getTables(): array
    {
        return ['tl_content'];
    }

    protected function getColumns(): array
    {
        return ['kiss_styles', 'rsce_data'];
    }

    protected function getKeyRenames(): array
    {
        return [
            'kiss_styles' => ['cardLayout' => 'elementLayout'],
            'rsce_data' => ['cardLayout' => 'elementLayout'],
        ];
    }

    protected function getValueMaps(): array
    {
        return [
            'kiss_styles' => ['elementLayout' => ['media_full' => 'media_background']],
            'rsce_data' => ['elementLayout' => ['media_full' => 'media_background']],
        ];
    }
}
```

### Code, CSS and templates

- Rename `styles.card_layout` to `styles.media_layout` and `addLinkField()` to `addImageUrlField()`.
- Pass `text` instead of `description` or `message` to the alert component.
- Rename the design token classes and properties you use yourself. Stored style values keep working:
  `.responsive-display1` becomes `.responsive-display-lg`, `.fixed-title1` becomes `.fixed-title-lg`,
  `.fixed-body1` becomes `.fixed-body-xl`, and `--kiss-sys-sizing-responsive-semantic-container-*` becomes
  `--kiss-sys-sizing-responsive-default-container-*`. The full table is in the changelog.
- `mod_navigation_horizontal` and `mod_navigation_vertical_md_horizontal` are gone. Modules still set to one of them
  fail with `Could not find template "mod_navigation_vertical_md_horizontal"`. Switch them to the default navigation
  template, or add an override in your project's `contao/templates/`:

  ```twig
  {% extends '@Contao/mod_navigation' %}

  {% set wrapperAttributes = attrs(cssID)
      .addClass(['mod_navigation--vertical', breakpoint|default('md') ~ ':mod_navigation--horizontal'])
      .mergeWith(wrapperAttributes|default)
  %}
  ```

- Headlines no longer allow HTML. Do that in your app if you need it.

## From 0.4 to 0.5

### Migrated automatically

- Background colors `base_100`, `base_200`, `base_300` and `base_content` become `neutral_one`, `neutral_two`,
  `neutral_three` and `neutral_inverse` (`ArticleContentBackgroundColorKissStylesMigration`)
- Article content widths: an empty value becomes `base` and `small` becomes `narrower`
  (`ArticleContentWidthKissStylesMigration`)

### Colors

`accent` is now `tertiary`, and `info` is gone. These are not migrated.

```bash
vendor/bin/contao-console contao_kiss:find-style-values backgroundColor,ctaColor,fieldColor,color accent info
```

```php
class AccentColorMigration extends AbstractJsonColumnMigration
{
    private const array COLOR_MAP = [
        'accent' => 'tertiary',
        'info' => '',
    ];

    protected function getTables(): array
    {
        return ['tl_content', 'tl_article', 'tl_form_field'];
    }

    protected function getColumns(): array
    {
        return ['kiss_styles', 'rsce_data', 'callToAction'];
    }

    protected function getValueMaps(): array
    {
        return [
            'kiss_styles' => [
                'backgroundColor' => self::COLOR_MAP,
                'ctaColor' => self::COLOR_MAP,
                'fieldColor' => self::COLOR_MAP,
            ],
            'rsce_data' => ['ctaColor' => self::COLOR_MAP],
            'callToAction' => ['color' => self::COLOR_MAP],
        ];
    }
}
```

### Font sizes

The font sizes `x_small` to `xxx_large` are gone from the headline appearance and `textAppearance`. Pick a design
system typography value for each one that fits your design:

```bash
vendor/bin/contao-console contao_kiss:find-style-values appearance,textAppearance x_small small medium large x_large xx_large xxx_large
```

```php
class FontSizeAppearanceMigration extends AbstractJsonColumnMigration
{
    private const array APPEARANCE_MAP = [
        'x_small' => 'body_three',
        'small' => 'body_two',
        'medium' => 'body_one',
        'large' => 'headline_three',
        'x_large' => 'headline_two',
        'xx_large' => 'headline_one',
        'xxx_large' => 'display_three',
    ];

    protected function getTables(): array
    {
        return ['tl_content', 'tl_module', 'tl_form_field'];
    }

    protected function getColumns(): array
    {
        return ['headline', 'kiss_styles', 'rsce_data'];
    }

    protected function getValueMaps(): array
    {
        return [
            'headline' => ['appearance' => self::APPEARANCE_MAP],
            'kiss_styles' => ['textAppearance' => self::APPEARANCE_MAP],
            'rsce_data' => ['textAppearance' => self::APPEARANCE_MAP],
        ];
    }
}
```

The mapping above is an example. There is no one-to-one match between the old sizes and the new typography.

### Code and templates

- Rename `styles.font_size` to `styles.font_appearance` in your templates.
- `form_inline` is now `form_wrapper_grid`.
- Replace `imageMargin` with `showAsCard`.

## From 0.3 to 0.4

Version 0.4 is a complete rewrite and needs PHP `^8.3` and Contao `^5.7`.
You may want to check the deactivated `Migration/Version004/ArticleContentKissStylesMigration` for ideas on how to migrate towards `0.4`
Otherwise, there is no proper upgrade path. Rebuild the project with the new components and style options.
