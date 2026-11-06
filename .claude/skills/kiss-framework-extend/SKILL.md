---
name: kiss-framework-extend
description: Extends the contao-kiss framework (Digitale Dinge, Contao 5 bundle) with components, StyleOptions, manipulators and templates — inventory before building, strict scope discipline, questions before assumptions. ALWAYS use this skill when working in the contao-kiss repo or any KISS-based Contao project and the task touches Twig templates, components (Card, media_text, CTA …), StyleOptions/Modifier/Enums, the `styles` Twig global, rsce elements, tl_content fields, kiss_styles or backend options — including terse requests like "build me a card component", "add option X", "new variant for Y", "the card needs sizes/colors/layouts" or "why doesn't this class apply?".
---

# Extending the KISS framework

contao-kiss is a Contao >= 5.7 bundle with its own style system. Most of what a task asks for already exists in
parts. The reviews that cost the most came from three mistakes: building before reading, inventing a parallel
structure where the chain already has a hook, and changing things nobody asked for. Every "happened" note below is a
real review finding that had to be reverted; treat it as a boundary.

## Contents

1. [Workflow](#workflow)
2. [Scope](#scope)
3. [CI and tests](#ci-and-tests)
4. [Comments and language](#comments-and-language)
5. [Inventory](#inventory)
6. [The style system](#the-style-system)
7. [New style option](#new-style-option)
8. [Changing a style option](#changing-a-style-option)
9. [Twig](#twig)
10. [Standalone components](#standalone-components)
11. [RSCE config and builder](#rsce-config-and-builder)
12. [New content element](#new-content-element)
13. [Self-check](#self-check)

## Workflow

1. **Clarify scope.** Resolve every ambiguity before the first edit. A spec (a list of options, sizes, colors) is
   answered with a mapping table and questions, not with code (see [Inventory](#inventory)).
2. **MoSCoW.** Must: exactly the requested feature. Should: the wiring without which the Must does not work (options
   callback, DCA field, DE and EN translations, subpalette); part of the Must. Could: obvious extensions and missing
   neighbour options; propose, never implement. Won't: renames (even ones the team discussed, like `text_inner_attrs`
   → `text_inner_attributes`), refactorings, "missing" enum cases, deprecated cleanup; name them and leave them alone.
3. **Inventory.** Grep and read what exists. Map every item of the request onto an existing enum, template hook or
   builder helper.
4. **Build.** The complete chain for a style option; inheritance instead of overriding in Twig.
5. **Self-check** the diff against the [list at the end](#self-check).

Before the first Twig edit read `references/examples.md`: positive references, the real failed attempts, a worked
example of mapping a card spec onto `media_text`, and the table of every attribute hook.

## Scope

Change exclusively what was requested.

- No enum case, field or translation "because it logically belongs", even when a docblock mentions it. A case that
  looks missing may be deprecated on purpose (happened: `info` in `Color\Background` was resurrected).
- No fixes along the way in code you only pass through. Report what looks broken; it may be intent.
- Never remove existing wiring while adding your own (happened: `data.backgroundColor` was deleted from the card
  during an "improvement").
- No new Twig global, enum or `attributes` block without one sentence that says why no existing option fits.
- An ambiguous requirement ("use X", "colors": background or text?) is a question, not a guess.
- A file you cannot read (repo not mounted, branch not checked out) is asked for by path. Ask whether a colleague's
  branch already has part of the task before rebuilding it.
- Could and Won't items go back to the user as proposals at the end. An unanswered question stays a question.

## CI and tests

Never edit what decides whether a check passes, without written permission in the current task:

- `tests/**`: tests, fixtures, snapshots, `CASES` lists, data providers, `markTestSkipped`/`markTestIncomplete`,
  deleted or weakened assertions
- `.twig-cs-fixer.php`, `ecs.php`, `rector.php`, `phpstan.neon*`, `depcheck.php`, `phpunit.xml*`
- `vendor-bin/**`, `.github/**`, the `scripts` in `composer.json` and `build/package.json`, `build/biome.json`,
  `build/stylelint.config.js`
- every ignore list, baseline, `@phpstan-ignore`, `@codeCoverageIgnore`, `SHAME_ON_YOU`, `notName()`/`exclude()` on a
  Finder, `*-disable` comment

A failing lint or test is not permission. "Make the lint pass", "make the tests green", "fix CI", "exclude X", "ok"
and 👍 are not permission. Fix the code, never the check. When the only way out is a CI change:

1. Do not edit. Leave the check failing.
2. Reply with this block on its own, before anything else:

   ```
   ██████████████████████████████████████████████████████████████
   🚨 CI CHANGE REQUESTED — NOTHING HAS BEEN CHANGED 🚨
   File:   <path>
   Change: <exact diff>
   Effect: <which files/rules stop being checked>
   Reply exactly "YES, HERE IS PERMISSION TO DO SO" to apply it.
   ██████████████████████████████████████████████████████████████
   ```

3. Apply it only after the user replies exactly `YES, HERE IS PERMISSION TO DO SO`. Anything else is a no; ask again.
4. After applying, end the final reply with a second 🚨 block naming the file and the change.

Happened: `be_tinyMCE.html.twig` was excluded from twig-cs-fixer with `notName()` after a 👍. The exclusion turned off
every rule for that file, not only the failing one. Reviewers do approve diffs without reading them.

## Comments and language

- Default is no comment. A comment that says what the next line says is deleted: not what a call does, not which
  file a class belongs to, not "maps 1:1", not "the size modifiers below redefine these and nothing else".
- SCSS and PHP may have bare section headers that group lines: `// Sizing`, `// Colors`. A header with a sentence
  appended (`// Sizing: the size modifiers below …`) is narration, not a header.
- Twig has no comments except the docblock of a standalone component (its API contract).
- A why is allowed only when the code cannot show it: a workaround for a Contao or Twig quirk, a deliberate deviation
  from the neighbouring pattern, the issue that forced it. One line.
- English everywhere: code, comments, docblocks, identifiers, commit messages, YAML keys, replies. German only as the
  content of `translations/de/*.yaml` and in existing strings you are told to keep.

## Inventory

The question is never "how do I build this?" but "where does this exist?":

| Looking for | Where (contao-kiss repo) |
| --- | --- |
| Style options (enums + option classes) | `src/Styles/Option/`: `Modifier/` (Size, Variant), `Color/` (Color, Background), `Layout/`, `Typography/`, `Padding/`, `Margin/`, `Component/` (CallToAction, Media, Swiper) |
| The `styles` Twig global | `src/Twig/Global/StylesVariable.php` (`styles.size`, `styles.variant`, `styles.color`, `styles.background`, `styles.container`, `styles.padding_top`, `styles.media_layout`, `styles.cta_variant` …) |
| Registry and attribute | `src/Styles/StyleOptionRegistry.php`, `src/DependencyInjection/` (`AsKissStyleOption`, `AddStyleOptionsPass`); usage in `docs/style-options.md` |
| Back end options callbacks | `src/EventListener/DataContainer/StyleOptionsListener.php` |
| DCA fields (`targetColumn: kiss_styles`) and subpalettes | `contao/dca/tl_content.php` |
| Translations | `translations/{de,en}/style_options.*.yaml`, `contao_tl_content.*.yaml`, `rsce.*.yaml` |
| KISS base template (extends Contao core) | `contao/templates/content_element/_base.html.twig` |
| List, grid, swiper wrapper | `contao/templates/kiss_component/_content_wrapper.html.twig` |
| Shared class sets | `contao/templates/kiss_attributes/` (`_card_attributes`, `_button_attributes`, `_text_appearance_attributes`) |
| Media components (media_text, wrapper, image, video, icon, text) | `contao/templates/kiss_component/media/` |
| Call to action | `contao/templates/kiss_component/action/` |
| Standalone components (badge, alert, switch …) | `contao/templates/kiss_component/<group>/_<name>.html.twig` |
| Core component overrides | `contao/templates/component/`: `{% use %}` the core component, redefine only the blocks that change (`_download.html.twig`: `download_link_attributes`, `download_link_inner`) |
| Include elements (form, module, article) | `kiss_include_data()` returns the include element's row, like `data`. Legacy templates without blocks (`form_inline.html.twig`): extend them and `set` the merged variable at top level |
| RSCE configs and the builder | `contao/templates/rsce_*_config.php`, `src/CustomElementsConfigurationBuilder.php` |
| Framework stylesheets | `build/assets/css/`; the minimal project theme is in `docs/build-tools.md` ("Theme variables") |
| User docs of the Twig rules | `docs/twig-templates.md`; keep it in sync when a rule here changes |

Before proposing a new enum, field or Twig global you must be able to say why none of the existing options fits. "I
didn't find it" does not count. `'card-' ~ styles.size(...)` in a template is the canary: every other card option
follows that pattern. Without the repo, the reference is `main` on `github.com/Digitale-Dinge/contao-kiss`; ask for
the files by path instead of reconstructing them.

**A spec becomes a mapping, not code.** When the request is a list ("the card should support layout, size, style,
color, mediaType, poster …"), the first deliverable is a table "spec item → where it exists / what is missing", then
the open questions. Edit only after the user confirms. The expected shape:

> Sizes already run through `Modifier\Size` (the `card-` prefix is added in the template). For `style` there is
> `Modifier\Variant` with `muted`; I'd add `outline` and `glass` there, not in a card-specific enum, because they are
> generic. Colors: background (`Color\Background`, wired as `data.backgroundColor`) or semantic (`Color\Color`)?
> `poster` belongs in `kiss_component/media/_video.html.twig`, not in the card; is that on a colleague's branch?
> Layout is the only card-bound option. Are the options set in `tl_content.php` or in `StyleOptionsListener`?

The full worked example is in `references/examples.md`.

## The style system

1. The `kiss_styles` column stores only the **enum case name** (`small`, `muted`, `accent`).
2. The `styles` global resolves the case to the class: `styles.size(data.elementSize)` → `sm`.
3. The **context prefix belongs in the template**: `'card-' ~ styles.size(...)` → `card-sm`, `'btn-' ~
   styles.color(...)` → `btn-primary`. One enum serves any number of components.

Because content stores case names, a project that replaces an option in its own app can change values and
translations freely. Inside KISS a changed value changes the class projects style against, and a removed or renamed
case needs a migration. Both are the maintainer's decision: warn and wait for an explicit yes (`AGENTS.md`,
"Backwards compatibility").

**Generic or component-bound.** Options that can occur anywhere (sizes, variants like muted/outline/glass, colors) go
into `Modifier/` or `Color/`, even when only one component needs them today; they will show up on buttons, alerts and
badges next. Only what is bound to one component lives under `Component/<Name>/` (`Component\Media\Layout`,
`Component\CallToAction\Shape`). Field names follow: `elementVariant`, not `cardVariant`, when the option is reusable.

**Two color dimensions.** `Color\Background` (`bg-*`, `data.backgroundColor`) and `Color\Color` (primary, secondary,
accent, success, warning, error) answer different questions. "Add colors to the card" is ambiguous until the user
says which.

## New style option

Only when the inventory shows something is missing. An option is exactly these parts:

1. **Enum case(s)** in an existing enum; a new enum only for a new dimension. Values without context prefix (`sm`,
   not `card-sm`) unless the class is inseparable (`Background`: `bg-base-100`). `label()` points to `style_options.*`.
2. **Option class** (`XyzOption extends StyleOption`) only for a new enum, registered with a bare
   `#[AsKissStyleOption]` (picked up by the `Styles\` resource in `config/services.yaml`); maintain the `@method`
   docblock lines.
3. **`StylesVariable` getter** only for a new enum, delegating to `$this->option(XyzOption::class, $key)`.
4. **Options callback**: one more `#[AsCallback('tl_content', 'fields.<field>.options')]` on the existing listener
   method for that enum, no new method. Callbacks resolve the enum through the registry
   (`$this->registry->getEnum(Xyz::class)`) so replacements apply.
5. **DCA field** in `tl_content.php`: `inputType: select`, `targetColumn: 'kiss_styles'`, `includeBlankOption:
   true`, no `blankOptionLabel` (the empty default `-` is the convention; existing labels such as `elementSize`'s
   "Normal" stay). Register it in the matching subpalette.
6. **Translations** DE and EN: option labels in `style_options.*.yaml`, field labels in `contao_tl_content.*.yaml`
   (label and description, no third line).
7. **Template wiring** with prefix and condition:
   `.addClass('card-' ~ styles.variant(data.elementVariant|default), data.elementVariant|default)`.

## Changing a style option

| Change | Effect | Do |
| --- | --- | --- |
| A case's value | only the emitted class changes | edit freely, no test change |
| New case | `StyleOptionCasesTest` reports it as incomplete | propose the `CASES` addition in a 🚨 block, add it after the exact permission phrase |
| Case removed or renamed | stored content loses its class, the test fails | warn first, touch nothing without confirmation |

Removing or renaming a case is a breaking change. A request to remove it is not the confirmation, even when it quotes
a team decision (happened with `btn-wide`: enum and test snapshot changed in one step, the warning came in the
summary). The steps:

1. Warn in a reply of its own before the first edit: the enum and case, every field that offers it (options callbacks,
   RSCE `addStyleOptionsField()`), every template that emits it. No edit in that reply.
2. Show how to find affected content: `contao_kiss:find-style-values <keys> <cases>` (for example `ctaSize,elementSize
   x_small x_large`). It reads `kiss_styles`, `headline` and `rsce_data`; group fields with their own column
   (`callToAction`) are not covered.
3. Wait for an explicit yes. Until then no edit: not the enum, not the test, not "just the CSS".
4. Update the `StyleOptionCasesTest` snapshot only as part of the confirmed change and only after the exact
   `YES, HERE IS PERMISSION TO DO SO`.
5. Write a migration only when the user asks for one and confirms the mapping.

**Migration.** Check `src/Migration/Version*/` first; if one exists for the same key, ask before adding a second.
Extend `AbstractJsonColumnMigration` with `getTables()`, `getColumns()` and `getValueMaps()` (`column => [key => [old
=> new]]`), plus `getKeyRenames()` for a renamed key. Maps apply at every depth (nested lists, JSON, serialized
strings), values are re-encoded in their original format, `''` clears a value. List every column that stores the key
(`kiss_styles`, `rsce_data`, `headline`, group columns); tables without a column are skipped. Place it in
`src/Migration/Version<NNN>/` of the upcoming release (`MigrationInterface` is autoconfigured) and add a test under
`tests/Migration/Version<NNN>/` like `ContentMediaTypeRsceDataMigrationTest`.

```php
final class ContentSizeKissStylesMigration extends AbstractJsonColumnMigration
{
    private const array SIZE_MAP = ['x_small' => 'small', 'x_large' => 'large'];

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

## Twig

The chain is Contao core `_base` → KISS `_base` → component or element. Each level enriches `attributes`; the core
block emits at the end.

- **Enrich `HtmlAttributes`, never replace them**: `attrs(attributes|default).addClass(...)` or
  `attrs()….mergeWith(attributes|default)`. `addClass` takes a condition as second argument; use it instead of
  `{% if %}`. `HtmlAttributes` deduplicates and drops empty values. The card wrapper is one chain:

  ```twig
  {% set attributes = attrs()
      .mergeWith(block('card_attributes'), show_as_card|default)
      .addClass('media-' ~ styles.media_layout(data.elementLayout|default), data.elementLayout|default)
      .mergeWith(attributes|default)
  %}
  ```

- **Root-level `set`, not a block override, to add classes.** A `{% set attributes = … %}` at template root runs
  before the inherited blocks render, so the base blocks keep working. Card classes belong in the wrapper's existing
  chain (`_media_text_wrapper.html.twig`), not in a new `{% block attributes %}` in `rsce_media_text.html.twig`.
- **Override a block only when the structure changes** (other markup or order), with `{{ parent() }}` where the
  parent logic should survive. Overriding to change a class duplicates the parent and decouples you from base changes.
- **Shared class sets come from an attributes block, never a copy**: card classes from
  `kiss_attributes/_card_attributes.html.twig` (`card_attributes`), `ctaAsButton` button classes from
  `kiss_attributes/_button_attributes.html.twig` (`button_attributes`). `{% use %}` the file and merge the block with
  its condition: `attrs(link_attributes|default).mergeWith(block('button_attributes'), data.ctaAsButton|default)`
  (`hyperlink`, `_download` and `rsce_hyperlink_list` each had their own copy before).
- **Reuse components, no parallel structures.** A "new card component" is an `{% extends %}` of
  `kiss_component/media/_media_text_wrapper.html.twig` with `show_as_card` (see `news_card.html.twig`). The heir gets
  media types, CTA, headline and every attribute hook. A change to a media sub-template goes into that sub-template:
  a video `poster` belongs in `_video.html.twig`, not in the card.
- `{% use %}` imports blocks without inheritance (`_text.html.twig`, `_headline.html.twig`). `{{ include() }}` is
  for self-contained fragments without blocks or configuration (`_icon_include`). `_figure` is neither, see
  [Core components](#core-components).

**Attribute hooks.** Everything from `_media_text_wrapper` down to the `<img>` is steered by variables:
`attributes`, `media_attributes`, `headline_classes`, `topline_attributes`, `text_attributes`, `text_inner_attrs`,
`cta_wrapper_attributes`, `cta_attributes`, `figure_attributes`, `caption_attributes`, `link_attributes`,
`img_class`, `video_attributes`, `source_attributes`, plus the switches `show_as_card` and `tag_name`. The
`_content_wrapper` (included by every `_base` descendant) adds `kiss_swiper`, `list_mode`, `list_tag_name` and
`list_wrapper_attributes`. The full table, with what each hook targets and how `list_mode` activates, is in
`references/examples.md`; read it before overriding a block.

A `{% set x_attributes = attrs()… %}` that ignores an incoming `x_attributes` is fixed the way the `AttrsHookMerge`
lint rule says: `attrs(x_attributes|default)…` or `.mergeWith(x_attributes|default)`. Keep the variable and its
name; do not inline the chain or rename it to silence the rule. Per-item state does not leak: `{{ block('…') }}` and
`include()` each get their own copy of the context, so a `set` inside a block called from a loop stays in that
call. A lint suggestion is rejected only when the rendered page shows it breaks something, never on a theory about
Twig scoping (negative example 9).

### Nested components

A KISS component that calls another already has `item` (`item|default(_context)`), and `block()` passes the context
on, so the nested block is called bare: `{{ block('text') }}`. The only `with` a nested KISS call may have is a
deliberately different `item`, for example `{item: {icon: item.icon, badgeSize: 'large'}}` so the badge does not
render the parent's `item.text`.

Contao core components do not know `item`; they read top-level names (`_rich_text` reads `text`). The KISS component
that includes the core component maps the names there, once:

```twig
{{ include('@Contao/component/_rich_text.html.twig', {
    text: item.text,
    attributes: text_inner_attrs|default,
}) }}
```

Happened (PR #30): `_text` left that mapping to its callers, `_icon_rich_text` and `_media_text` each carried
`{% with {text: item.text|default} %}`, review removed it as bloat, and `rsce_icon_list` rendered every item without
text while the single `rsce_icon` still worked. Before adding or removing a `with`, open the component and every
template it includes and check where each name is read. Verify the list variant, not only the single element.

**When the caller's names are not yours**, map exactly those names with `with`, scoped to the block call. `hyperlink`
provides `data.icon`, `data.iconPosition` and `link_text`; `_icon_text` reads `item.iconPosition`, `icon` and `text`:

```twig
{% use '@Contao/kiss_component/media/_icon_text.html.twig' %}
{% extends '@Contao/content_element/hyperlink.html.twig' %}

{% block text_link %}
    <a{{ attrs(link_attributes|default) }}>
        {% with {item: data, icon: data.icon, text: link_text} %}
            {{ block('icon_text') }}
        {% endwith %}
    </a>
{% endblock %}
```

`with` without `only` keeps the surrounding context, so hooks like `icon_text_attributes` still arrive, and the
mapping ends at `{% endwith %}`.

**Never `data|merge({…})` or `item|merge({…})` to feed a component.** `data` is the whole `tl_content` row: merging
it dumps every column into the context as top-level variables, any of which can shadow a name the component or its
includes read, and the one mapping that matters disappears inside it.

### Core components

Core calls its own components the same way: `image`, `text`, `gallery` and `hyperlink` all `use` `_figure` and call
its block inside `{% with {figure: …} %}`:

```twig
{% use '@Contao/component/_figure.html.twig' %}
{% with {figure: image} %}{{ block('figure_component') }}{% endwith %}
```

`_figure` is not a closed fragment: it has the blocks `media`, `media_link`, `caption`, `caption_inner` and takes
`figure_attributes`, `picture_attributes`, `source_attributes`, `img_attributes`, `link_attributes` and
`caption_attributes`. Extend a caption through those blocks or attributes, never by copying `_figure`'s markup or by
`include(…, false)`.

The one exception is a block-name collision. `{% use %}` imports every block, and `_figure` brings `media` plus,
through `_picture`, `image`. In `kiss_component/media/_image.html.twig` both collide (`image` is its root block,
`media` belongs to `_media_text`), so there, and only there, `_figure` is rendered with `{% embed %}`:

```twig
{% embed '@Contao/component/_figure.html.twig' with {
    figure: figure_object,
    figure_attributes: figure_attributes|default,
    caption_attributes: caption_attributes|default,
    link_attributes: link_attributes|default,
    img_attributes: {class: img_class|default},
    hide_figcaption: hide_figcaption|default(false),
    figure_extra: block('figure_extra'),
} only %}
    {% block caption %}
        {% if not hide_figcaption %}
            {{ parent() }}
        {% endif %}
        {{- figure_extra|default|sanitize_html('contao')|insert_tag_raw -}}
    {% endblock %}
{% endembed %}
```

Because of `only`, only the listed hooks arrive (`picture_attributes` and `source_attributes` never do), and callers
extend the caption through the `figure_extra` block. `{% embed %}` is allowed exclusively in
`kiss_component/media/_image.html.twig`.

## Standalone components

Components that are not heirs of the media_text chain (badge, alert, icon, switch …) live under
`contao/templates/kiss_component/<group>/_<name>.html.twig` and always have the same structure; the reference is
`kiss_component/status/_badge.html.twig` (positive example 4):

1. A **docblock as API contract**: `@param {type} name - description` for every value read, plus a `Usage:`
   example. A new parameter is added in both places.
2. **One root block** named after the component (`{% block badge %}`, `{% block alert %}`). It is the public
   interface.
3. **`{% set item = item|default(_context) %}`** as the first line of the block; afterwards every value, including
   element-wide options, is read as `item.<field>|default`. So the component works in a loop (`item` is the loop
   variable) and in a single call (`_context` kicks in).
4. **Classes only through `attrs()`**, never concatenated strings or `|join(' ')`: `attrs(<name>_attributes|default)`
   as the base (the hook for callers), then `.addClass('<prefix>-' ~ styles.<getter>(item.x|default),
   item.x|default)`.
5. **Icons only through** `{{ include('@Contao/kiss_component/media/_icon_include.html.twig', {icon: item.icon}) }}`,
   never `<i class="…">`. Editor text through `|insert_tag`, HTML fields (`allowHtml`) through `|insert_tag_raw`.

**The logic lives in the component, the RSCE only calls the block.** No `styles.*()`, no component class names, no
value passing in the RSCE template:

```twig
{% use '@Contao/kiss_component/status/_badge.html.twig' %}
{% extends '@Contao/content_element/_base.html.twig' %}

{% set badge_outer_attributes = attrs()
    .addClass(['badges', 'inline-flex', 'flex-wrap', 'gap-2'])
    .mergeWith(badge_outer_attributes|default)
%}

{% block content %}
    <div{{ badge_outer_attributes }}>
        {% for item in list %}
            {{ block('badge') }}
        {% endfor %}
    </div>
{% endblock %}
```

`{% use %}` + `{{ block() }}` and not `{{ include(..., {…}) }}` because the block inherits the caller's context
(nothing passed through, lifted into the item with `|merge` or renamed), callers set the hooks
(`badge_attributes`, `badge_outer_attributes`) by root-level `set` from anywhere in the chain, and the block stays
overridable; `include` freezes the signature and creates a second API. The component's field names are therefore
identical to the field names in `rsce_<name>_config.php` (`badgeSize`, `badgeShape`, not `size`, `shape`). A
`styles.`, a `badge-` class, a variable map for `include()` or a `|merge` in an RSCE template belongs in the
component.

## RSCE config and builder

Before every hand-written options array, translation line in PHP or new Twig variable: something global exists.

- Style values come from the enums under `src/Styles/Option/`, never from a component-specific parallel enum or a
  string list in the config.
- Option lists are built by the builder: `->addStyleOptionsField('<field>', <Enum>::class)` generates select,
  options and labels from the enum. Without an enum: `->addSelectField('<field>', ['', 'a', 'b'])`; the leading `''`
  is the blank option, labels come from `rsce.field.<field>.options.*`.
- No plain-text label in PHP. `'dashed' => 'Dashed'` belongs in `translations/{de,en}/rsce.*.yaml`; element label
  and description go under `rsce.<name>.label` / `.description` with `->create('<name>', …)` in string form.
- A recurring case without a builder method gets a new builder method, not an inline copy in the config.

**Local values go into `_config.php`, not into the enum.** When a component needs its own values next to the global
ones (badge and alert know `muted`/`outline` from `Modifier\Variant` plus their own `dashed`), the list is assembled
only in `rsce_<name>_config.php` with `addSelectField()` and labels in `rsce.*.yaml`:

```php
->addSelectField('variant', ['', 'muted', 'outline', 'dashed'], ['tl_class' => 'w25'])
```

A case only one component can render would be a dead option in every other select. The deciding question is "could
a second component render this value?", not "where is it convenient". In the component such values pass through 1:1
without `styles.*()`: `.addClass('badge-' ~ item.variant|default, item.variant|default)`.

**CSS.** Icons inside a component take their size from the font size, so they grow with every size variant:

```css
svg {
    width: 1em;
    height: 1em;

    @apply shrink-0;
}
```

A size variant (`badge-sm`, `badge-lg`) sets only the font size. What Tailwind solves is not rebuilt as a token
(`@apply rounded-full`, not `border-radius: var(--radius-full)`). Every `var()` in `build/assets/css/` gets a
fallback except the `--kiss-sys-color-*` tokens: `var(--radius-box, .5rem)`, `var(--shadow-card, none)`,
`var(--kiss-comp-alert-spacing-padding-inline, 1rem)`; reuse the fallback the framework already uses for that
variable. A project then only defines the sys colors, which is what the minimal theme in `docs/build-tools.md`
promises.

## New content element

Always an RSCE (`madeyourday/contao-rocksolid-custom-elements`), never a DCA/model class. Two same-named files in
`contao/templates/` plus translations:

1. **Inventory**: the existing `rsce_*_config.php` with the most similar need (list or single element, media types)
   is the template. Check `CustomElementsConfigurationBuilder` for a helper before every field (`addGroup`,
   `addImageField`, `addImageSizeField`, `addRichTextField`, `addBackgroundField`, `addHeadlineField`,
   `addIconField`, `addCallToActionField`, `addStyleOptionsField`, `addSelectField`, `addCardStyleFields`, …).
   `addField()` only for element-specific fields; anything reusable becomes a builder helper. `addField()` always
   has `'label' => true` (resolved from `rsce.<name>.field.<field>.*` / `rsce.field.<field>.*`) plus `inputType` and
   `eval` (`tl_class`, `maxlength`, `mandatory` …), never a label string.
2. **Config** `rsce_<name>_config.php`: `kiss.rsce_config.builder` from the container, then `->create('<name>',
   '<category>', ['types' => ['content'], 'standardFields' => [...]])`. `<name>` sets the element type by RockSolid's
   naming convention and may be hyphenated (`hero-detail` → `rsce_hero-detail_config.php` +
   `rsce_hero-detail.html.twig`). `<category>` follows existing elements (`media`, `texts`, …); no new category
   without asking. List elements wrap item fields in `->startList()/->endList()`; `->addGridGroup()` only for a grid.
3. **Template** `rsce_<name>.html.twig` extends `@Contao/content_element/_base.html.twig`. No new standalone markup:
   import components from `kiss_component/` with `{% use %}` or `{{ include() }}` (see `rsce_icon.html.twig`,
   `rsce_media_text.html.twig`). Style classes through `styles.*()` with the prefix in the template, like everywhere.
4. **`data.<field>` or top-level**: what decides is where the builder stores the value, not whether a `tl_content`
   column of that name exists. Real columns arrive as `data.<field>`: `standardFields` (`cssID`, …), the style
   options in `kiss_styles` (`data.elementVariant`, `data.backgroundColor`, `data.paddingTop` …) and helpers that
   reuse a standard column (`addBackgroundField()` → `data.backgroundColor`). RSCE fields are stored in `rsce_data`
   and arrive top-level, even when a same-named column exists: `addImageField()` gives `singleSRC` / `addImage`,
   `addRichTextField()` and `addField('text', …)` give `text`. `headline` comes top-level from the core context as
   `{text, tagName}`. When in doubt, open the builder helper and check whether it declares a standard field or its
   own.
5. **Translations** DE and EN in `rsce.*.yaml`: `rsce.<name>.label` / `.description`, element-specific fields under
   `rsce.<name>.field.<field>.*`, reusable fields (`type`, `callToAction`) under `rsce.field.<field>.*`.

## Self-check

- Did any CI file or test change? Without the exact `YES, HERE IS PERMISSION TO DO SO` in this task: revert it and
  send the 🚨 block.
- `grep` every new identifier: listener, DCA, templates and translations consistent, no orphan.
- `php -l` on every changed PHP file; parse the YAML files.
- Read the diff asking "which line did nobody order?", and "which existing line did I remove?". Revert or report.
- DE and EN translations both present?
- Any comment that restates the code, a section header with a sentence appended, a `{# #}` in Twig outside the
  component docblock, a non-English word anywhere? Fix it.
- A prefix in an enum that belongs in the template? A new Twig global or a `Component\<X>\Variant` / `Color` enum
  although `Modifier\Variant` / `Color\Color` exist? A deprecated or docblock-only case (`info`) resurrected?
- A case removed or renamed? Then the warning went out in its own reply before the first edit, the user confirmed,
  `StyleOptionCasesTest` changed only after that, and a migration exists only because it was asked for. Otherwise
  stop, revert, warn.
- Card and media classes added in the wrapper's existing chain, not in a new `attributes` block?
- Every new standalone component has the docblock with `@param` and `Usage`, one root block,
  `item|default(_context)`, `attrs()` instead of string classes, `_icon_include.html.twig` instead of `<i class>`?
- Any `data|merge(…)` / `item|merge(…)` feeding a component? Map only the differing names with
  `{% with {…} %}{{ block('…') }}{% endwith %}`.
- Does the RSCE call the component with `{% use %}` + `{{ block('…') }}` without passing variables, and are the
  component's fields named like the config fields?
- A lint warning fixed with the rule's own suggestion? An `x_attributes` variable inlined, renamed or dropped to
  silence `AttrsHookMerge`? Restore `attrs(x_attributes|default)`.
- A new `var(--…)` in `build/assets/css/` without a fallback (other than `--kiss-sys-color-*`)?
- A plain-text label, a `foreach` over an enum or a `$GLOBALS['TL_LANG']` reference in a config file instead of
  `addStyleOptionsField()` / `addSelectField()` + `rsce.*.yaml`?
- RSCE: every field checked for `data.<field>` versus top-level, every new field in both `rsce.*.yaml` files, a
  builder helper checked before every `addField()`, `'label' => true` on every `addField()`?
- Could and Won't items returned as proposals; open questions still questions.
