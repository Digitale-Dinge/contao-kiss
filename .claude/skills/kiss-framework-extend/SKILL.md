---
name: kiss-framework-extend
description: Extends the contao-kiss framework (Digitale Dinge, Contao 5 bundle) with components, StyleOptions, manipulators and templates — inventory before building, strict scope discipline, questions before assumptions. ALWAYS use this skill when working in the contao-kiss repo or any KISS-based Contao project and the task touches Twig templates, components (Card, media_text, CTA …), StyleOptions/Modifier/Enums, the `styles` Twig global, rsce elements, tl_content fields, kiss_styles or backend options — including terse requests like "build me a card component", "add option X", "new variant for Y", "the card needs sizes/colors/layouts" or "why doesn't this class apply?".
---

# Extending the KISS framework

contao-kiss is a Contao >= 5.7 bundle with its own style system. Almost everything you are asked to build already exists in parts. The expensive mistakes in this framework do not come from lack of skill — they come from **building before reading**, from **inventing parallel structures** where the chain already provides a hook, and from **unrequested side changes**. This skill prevents all three.

Every negative example referenced here really happened and had to be reverted in review. Treat them as boundaries, not anecdotes.

## Workflow

Keep this order — every step saves the next one:

1. **Clarify scope** (Rule Zero) — resolve ambiguities *before* the first edit. If the request is a spec (a list of options, sizes, colors), answer with questions and a mapping, not with code.
2. **MoSCoW plan** — name Must / Should / Could / Won't.
3. **Inventory** — grep and read what already exists. Map every item of the request onto an existing enum, template hook or builder helper.
4. **Build** — full chain for style options; inheritance instead of overriding in Twig.
5. **Self-check** — read the diff against the checklist at the end.

Before the first Twig edit, additionally read `references/examples.md`. It contains the positive references, seven real failed attempts, a worked example of mapping a card spec onto `media_text`, and the cheat sheet of all attribute hooks.

## Rule Zero: scope discipline

Change exclusively what was requested:

- No enum case, no field, no translation "because it logically belongs" — even if a docblock already mentions it. A case that *looks* missing may be deliberately deprecated (this happened with `info` in `Color\Background`).
- No "bugfixes along the way" in code you are only passing through. If something looks broken: **report** it, do not fix it. The framework has conventions you may not fully know — what looks like a bug can be intent.
- Never **remove** existing wiring while adding your own. `data.backgroundColor` was already used on the card and got deleted during an "improvement" — that is a regression, not a cleanup.
- No new Twig globals, no new enums, no new `attributes` blocks as a reflex. Each of these needs a sentence explaining why *no* existing option fits.
- If a requirement is ambiguous ("use X" — but how exactly? "colors" — background or text?): **ask**, do not silently implement the most plausible interpretation. A question costs a minute; a wrong interpretation costs a review cycle.
- If you cannot read a file you need (repo not mounted, branch not checked out): **ask for it by path** instead of guessing its contents. Colleagues may have already solved part of the task on another branch — ask whether that is the case before rebuilding it.

## Rule One: no comments that restate the code, everything in English

Code comments are the second most common review complaint after scope creep. Lines like these got flagged and had to be deleted:

```scss
// Sizing: the size modifiers below redefine these and nothing else
--alert-px: var(--kiss-comp-alert-spacing-padding-inline, 1rem);
```

(The `// Sizing` header itself is fine — the half-sentence after it is the problem, see below.)

```twig
{# The variant is alert-local and maps 1:1 onto the alert-* classes #}
.addClass('alert-' ~ item.variant|default, item.variant|default)
```

```php
// Alert styles per _alert.scss: the shared soft/outline, extended with an alert-only dashed
->addSelectField('variant', ['', 'soft', 'outline', 'dashed'], ['tl_class' => 'w25'])
```

Every one of them says what the next line already says. A programmer reads `addSelectField('variant', [...])` faster than the sentence describing it. Rules:

- **Default is no comment.** The framework's naming conventions carry the meaning; a comment that explains a well-named line is noise.
- **Never comment the what**: not what a call does, not which file a class name belongs to, not that a value "maps 1:1", not how the size modifiers "redefine these and nothing else".
- **Section headers in SCSS and PHP are fine** when they group a block of related lines: `// Sizing`, `// Colors`, `// Typography` — a bare label, nothing appended. `// Sizing: the size modifiers below redefine these and nothing else` is not a header, it is a narration.
- **Twig gets no comments at all** apart from the component docblock. No section headers, no `{# … #}` above an `addClass`, no `{# NOT LIKE THIS #}` markers.
- **A why is allowed only when it is invisible in the code**: a workaround for a Contao or Twig quirk, a deliberate deviation from a neighbouring pattern, a link to the issue that forced it. One line, states the reason, nothing else. If you cannot name such a reason, delete the comment.
- **The docblock of a standalone component is the one Twig exception** (see the mandatory structure below) — it is the API contract, not commentary.
- **English only, everywhere you write**: code, comments, docblocks, identifiers, commit messages, YAML keys, replies to the user. No German, no mixed German/English. The only German that may appear is the *content* of `translations/de/*.yaml` and existing German strings you are told to keep.

Self-check line for this rule: for every comment in the diff ask "is this a bare section header in SCSS/PHP, or a why the code cannot express?" Anything else goes.

## Step 1: MoSCoW before the first edit

Break the task down and stick to the categories:

- **Must** — exactly the requested feature, nothing more. The only category implemented without being asked.
- **Should** — wiring without which the Must does not work (options callback, DCA field, translations DE **and** EN, subpalette). Belongs to the Must.
- **Could** — obvious extensions, missing neighbour options, improvements. **Propose**, never implement.
- **Won't** — everything else: renames (even ones the team has discussed, like `text_inner_attrs` → `text_inner_attributes`), refactorings, "missing" enum cases, deprecated cleanup. Name explicitly and leave alone.

## Step 2: Inventory — read first, then build

The question is never "how do I build this?" but "where does this already exist?":

| What you are looking for | Where it lives (contao-kiss repo) |
| --- | --- |
| All style options (enums + option classes) | `src/Styles/Option/` — `Modifier/` (Size, Variant), `Color/` (Color, Background), `Layout/`, `Typography/`, `Padding/`, `Margin/`, `Component/` |
| The `styles` Twig global | `src/Twig/Global/StylesVariable.php` |
| Backend options callbacks | `src/EventListener/DataContainer/StyleOptionsListener.php` |
| DCA fields (`targetColumn: kiss_styles`) + subpalettes | `contao/dca/tl_content.php` |
| Translations | `translations/{de,en}/style_options.*.yaml`, `contao_tl_content.*.yaml`, `rsce.*.yaml` (locate with `find . -name 'rsce*.yaml'` — the folder may sit under `src/` or the bundle root) |
| KISS base template (extends Contao core) | `contao/templates/content_element/_base.html.twig` |
| List / grid / swiper wrapper | `contao/templates/kiss_component/_content_wrapper.html.twig` |
| Media components (media_text, wrapper, image, video, icon, text) | `contao/templates/kiss_component/media/` |
| Call-to-action | `contao/templates/kiss_component/action/` |
| rsce element configs + builder | `contao/templates/rsce_*_config.php`, `src/CustomElementsConfigurationBuilder.php` |

Rule of thumb: before you propose a new enum, a new field or a new Twig global, you must be able to say why **none** of the existing options fits. "I didn't find it" does not count — grep first. The `SIZE` modifier is the canary: if `'card-' ~ styles.size(...)` already exists in a template, every other card option follows the same pattern.

If the repo is not available locally, the reference branch is `main` on `github.com/Digitale-Dinge/contao-kiss` — ask the user for the concrete files (`contao/dca/tl_content.php`, `src/EventListener/DataContainer/StyleOptionsListener.php`, `src/Styles/Option/Modifier/*.php`, the relevant templates and translation files) rather than reconstructing them from memory.

### Turning a spec into a mapping, not into code

When the request arrives as a spec ("the card should support layout, size, style, color, mediaType, poster …"), the first deliverable is a **table: spec item → where it already exists / what is genuinely missing**, followed by the open questions. Only after the user confirms do you edit. The expected shape of that answer:

> I checked the existing style options. Sizes already run through `Modifier\Size` (`card-` prefix is added in the template). For `style` there is `Modifier\Variant`, which already has `soft` — I'd add `outline` and `glass` there rather than creating a card-specific enum, since those variants are generic. For colors: do you mean background (`Color\Background`, `data.backgroundColor` is already wired) or text/semantic color (`Color\Color`)? `poster` belongs in `kiss_component/media/_video.html.twig` — is that already on a colleague's branch? Layout is the only truly card-bound option; `Component\Card\Layout` is fine for that. How are the options set — via `tl_content.php` directly or via `StyleOptionsListener`? If I can't read those, please paste them.

See the full worked example in `references/examples.md` ("Worked example: card spec onto media_text").

## Understanding the style system

The core of the framework is the separation between the stored value and the emitted class:

1. The database (`kiss_styles` column) stores only the **enum case name** (e.g. `x_small`, `soft`, `accent`).
2. The `styles` Twig global resolves the key to the CSS class: `styles.size(data.elementSize)` → `xs`. Getters live in `StylesVariable.php` (`styles.size`, `styles.variant`, `styles.color`, `styles.background`, `styles.container`, `styles.padding_top` …).
3. The **context prefix belongs in the template**, not in the enum: `'card-' ~ styles.size(...)` → `card-xs`, `'btn-' ~ styles.color(...)` → `btn-primary`. One enum therefore serves any number of components.

Consequences: CSS classes can be swapped in the enum at any time (recompile, no DB migration), translations can be changed at any time, only changing case *names* requires a migration.

**Generic vs. component-specific:** options that can conceptually occur anywhere (sizes; variants like soft/outline/glass; colors) belong in `Modifier/` or `Color/` — even if the current occasion is only one component. Soft, glass, outline and friends will show up on buttons, alerts and badges next; that is why they live in `Modifier\Variant`, not in `Component\Card\Variant`. Only what is truly bound to one component (a card layout `side` / `media-full`) lives under `Component/<Name>/`. Same logic for field names: `elementVariant`, not `cardVariant`, when the option is reusable.

**Two color dimensions exist — ask which one is meant.** `Color\Background` (`bg-*`, wired as `data.backgroundColor`) and `Color\Color` (semantic colors: primary, secondary, accent, success, warning, error) are different questions. "Add colors to the card" is ambiguous until the user says which.

## New style option: the complete chain

If (and only if) the inventory shows something is really missing, an option consists of exactly these parts — omit nothing, invent nothing:

1. **Enum case(s)** added to an existing enum — a new enum only for a genuinely new dimension. Values without context prefix (like `Modifier\Size`: `xs`, not `card-xs`), unless the class is inseparable (like `Background`: `bg-base-100`). `label()` points to `style_options.*`.
2. **Option class** (`XyzOption extends StyleOption`) only for a new enum; maintain the docblock `@method` lines.
3. **`StylesVariable` getter** only for a new enum. New Twig globals are the exception, not the reflex — `styles.variant`, `styles.color`, `styles.background`, `styles.size` already cover the card.
4. **Options callback**: preferably an additional `#[AsCallback('tl_content', 'fields.<field>.options')]` attribute on the **existing** listener method, no new method for the same enum.
5. **DCA field** in `tl_content.php`: `inputType: select`, `targetColumn: 'kiss_styles'`, `includeBlankOption: true`. No `blankOptionLabel` — the empty default (`-`) is the convention. Register the field in the matching subpalette (e.g. `showAsCard`).
6. **Translations** always in pairs DE + EN: option labels in `style_options.*.yaml`, field labels in `contao_tl_content.*.yaml` (two lines: label + description, no third default line).
7. **Template wiring** with prefix and condition: `.addClass('card-' ~ styles.variant(data.elementVariant|default), data.elementVariant|default)`.

## Twig: inheritance over overriding

The template chain is `Contao core _base` → `KISS _base` → component/element. Each level enriches `attributes`; the core block emits at the end. Respect that chain:

- **Enrich HtmlAttributes, never replace them:** `attrs(attributes|default).addClass(...)` or `attrs()...mergeWith(attributes|default)`. `addClass` takes a condition as second argument — use it instead of `{% if %}` wrappers. `HtmlAttributes` deduplicates and handles empty values, so a chain like this is the whole card:

  ```twig
  {% set attributes = attrs()
      .addClass(['card', 'card-hover'])
      .addClass('card-' ~ styles.card_layout(data.cardLayout|default), data.cardLayout|default)
      .addClass('card-' ~ styles.size(data.elementSize|default), data.elementSize|default)
      .addClass('card-' ~ styles.variant(data.elementVariant|default), data.elementVariant|default)
      .addClass(styles.background(data.backgroundColor|default), data.backgroundColor|default)
      .mergeWith(attributes|default)
  %}
  ```

- **Root-level `set` instead of block override:** a `{% set attributes = ... %}` at template root runs *before* the inherited blocks render — the base blocks keep working with your enriched attributes. This is the standard way to contribute classes. In `_media_text_wrapper.html.twig` the card classes belong inside the existing `show_as_card` branch — not in a freshly added `{% block attributes %}` in `rsce_media_text.html.twig`.
- **Override blocks only when the *structure* changes** (different markup, different order) — and then with `{{ parent() }}` wherever the parent logic should survive. Overriding a block just to change a class duplicates the entire parent logic and decouples you from future base changes.
- **Reuse components instead of building parallel structures:** a "new card component" is almost always an `{% extends %}` on `kiss_component/media/_media_text_wrapper.html.twig` with `show_as_card` (see `news_card.html.twig`) — no new standalone template. Whoever inherits gets media types, CTA, headline and all attribute hooks (`media_attributes`, `text_attributes`, `headline_classes` …) for free. Because of that inheritance, changes to a media sub-template belong in that sub-template: a `poster` for videos goes into `kiss_component/media/_video.html.twig`, not into the card.
- `{% use %}` imports blocks without inheritance (e.g. `_text.html.twig`, `_headline.html.twig`); `{{ include(...) }}` is for self-contained fragments without blocks or configuration (`_icon_include`). `_figure` is not one of them, see below.

### Attribute hooks and wrapper variables

Everything from `_media_text_wrapper` down to the `<img>` is steerable via variables — `attributes`, `media_attributes`, `headline_classes`, `topline_attributes`, `text_attributes`, `text_inner_attrs`, `cta_wrapper_attributes`, `cta_attributes`, `figure_attributes`, `caption_attributes`, `link_attributes`, `img_class`, `video_attributes`, `source_attributes`, plus the behaviour switches `show_as_card`, `tag_name`. The `_content_wrapper` (included by every `_base` descendant) adds `kiss_swiper`, `list_mode`, `list_tag_name` and `list_wrapper_attributes`. The full table with what each hook targets and how `list_mode` auto-activates is in `references/examples.md` — consult it before overriding any block.

A `{% set x_attributes = attrs()… %}` that ignores an incoming `x_attributes` is fixed the way the `AttrsHookMerge` lint rule says: `attrs(x_attributes|default)…` or `.mergeWith(x_attributes|default)`. Keep the variable and its name. Do not inline the chain or rename the variable to get rid of the warning. Also, per-item state does not leak: `{{ block('…') }}` and `include()` each get their own copy of the context, so a `set` inside a block that a loop calls stays inside that one call. A lint suggestion is rejected only when the rendered page shows it breaks something, never on a theory about Twig scoping (negative example 9 in `references/examples.md`).

Concrete positive and negative examples, including the documented real failed attempts: **read `references/examples.md` before writing templates.**

## Standalone component in `kiss_component/`: the mandatory structure

Some components are not descendants of the media_text chain but a self-contained piece of markup (badge, alert, icon, switch …). They live under `contao/templates/kiss_component/<group>/_<name>.html.twig` and **always** follow the same structure — the reference is `kiss_component/status/_badge.html.twig` (positive example 4 in `references/examples.md`):

1. **Docblock as API contract**: leading `{# … #}` comment with `@param {type} name - description` for **every** value read, plus a `Usage:` example. Whoever adds a parameter adds it in both places.
2. **Exactly one root block** named after the component (`{% block badge %}`, `{% block alert %}`, `{% block icon_text %}`). It is the public interface — without it callers are left with copy-paste.
3. **`{% set item = item|default(_context) %}`** as the first line of the block. Afterwards **all** values are read consistently as `item.<field>|default` — without exception, including element-wide options. This way the component works in a loop (`item` is the loop variable) exactly as in a single call (`_context` kicks in).
4. **Classes only via `attrs()`**, never via concatenated strings or `|join(' ')`: `attrs(<name>_attributes|default)` as the base (attribute hook for callers), then `.addClass('<prefix>-' ~ styles.<getter>(item.x|default), item.x|default)` — prefix in the template, condition as second argument.
5. **Icons exclusively via `{{ include('@Contao/kiss_component/media/_icon_include.html.twig', {icon: item.icon}) }}`** — never `<i class="…">`. Editor text through `|insert_tag`, HTML-capable fields (`allowHtml`) through `|insert_tag_raw`.

### The logic lives in the component, the RSCE only calls the block

The RSCE template contains **no** component logic: no `styles.*()` resolution, no class names of the component, no value passing. It imports the component via `{% use %}` and calls its block:

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

Why `{% use %}` + `{{ block('badge') }}` and **not** `{{ include(..., {…}) }}`:

- The block inherits the context of the calling template. Nothing has to be passed through, nothing lifted into the item via `|merge`, nothing renamed.
- Callers can set the component's attribute hooks (`badge_attributes`, `badge_outer_attributes` …) via root-level `set` — from anywhere in the inheritance chain, without touching the component.
- The block stays overridable. `include` freezes the call signature and creates a second, parallel API.

#### When the caller's names are not yours: `{% with %}`

Sometimes the caller is a Contao core element whose context you cannot rename. `hyperlink` provides `data.icon`,
`data.iconPosition` and `link_text`; `_icon_text` reads `item.iconPosition`, `icon` and `text`. Map exactly those
names, scoped to the block call:

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

`with` without `only` keeps the surrounding context, so hooks like `icon_text_attributes` still arrive, and the mapping
ends at `{% endwith %}` instead of leaking into the rest of the block.

**Never `data|merge({…})` or `item|merge({…})` to feed a component.** `data` is the whole `tl_content` row: merging it
dumps every column into the component's context as top-level variables. Which names land there depends on the element,
not on the component's API, and any of them can shadow a name the component or its includes read. The one mapping that
matters (`text: link_text`) disappears inside a merge over an opaque array.

#### Contao core components: the same pattern, one exception

Core calls its own components exactly like this. `image`, `text`, `gallery` and `hyperlink` all `use` `_figure` and
call its block inside `{% with {figure: …} %}`, for example:

```twig
{% use '@Contao/component/_figure.html.twig' %}
{% with {figure: image} %}{{ block('figure_component') }}{% endwith %}
```

`_figure` is not a closed fragment: it has overridable blocks (`media`, `media_link`, `caption`, `caption_inner`) and
takes `figure_attributes`, `picture_attributes`, `source_attributes`, `img_attributes`, `link_attributes` and
`caption_attributes`.

The one exception is a **block-name collision**. `{% use %}` imports every block of the used template, and `_figure`
brings `media` plus, through `_picture`, `image`. In `kiss_component/media/_image.html.twig` both collide: `image` is
its own root block, `media` is a block of `_media_text`. There `_figure` is rendered isolated:

```twig
{{ include('@Contao/component/_figure.html.twig', {
    figure: figure_object,
    figure_attributes: figure_attributes|default,
    caption_attributes: caption_attributes|default,
    link_attributes: link_attributes|default,
    img_attributes: {class: img_class|default},
}, false) }}
```

That freezes the component. Its blocks cannot be overridden from the KISS chain, and only the listed hooks arrive:
`picture_attributes` and `source_attributes` never do. Use `include(…, {…}, false)` only for a collision you can name,
list every hook the caller should keep, and never for a kiss component: the `ComponentInclude` lint rule rejects that.

The component's field names are therefore **identical to the field names in `rsce_<name>_config.php`** (`badgeSize`, `badgeShape`, not `size`/`shape`). Renaming in the RSCE template is a symptom of passing through instead of inheriting.

**Rule of thumb:** if the RSCE template contains a `styles.`, a `badge-`/`alert-` class name, a variable map passed to `include()` or a `|merge` into a component's context, it belongs in the component.

### Global enums and builder functions first

Before every hand-written options array, every translation line in PHP and every new Twig variable: **something global probably exists already.**

- **Style values come from the global enums** under `src/Styles/Option/` (`Modifier\Size`, `Modifier\Variant`, `Color\Color` …) — never from a component-specific parallel enum, never from a list of strings in the config file.
- **Option lists are built by the builder**, not by the config file. `->addStyleOptionsField('<field>', <Enum>::class)` generates select, options and labels from the enum (internally `TranslatableEnumTrait::getTranslatedOptions()`). For options without an enum: `->addSelectField('<field>', ['', 'a', 'b'])` — the leading `''` creates the blank option; labels come from `rsce.field.<field>.options.*`.
- **Never plain-text labels in the config file.** `'dashed' => 'Dashed'` belongs in `translations/{de,en}/rsce.*.yaml`, not in PHP. Same for element label and description: `->create('<name>', …)` in string form, texts under `rsce.<name>.label`/`.description`.
- If a builder method is missing for a recurring case, **add it to the builder** instead of spelling it out in the config file — that is the place where it reaches all elements.

### Local special values belong in `_config.php`, not in the enum

If a component needs its own values besides the global ones (e.g. badge knows `soft`/`outline` from `Modifier\Variant`, plus a badge-only `dashed`), that is assembled **only in `rsce_<name>_config.php`** — with `addSelectField()` and the labels in `rsce.*.yaml`:

```php
->addSelectField('variant', ['', 'soft', 'outline', 'dashed'], ['tl_class' => 'w25'])
```

The global enum is **not** extended for this: a case that only one component can render becomes a dead option in every other select. Conversely: if the value is conceptually usable everywhere, it belongs in the global enum and not in the config file. The deciding question is not "where is it more convenient" but "could a second component render this value?"

In the component such local values pass through 1:1, without `styles.*()`:

```twig
.addClass('badge-' ~ item.variant|default, item.variant|default)
```

**CSS to go with it:** icons inside a component get their size from the font size, not from fixed values, so they grow with all size variants:

```css
svg {
    width: 1em;
    height: 1em;

    @apply shrink-0;
}
```

Per size variant (`badge-xs`, `badge-xl` …) only the font size is then set — no separate icon rule. Likewise: what Tailwind already solves is not rebuilt as a custom token (`@apply rounded-full` instead of `border-radius: var(--radius-full)`).

## New content element: always as RSCE, the complete chain

New content elements are always built as RSCE (`madeyourday/contao-rocksolid-custom-elements`), not as a custom DCA/model class. An element consists of exactly two same-named files in `contao/templates/` plus translations:

1. **Inventory first**: read the existing `rsce_*_config.php` with the most similar need (list vs. single element, media types) as the template. Check whether `CustomElementsConfigurationBuilder` (`src/CustomElementsConfigurationBuilder.php`) already has a helper for the required field (`addGroup`, `addImageField`, `addImageSizeField`, `addRichTextField`, `addBackgroundField`, `addHeadlineField`, `addIconField`, `addCallToActionField`, `addStyleOptionsField`, `addSelectField`, `addCardStyleFields`, …). New `addField()` calls only for truly element-specific fields — for anything reusable add a new helper to the builder instead of an inline duplicate. An `addField()` always uses `'label' => true` (the label resolves from `rsce.<name>.field.<field>.*` / `rsce.field.<field>.*`) plus `inputType` and `eval` (`tl_class`, `maxlength`, `mandatory` …) — never a label string in PHP.
2. **Config file** `rsce_<name>_config.php`: fetches `kiss.rsce_config.builder` from the container and starts with `->create('<name>', '<category>', ['types' => ['content'], 'standardFields' => [...]])`. `<name>` determines the content element type in the backend via RockSolid naming convention and may be hyphenated (`hero-detail` → `rsce_hero-detail_config.php` + `rsce_hero-detail.html.twig`). `<category>` (second parameter) follows existing elements (`media`, `texts`, …); no new category without asking. For list elements wrap item fields in `->startList()/->endList()`; append `->addGridGroup()` only when a grid layout is needed.
3. **Twig template** `rsce_<name>.html.twig`: always extends `@Contao/content_element/_base.html.twig`. For markup **do not write a new standalone structure** — import existing components from `kiss_component/media/` or `kiss_component/action/` via `{% use %}` or embed them via `{{ include() }}` (see `rsce_icon.html.twig`, `rsce_media_text.html.twig`). Set style classes as everywhere else via `styles.*()` with the context prefix in the template — RSCE templates are not a special case of the style system.
4. **`data.<field>` vs. top-level variable — check before writing the template:** what decides is *where the builder stores the value*, not whether a column of that name exists in `tl_content.php`. Values stored in a real column arrive as `data.<field>`: the `standardFields` (`cssID`, …), the style options in `kiss_styles` (`data.elementVariant`, `data.backgroundColor`, `data.cardLayout`, `data.paddingTop` …) and helpers that reuse a standard column (`addBackgroundField()` → `data.backgroundColor`). Values the config declares as RSCE fields are stored in `rsce_data` and arrive as top-level variables without `data.` — **even when a same-named `tl_content` column exists**: `addImageField()` yields `singleSRC` / `addImage`, `addRichTextField()` yields `text`, a custom `addField('text', …)` yields `text`. `headline` is special: the core content-element context provides it top-level as `{text, tagName}` (`headline.text`). Compare `rsce_hero-detail.html.twig` (`singleSRC`, `addImage`, `headline.text` top-level; `data.backgroundColor`, `data.paddingTop` for columns) with `rsce_media_text.html.twig` (`data.elementVariant`). This cannot be guessed — when in doubt, open the builder helper and check whether it declares a standard field or its own field.
5. **Translations** always in pairs DE + EN in `translations/{de,en}/rsce.*.yaml`: element label/description under `rsce.<name>.label`/`.description`, element-specific field labels under `rsce.<name>.field.<field>.label`/`.description`, reusable/generic field labels (e.g. `type`, `callToAction`) under `rsce.field.<field>.*`.

RSCE-specific self-check: was an existing media/action component reused via `{% use %}`/`include()` instead of duplicated? Does every new field have an entry in **both** `rsce.*.yaml` files? Was a builder helper checked for before every new `addField()`? Was every field read in the template checked for being stored in a column (`data.<field>`) or in `rsce_data` (`<field>` without prefix)? Does every `addField()` use `'label' => true`?

## Self-check before handing over

- `grep` for all new identifiers: no orphaned references (listener, DCA, templates, translations consistent)?
- `php -l` on every changed PHP file; parse the YAML files.
- Read the diff again asking "which line did nobody order?" — revert every such line or report it as a proposal. Same question for deletions: "which existing line did I remove without being asked?"
- DE and EN translations both present?
- Any comment in the diff that restates the code below it ("maps 1:1", "styles per _x.scss", a section header with a sentence appended)? Any `{# #}` in Twig outside the component docblock? Delete it. Any non-English word in code, comments, identifiers or the reply? Translate it.
- Did a prefix land in an enum that belongs in the template? Did a new Twig global or a new `Component\<X>\Variant`/`Color` enum appear although `Modifier\Variant` / `Color\Color` exist?
- Did a deprecated or docblock-only case (`info`) get resurrected?
- Are card/media classes added inside the existing `show_as_card` branch of the wrapper, not in a new `attributes` block?
- Does every new standalone component in `kiss_component/` have a docblock with `@param` + `Usage`, one root block, `item|default(_context)`, `attrs()` instead of string classes and `_icon_include.html.twig` instead of `<i class>`?
- Any `data|merge(…)` / `item|merge(…)` feeding a component? Wrong: map only the differing names with `{% with {…} %}{{ block('…') }}{% endwith %}`.
- Does the RSCE include the component via `{% use %}` + `{{ block('…') }}` without passing variables — and are the component fields named exactly like the config fields?
- Did a lint warning get fixed with the rule's own suggestion? Any `x_attributes` variable inlined, renamed or dropped to silence `AttrsHookMerge`? Restore it with `attrs(x_attributes|default)`.
- Does the config file still contain a plain-text label, a `foreach` over an enum or a `$GLOBALS['TL_LANG']` reference instead of `addStyleOptionsField()` / `addSelectField()` + `rsce.*.yaml`?
- Return Could and Won't items to the user as proposals at the end instead of implementing them. Every open question that was not answered stays a question, not an assumption.
