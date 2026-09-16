# Examples: extending KISS templates the right way

All negative examples here really happened (documented chat history from building the card manipulators and the badge element). They are not hypothetical — these are exactly the mistakes you make when you do not read the framework first.

Contents:

1. Worked example: mapping a card spec onto `media_text`
2. Positive example 1: `rsce_media_text_list.html.twig` (reference extending)
3. Positive example 2: `news_card.html.twig` (mapping a foreign template onto a KISS component)
4. Positive example 3: `rsce_card_group.html.twig` (card settings once per element)
5. Positive example 4: `_badge.html.twig` + `rsce_badge.html.twig` (standalone component via `use`)
6. Negative examples 1–8
7. Cheat sheet: attribute hooks of the media_text chain and the content wrapper

---

## Worked example: mapping a card spec onto `media_text`

The request arrived as a spec for a "Card component". Here is how the inventory maps it onto what exists. Column three is the only place where work happens — everything else is already there. Verify each row against the current branch; some rows describe work in progress, and a colleague may already have pushed it.

| Spec item | Type / values | Where it already exists in KISS | What is genuinely missing |
| --- | --- | --- | --- |
| `layout` | `card-reverse` \| `card-side` \| `card-side-reverse` \| `card-media-full` | — (truly card-bound) | `Component\Card\Layout` enum + `data.cardLayout` wired in the `show_as_card` branch of `_media_text_wrapper.html.twig`, prefix `card-` in the template |
| `size` | `card-xs` \| `card-sm` \| `card-md` (default) \| `card-lg` \| `card-xl` | `Modifier\Size`, `data.elementSize`, already `'card-' ~ styles.size(...)` in the wrapper | nothing |
| `style` | `card-soft` \| `card-outline` \| `card-glass` (default solid = no class) | `Modifier\Variant` already has `soft`; `data.elementVariant` | add `outline`, `glass` cases to `Modifier\Variant` (generic — buttons/alerts/badges will use them too); wire `'card-' ~ styles.variant(...)` in the wrapper |
| `color` | `card-primary` … `card-error` | `Color\Color` (semantic) **and** `Color\Background` (`data.backgroundColor`, already wired on the card) | **ask**: background or text/semantic color? Then wire the existing enum with `card-` prefix; no new enum, no new Twig global |
| `mediaType` | `image` \| `video` \| `icon` | called `type` in `media_text.html.twig` | nothing (do not rename) |
| `image` | Figure from `figure(...)` | `media_text.html.twig` → `kiss_component/media/_image.html.twig` | nothing |
| `video` | video URL | `media_text.html.twig` → `kiss_component/media/_video.html.twig` | nothing |
| `poster` | poster image URL | — | belongs in `_video.html.twig` (via `video_attributes` / a `poster` variable), **not** in the card; ask whether a colleague's branch already has it |
| `icon` | icon markup/class | `media_text.html.twig` → `kiss_component/media/_icon.html.twig` | nothing |
| `headline` | card title | `headline` / `item.headline` in `_media_text.html.twig` (uses the core headline template) | nothing |
| `text` | description, rendered as HTML | `text` / `item.text` → `kiss_component/media/_text.html.twig` | nothing |
| `cta` | CTA markup | `callToAction` / `item.callToAction`, rendered *inside* `_text.html.twig` via `kiss_component/action/_call_to_action.html.twig` — CTA is always part of text | nothing |
| `attributes` | HtmlAttributes for the outer div | `attributes` | nothing |

The resulting template change is a handful of `addClass` lines inside the existing `show_as_card` branch — not a new template, not a new enum family, not a new `attributes` block:

```twig
{% if show_as_card|default %}
    {% set attributes = attrs(attributes|default)
        .addClass(['card', 'card-hover'])
        .addClass('card-' ~ styles.card_layout(data.cardLayout|default), data.cardLayout|default)
        .addClass('card-' ~ styles.size(data.elementSize|default), data.elementSize|default)
        .addClass('card-' ~ styles.variant(data.elementVariant|default), data.elementVariant|default)
        .addClass(styles.background(data.backgroundColor|default), data.backgroundColor|default)
    %}
    {% set text_attributes = attrs(text_attributes|default).addClass('card-body') %}
{% endif %}
```

### The reply the reviewer expected

Instead of code, the first answer to the spec should have looked like this:

> I've checked the existing StyleOptions. You already have `Modifier\Size` for sizes and `Modifier\Variant` with a `soft` case — I'd extend `Variant` with `outline` and `glass` rather than creating a card-specific variant enum, because those variants are generic. Colors: you already have `Color\Background` (wired as `data.backgroundColor`) and `Color\Color` — do you mean background colors or text/semantic colors here? For `poster` the change belongs in `_video.html.twig`, not in the card — is that already on a branch? `layout` is the only option that is really card-specific, so a `Component\Card\Layout` enum is appropriate. How are the options set — directly in `tl_content.php` or via `StyleOptionsListener`? If I can't read those files, please paste `contao/dca/tl_content.php` and `src/EventListener/DataContainer/StyleOptionsListener.php`.

What actually happened instead: parallel enums (`Component\Card\Variant`, `Component\Card\Color`), new Twig globals (`styles.card_variant`, `styles.card_color`), an `info` case added to `Background`, `data.backgroundColor` deleted, a new `attributes` block in `rsce_media_text.html.twig`, and a "bugfix" in `content_element/_base.html.twig`. The review instructions were, verbatim in spirit:

- Remove all but `Component/Card/Layout` and use what already exists.
- Add the missing cases to `Modifier/Variant` — there was already a soft variant in it.
- Use the colors that exist. No need to reinvent the wheel.
- Remove the newly added Twig globals and update the template.
- `data.backgroundColor` was already used — put it back.
- Remove the hallucinated `info` color, it is deprecated. Stay on the original task — if unsure, ask.
- The attribute changes belong in the `show_as_card` branch, not in a new `attributes` block. Inheritance over overriding.

---

## Positive example 1: `rsce_media_text_list.html.twig` (reference extending)

This is what a component that fully uses the inheritance chain looks like:

```twig
{% use '@Contao/component/_headline.html.twig' %}
{% use '@Contao/kiss_component/media/_media_text.html.twig' %}
{% extends '@Contao/content_element/_base.html.twig' %}

{% set show_as_card = data.showAsCard|default %}

{% if show_as_card|default %}
    {% set media_text_item_attributes = attrs()
        .addClass(['card', styles.background(data.backgroundColor|default)])
        .addClass('card-' ~ styles.size(data.elementSize|default), data.elementSize|default)
        .mergeWith(media_text_item_attributes|default)
    %}
    {% set text_attributes = attrs().addClass(['card-body']).mergeWith(text_attributes|default) %}
{% endif %}

{% block media_text %}
    <div{{ attrs(media_text_item_attributes|default).addClass('media_text') }}>
        {{ parent() }}
    </div>
{% endblock %}

{% block content %}
    {% for item in list %}
        {{ block('media_text') }}
    {% endfor %}
{% endblock %}
```

Why this is good:

- `extends` on the KISS `_base` → container, paddings, grid/list, headline come for free.
- `use` pulls blocks from `_media_text` → media types, text, CTA come for free.
- Attributes are enriched at **root level** via `set` (`attrs()....mergeWith(...)`); the inherited blocks keep working with them.
- The only block override (`media_text`) really changes structure (wrapper div per item) — and calls `{{ parent() }}`.
- Conditions as second `addClass` argument instead of `{% if %}` nesting.

## Positive example 2: `news_card.html.twig` (mapping a foreign template onto a KISS component)

A Contao core template (news list) becomes a card by inheriting the existing component — not by building a new one:

```twig
{% extends '@Contao/kiss_component/media/_media_text_wrapper.html.twig' %}

{% set text = teaser|default %}
{% set show_as_card = true %}
{% set attributes = attrs(attributes|default).addClass(['arc_' ~ archive.id, class]) %}

{% block headline %}
    <p class="info"><time datetime="{{ datetime }}">{{ date }}</time> {{ author }}</p>
    <{{ headline_unit|default('h2') }}{{ attrs(headline_attributes|default).mergeWith(headline_classes) }}>{{ linkHeadline|sanitize_html('contao') }}</{{ headline_unit|default('h2') }}>
{% endblock %}
```

Foreign variables (`teaser`, `linkHeadline`) are mapped via `set` onto the component variables (`text`, `headline` block). Nothing more is needed.

## Positive example 3: `rsce_card_group.html.twig` (card settings once for the whole element)

An RSCE list element where size/layout/variant/background are set **not per list item** but once for the whole element and automatically apply to every card — because they are read from `data.*` (element level) instead of `item.*` (item level):

```twig
{% use '@Contao/component/_headline.html.twig' %}
{% use '@Contao/kiss_component/media/_media_text.html.twig' %}
{% extends '@Contao/content_element/_base.html.twig' %}

{% set attributes = attrs(attributes|default).addClass('card_group') %}

{% set media_text_item_attributes = attrs()
    .addClass(['card', styles.background(data.backgroundColor|default)])
    .addClass('card-' ~ styles.size(data.elementSize|default), data.elementSize|default)
    .addClass('card-' ~ styles.card_layout(data.cardLayout|default), data.cardLayout|default)
    .addClass('card-' ~ styles.variant(data.elementVariant|default), data.elementVariant|default)
%}
{% set text_attributes = attrs().addClass(['card-body', 'responsive-body3']).mergeWith(text_attributes|default) %}
{% set headline_classes = attrs().addClass('responsive-headline3').mergeWith(headline_classes|default) %}

{% block media_text %}
    <div{{ attrs(media_text_item_attributes|default).addClass('media_text') }}>
        {{ parent() }}
    </div>
{% endblock %}

{% block content %}
    {% for item in list %}
        {{ block('media_text') }}
    {% endfor %}
{% endblock %}
```

Config-side (`rsce_card_group_config.php`) `->addCardStyleFields()` (builder helper bundling `backgroundColor`, `elementSize`, `cardLayout`, `elementVariant` as top-level fields) delivers exactly the four values read here from `data.*` — per card in the list (`item.*`) there are **no** separate fields.

Why this is good:

- Almost identical to positive example 1 — deliberately copied instead of reinvented, because it is exactly the same inheritance chain.
- No `showAsCard` toggle: the element name "Card Group" always implies cards, so the condition (`show_as_card|default`) disappears entirely — less code for the same purpose.
- Root-level `set` for `attributes`, **no** block override: the base classes (container, paddings, margins, background, text alignment) come unchanged from `_base`. Because there is no toggle, `backgroundColor` does not have to be removed from the outer wrapper (cf. negative example 3) — it deliberately lands both outside and per card; that is not a conflict here.

## Positive example 4: `_badge.html.twig` + `rsce_badge.html.twig` (standalone component via `use`)

The reference structure for components that do **not** inherit from the media_text chain (badge, alert, switch …). The component knows the whole class and markup logic; the RSCE template only knows wrapper and loop.

`contao/templates/kiss_component/status/_badge.html.twig`:

```twig
{#
  KISS Badge Component

  @param {string} text - badge text (insert tags allowed)
  @param {string} icon - icon name for svg_icon()
  @param {string} color - key from Color (primary, secondary, success …)
  @param {string} variant - badge style: soft|outline|dashed
  @param {string} badgeSize - key from Modifier\Size (x_small, small, large …)
  @param {string} badgeShape - badge shape: pill|square
  @param {HtmlAttributes} badge_attributes - additional attributes

  All values are read from `item`, falling back to the context.

  Usage:
  {% use '@Contao/kiss_component/status/_badge.html.twig' %}
  {{ block('badge') }}
#}

{% block badge %}
    {% set item = item|default(_context) %}

    <span{{ attrs(badge_attributes|default)
        .addClass('badge')
        .addClass('badge-' ~ styles.color(item.color|default), item.color|default)
        .addClass('badge-' ~ item.variant|default, item.variant|default)
        .addClass('badge-' ~ styles.size(item.badgeSize|default), item.badgeSize|default)
        .addClass('badge-' ~ item.badgeShape|default, item.badgeShape|default)
    }}>
        {% if item.icon|default %}
            {{ include('@Contao/kiss_component/media/_icon_include.html.twig', {icon: item.icon}) }}
        {% endif %}
        {% if item.text|default %}
            {{ item.text|insert_tag }}
        {% endif %}
    </span>
{% endblock %}
```

`contao/templates/rsce_badge.html.twig`:

```twig
{% use '@Contao/kiss_component/status/_badge.html.twig' %}
{% extends '@Contao/content_element/_base.html.twig' %}

{% set badge_outer_attributes = attrs()
    .addClass(['badges', 'inline-flex', 'flex-wrap', 'gap-2'])
    .mergeWith(badge_outer_attributes|default)
%}

{% block content_wrapper %}
    {{ block('content') }}
{% endblock %}

{% block content %}
    <div{{ badge_outer_attributes }}>
        {% for item in list %}
            {{ block('badge') }}
        {% endfor %}
    </div>
{% endblock %}
```

`contao/templates/rsce_badge_config.php` (abridged):

```php
->create('badge', 'texts', ['types' => ['content'], 'standardFields' => ['cssID']])

->addStyleOptionsField('badgeSize', Size::class)
->addDependsOnField('badgeShape', ['', 'pill', 'square'], ['tl_class' => 'w25'])

->startList()
    ->addStyleOptionsField('color', Color::class)
    ->addDependsOnField('variant', ['', 'soft', 'outline', 'dashed'], ['tl_class' => 'w25'])
    ->addIconField()
->endList()
```

Why this is good:

- **`{% use %}` + `{{ block('badge') }}` instead of `include`:** the block inherits the RSCE template's context. Nothing is passed through, nothing renamed, nothing lifted into the item via `|merge` — and the block stays overridable everywhere.
- **Field names identical to the config** (`badgeSize`, `badgeShape`): that is exactly why no passing is needed. A `size:`/`shape:` alias in the RSCE would be the symptom of a passed-through instead of inherited component.
- **No logic in the RSCE:** no `styles.*()`, no `badge-` class name. The RSCE only supplies the flex wrapper — via `badge_outer_attributes` with `mergeWith`, so callers can add *and* remove classes.
- **Global enums via the builder:** `addStyleOptionsField()` pulls options and labels from `Modifier\Size` and `Color\Color`. No `foreach (Size::cases())` in the config file, no plain-text labels in PHP.
- **Local special value `dashed` only in the config:** `Modifier\Variant` stays untouched because no other element can render `badge-dashed`. Label lives in `rsce.field.variant.options.dashed`.
- **Docblock as contract**, icons via `_icon_include.html.twig`, text via `|insert_tag` — no `<i class="…">`, no `|raw`.
- **Not a single comment in component, RSCE template, config or CSS.** `addDependsOnField('variant', ['', 'soft', 'outline', 'dashed'])` does not need a line above it saying which of those are shared and which is badge-only; `svg { width: 1em }` does not need to be told it scales with the font size. The docblock is the only comment the component carries.

In CSS (`assets/css/components/_badge.css`) icons grow with the badge size because they take their size from the font size:

```css
.badge {
    font-size: var(--badge-text);

    svg {
        width: 1em;
        height: 1em;

        @apply shrink-0;
    }
}

.badge-pill {
    @apply rounded-full;
}
```

The size variants (`.badge-xs` … `.badge-xl`) only set `--badge-text` — not a single icon rule of their own. And `rounded-full` comes from Tailwind instead of being rebuilt as a custom token.

---

## Negative example 1: standalone component instead of extending

**Wrong (really happened):** building a standalone `_card.html.twig` with its own blocks (`card_header`, `card_media`, `card_body` …) and its own variable API — although `_media_text_wrapper` with `show_as_card` already does exactly that.

Consequences: double maintenance, two APIs for the same thing, the standalone version knows neither `kiss_swiper` nor grid nor the attribute hooks. The correct answer to "build me a card component" in KISS is an `extends` (see positive example 2).

## Negative example 2: parallel enums instead of existing options

**Wrong (really happened):** creating a new `Component\Card\Variant` enum (`card-soft`, `card-outline`, `card-glass`) for card variants — although `Modifier\Variant` existed and already had a `soft` case. Plus a `Component\Card\Color`, although `Color\Color` existed. And new Twig globals `styles.card_variant` / `styles.card_color` for both.

**Right:** extend `Modifier\Variant` with `outline` and `glass` (variants are generic, not card-specific), use `Color\Color` / `Color\Background` unchanged, prefix in the template: `'card-' ~ styles.variant(...)`. The exact same pattern was already visible in the code: `'card-' ~ styles.size(data.elementSize)`. Whoever had read that would not have made the mistake.

Detection question: "Does my new enum value contain a component prefix (`card-`, `btn-` …)?" → If yes, the prefix belongs in the template and the enum is probably superfluous.

## Negative example 3: block override instead of inheritance

**Wrong (really happened):** overriding the `attributes` block in `rsce_media_text.html.twig` and duplicating the complete class list from the KISS `_base` (container, paddings, margins, background …) just to make one class conditional:

```twig
{% block attributes %}
    {% set attributes = attrs(attributes|default)
        .addClass([styles.container(...), styles.padding_top(...), ...])
        .addClass(styles.background(...), not show_as_card)
    %}
    {{ attributes }}
{% endblock %}
```

**Right:** hook the classes into the existing `set` at root level — in this case inside the `show_as_card` branch of `_media_text_wrapper.html.twig` — and let inheritance do its work:

```twig
{% set attributes = attrs(attributes|default)
    .addClass(['media_text', 'responsive-body3'])
    .addClass('card', show_as_card|default)
    .addClass('card-' ~ styles.size(data.elementSize|default), data.elementSize|default)
%}
```

Every block override freezes the state of the parent logic: if `_base` changes, the overriding template silently renders differently from the rest of the system.

## Negative example 4: scope creep

All really happened, all had to be reverted in review:

1. **Unrequested enum case:** `info` added to `Color\Background` "because the docblock mentions it and the spec needs it". The case was deliberately deprecated. Docblocks are not a work order.
2. **"Bugfix along the way":** a supposedly missing `{{ attributes }}` added to `content_element/_base.html.twig`, a template that was only passed through. Bug or intent — it was not part of the task. Report, don't fix.
3. **Unrequested UX extras:** `blankOptionLabel` with a custom "Default" translation on new selects. The framework convention is the empty option (`-`). New fields copy the convention of their neighbours, not your own idea.
4. **Deleting existing wiring:** `data.backgroundColor` was already applied to the card and was removed while adding the new options. Adding never licenses removing.
5. **New Twig globals as a reflex:** `styles.card_variant`, `styles.card_color` were added although `styles.variant` / `styles.color` already resolve the same enums. A new getter is only justified by a new enum, and a new enum only by a new dimension.

## Negative example 5: `{% embed %}` to "reuse" a component per list item

**Wrong (really happened, while building `rsce_card_group.html.twig`):** to reuse the card class logic from `_media_text_wrapper.html.twig` in a loop, an `{% embed %}` of the wrapper file was built for every list item, because its card logic sits at root level (no `{% block %}`) and `{% use %}` therefore does not reach it:

```twig
{% block content %}
    {% for item in list %}
        {% embed '@Contao/kiss_component/media/_media_text_wrapper.html.twig' with {
            item: item,
            show_as_card: true,
        } %}
            {% use '@Contao/component/_headline.html.twig' %}
            {% block headline %}
                {% if item.headline.value|default or item.topline|default %}
                    {{ block('headline_component') }}
                {% endif %}
            {% endblock %}
        {% endembed %}
    {% endfor %}
{% endblock %}
```

Technically this works (every `{% embed %}` instance really executes the embedded file's root code), but it introduces a completely new Twig construct into the repo to solve something the chain already solves: `rsce_media_text_list.html.twig` (positive example 1) has been in the code for a long time and does exactly the same — card classes per item, in a loop — with `{% use %}` + block override.

**Right:** copy the few lines of card class logic from the wrapper (as in positive examples 1 and 3), do not introduce a new rendering mechanism:

```twig
{% use '@Contao/kiss_component/media/_media_text.html.twig' %}
...
{% block media_text %}
    <div{{ attrs(media_text_item_attributes|default).addClass('media_text') }}>
        {{ parent() }}
    </div>
{% endblock %}
{% block content %}
    {% for item in list %}
        {{ block('media_text') }}
    {% endfor %}
{% endblock %}
```

Detection question: "Is there already a model in the repo for this rendering pattern (N items, each with the same few extra classes)?" → For RSCE lists almost always yes (`rsce_media_text_list.html.twig`). Introducing a new Twig feature is more expensive than copying three lines of class logic — clean code beats cleverness.

## Negative example 6: passing values into a component via `include`

**Wrong (really happened, flagged in the `rsce_badge` PR review):** the element-wide options were renamed in the RSCE template and pushed into the component via `include`:

```twig
{% for item in list %}
    {{ include('@Contao/kiss_component/status/_badge.html.twig', {
        item: item|merge({size: badgeSize|default, shape: badgeShape|default})
    }) }}
{% endfor %}
```

Three follow-up errors hide in there: the `|merge` as a workaround for `item` being the loop variable; the rename `badgeSize` → `size`, which creates a second naming API; and `include`, which cuts the component off from the inheritance chain so callers can neither set attributes nor override the block.

**Right:** use the config's field names in the component (`item.badgeSize`), `{% use %}` at the top, only `{{ block('badge') }}` in the loop — see positive example 4.

Detection question: "Am I passing any variables at all when calling a kiss_component?" → If yes, the field names almost always don't match, or it should have been `{% use %}`. `include()` remains correct for self-contained fragments without configuration (`_icon_include`, `_figure`).

## Negative example 7: options and labels hand-written in the config file

**Wrong (really happened, flagged in the `rsce_badge` PR review):**

```php
$sizeOptions = [];

foreach (Size::cases() as $case) {
    $sizeOptions[$case->name] = $case->label()->trans($translator);
}

$shapeOptions = [
    'pill' => 'Pill',
    'square' => 'Square',
];

->addField('badgeShape', [
    'label' => ['Shape', 'Sets the shape of all badges'],
    'inputType' => 'select',
    'options' => $shapeOptions,
    …
])
```

Three convention breaks at once: the enum loop duplicates `TranslatableEnumTrait::getTranslatedOptions()`, the labels are plain text in PHP instead of `translations/{de,en}/rsce.*.yaml`, and `create(['Badge', '…'])` in array form prevents `'label' => true` from resolving the element translations.

**Right:**

```php
->create('badge', 'texts', […])
->addStyleOptionsField('badgeSize', Size::class)
->addDependsOnField('badgeShape', ['', 'pill', 'square'], ['tl_class' => 'w25'])
```

Labels and options land in **both** YAML files under `rsce.field.badgeShape.label`/`.description`/`.options.*` and `rsce.badge.label`/`.description`.

Detection question: "Does my config file contain a human-readable sentence, a `foreach` over an enum or a `$GLOBALS['TL_LANG']` reference?" → None of the three belongs there.

## Negative example 8: comments that narrate the code

**Wrong (really happened, flagged in the alert component review):**

```scss
.alert {
    --alert-bg: var(--kiss-comp-alert-color-background, var(--kiss-sys-color-neutral-surface-2));

    // Sizing: the size modifiers below redefine these and nothing else
    --alert-px: var(--kiss-comp-alert-spacing-padding-inline, 1rem);
    --alert-py: var(--kiss-comp-alert-spacing-padding-block, 1rem);
}
```

```twig
{# The variant is alert-local and maps 1:1 onto the alert-* classes #}
<div{{ attrs(alert_attributes|default)
```

```php
// Alert styles per _alert.scss: the shared soft/outline, extended with an alert-only dashed
->addDependsOnField('variant', ['', 'soft', 'outline', 'dashed'], ['tl_class' => 'w25'])
```

Each comment narrates the line under it: the `addClass` says it maps the variant, the method name and array say which values exist, and the SCSS header stops being a header the moment ": the size modifiers below redefine these and nothing else" is appended to it. The Twig one was additionally written in German. Comments like these cost the reviewer a read, rot the moment the line changes and mark the diff as generated.

**Right:**

```scss
.alert {
    --alert-bg: var(--kiss-comp-alert-color-background, var(--kiss-sys-color-neutral-surface-2));

    // Sizing
    --alert-px: var(--kiss-comp-alert-spacing-padding-inline, 1rem);
    --alert-py: var(--kiss-comp-alert-spacing-padding-block, 1rem);
}
```

The Twig and PHP snippets: comment lines deleted, nothing else changes. A bare section header in SCSS or PHP grouping related lines is fine; Twig gets no comments apart from the component docblock in new components, if needed.

Detection question: "Is this a bare section header in SCSS/PHP, or a one-line *why* the code cannot express (a quirk, a deliberate deviation, an issue link)?" → If neither, delete it. In Twig only the `@param` docblock of a standalone component survives.

---

## Cheat sheet: attribute hooks of the media_text chain and the content wrapper

Whoever inherits from `_media_text_wrapper` / `_media_text` controls everything down to the `<img>` via variables — no override needed. Everything that is included below the wrapper inherits these, so a class set here reaches the innermost element.

### `_media_text_wrapper.html.twig` and what it includes

| Variable | Type | Targets |
| --- | --- | --- |
| `attributes` | HtmlAttributes | outer wrapper (as card: `.card`) |
| `media_attributes` | HtmlAttributes | direct wrapper of the medium (`.media`); no wrapper of its own — inherited into image / video / icon |
| `headline_classes` | HtmlAttributes | classes directly on the headline (`.headline` or whatever is set) |
| `topline_attributes` | HtmlAttributes | topline element |
| `text_attributes` | HtmlAttributes | direct wrapper of the content text (`.text`; as card `.card-body`) |
| `text_inner_attrs` | HtmlAttributes | the richtext element (Contao's `.rte`). A rename to `text_inner_attributes` has been discussed — that is a **Won't** unless explicitly requested |
| `cta_wrapper_attributes` | HtmlAttributes | call-to-action wrapper (`.cta_outer`) |
| `cta_attributes` | HtmlAttributes | every button inside the CTA wrapper (e.g. the `btn` option is passed here) |
| `source_attributes` | HtmlAttributes | video: attributes on `<source>` |
| `video_attributes` | HtmlAttributes | video: attributes on `<video>` (a `poster` belongs here / in `_video.html.twig`) |
| `figure_attributes` | HtmlAttributes | image: attributes on `<figure>` |
| `caption_attributes` | HtmlAttributes | image: attributes on `<figcaption>` |
| `link_attributes` | HtmlAttributes | image: attributes on the outer link when the image is linked |
| `img_class` | string | image: class directly on `<img>` |
| `show_as_card` | bool | switches on the current card classes (`card`, `card-body`, size/variant/layout/background wiring) |
| `tag_name` | string | outer element tag, default `div`; set `li` when rendering inside a list |
| `type` | string | media type `image` \| `video` \| `icon` (the spec's `mediaType`) |
| `headline`, `text`, `callToAction` | mixed | content; `callToAction` is rendered inside `_text.html.twig`, never separately |

### `_content_wrapper.html.twig` (included by everything that extends `content_element/_base`)

Every KISS base template of a content element includes the content wrapper; it controls list, grid and swiper behaviour:

| Variable | Type | Behaviour |
| --- | --- | --- |
| `kiss_swiper` | bool | activates the Swiper wrapper; when `true`, list mode is skipped and Swiper is included directly |
| `list_mode` | bool | activates the list wrapper. Also **auto-activates** when `list` exists and has items, when `data.gridColumns` exist (a grid is configured), or when `grid_ratio_active` is set (grid-ratio widget) — but not when `kiss_swiper` is `true` |
| `list_tag_name` | string | outer list wrapper tag, default `div`; set `ul` / `ol` for real lists (then `tag_name: 'li'` on the items) |
| `list_wrapper_attributes` | HtmlAttributes | outer wrapper in list mode — carries `.grid` and the grid-ratio settings |

Verify the auto-activation conditions against the current `_content_wrapper.html.twig` before relying on them; the widget-driven ones (`grid_ratio_active`) depend on which branch is checked out.
