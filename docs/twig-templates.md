# Twig templates

KISS extends Contao's templates instead of replacing them. Every element, component and hook below can be changed in
your project through the Twig inheritance chain, without copying a whole template.

## The inheritance chain

```
Contao core content_element/_base  →  KISS content_element/_base  →  element / component
```

Each level enriches `attributes`, and the core block renders them at the end. The KISS `_base` adds the layout and
appearance classes (width, spacing, background, text alignment) and includes the content wrapper for lists, grids and
the Swiper.

To change a template in your project, put a template with the same name into your `templates/` folder and extend the
KISS one. Contao resolves the chain for you.

## Adding classes

Set `attributes` at template root and merge it. A root-level `set` runs before the inherited blocks render, so the
parent keeps working with your classes:

```twig
{% extends '@Contao/content_element/text.html.twig' %}

{% set attributes = attrs()
    .addClass('my-text')
    .addClass('is-highlighted', data.highlight|default)
    .mergeWith(attributes|default)
%}
```

- Enrich `HtmlAttributes`, never replace them: `attrs(attributes|default).addClass(...)` or
  `attrs()...mergeWith(attributes|default)`.
- `addClass` takes a condition as second argument. Use it instead of `{% if %}` wrappers.
- Style option classes come from the `styles` Twig global. The context prefix belongs in the template:
  `'card-' ~ styles.size(data.elementSize|default)`. See [style options](style-options.md).
- Every `x_attributes` hook you set has to merge the incoming one, `attrs(x_attributes|default)…` or
  `.mergeWith(x_attributes|default)`. The `AttrsHookMerge` Twig CS Fixer rule checks it.

### Shared class sets

The card and button classes live in attributes blocks, so they are never copied. `{% use %}` the block and merge it
with its condition:

```twig
{% use '@Contao/kiss_attributes/_button_attributes.html.twig' %}

{% set link_attributes = attrs(link_attributes|default)
    .mergeWith(block('button_attributes'), data.ctaAsButton|default)
%}
```

| Block               | Template                                       |
|---------------------|------------------------------------------------|
| `button_attributes` | `kiss_attributes/_button_attributes.html.twig` |
| `card_attributes`   | `kiss_attributes/_card_attributes.html.twig`   |

## Overriding blocks

Override a block only when the markup changes, and call `{{ parent() }}` wherever the parent logic should survive.
Overriding a block just to change a class duplicates the parent and decouples you from future changes.

```twig
{% extends '@Contao/content_element/headline.html.twig' %}

{% block content %}
    <div class="headline-decoration"></div>
    {{ parent() }}
{% endblock %}
```

## `extends`, `use` and `include`

| Tag               | Use it for                                                                                     |
|-------------------|------------------------------------------------------------------------------------------------|
| `{% extends %}`   | building on a template and its blocks, e.g. a news template on the media & text wrapper        |
| `{% use %}`       | importing the blocks of a component, then calling them with `{{ block('name') }}`              |
| `{{ include() }}` | fragments without blocks or configuration, e.g. `kiss_component/media/_icon_include.html.twig` |

A block imported with `use` inherits the context of the calling template. Nothing has to be passed through, and the
component's attribute hooks stay settable from anywhere in the chain:

```twig
{% use '@Contao/kiss_component/status/_badge.html.twig' %}
{% extends '@Contao/content_element/_base.html.twig' %}

{% block content %}
    {% for item in list %}
        {{ block('badge') }}
    {% endfor %}
{% endblock %}
```

### Mapping names with `with`

When the caller's names differ from the component's, map exactly those names for one block call. `with` without
`only` keeps the rest of the context, so attribute hooks still arrive, and the mapping ends at `{% endwith %}`:

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

Never feed a component with `data|merge({…})`. `data` is the whole `tl_content` row, and every column would land in
the component's context.

## Core components

KISS changes Contao's own components the same way: `{% use %}` the core component and redefine only the blocks that
change. `component/_download.html.twig` adds the button classes and an icon to the download link:

```twig
{% use '@Contao/component/_download.html.twig' %}
{% use '@Contao/kiss_attributes/_button_attributes.html.twig' %}

{% block download_link_attributes -%}
    {%- set download_link_attributes = attrs(download_link_attributes)
        .mergeWith(block('button_attributes'), data.ctaAsButton|default)
    -%}
    {{- download_link_attributes -}}
{%- endblock %}
```

`component/_figure.html.twig` is not a closed fragment either. It has overridable blocks (`media`, `media_link`,
`caption`, `caption_inner`) and takes `figure_attributes`, `picture_attributes`, `source_attributes`, `img_attributes`,
`link_attributes` and `caption_attributes`. Use it like Contao does:

```twig
{% use '@Contao/component/_figure.html.twig' %}

{% with {figure: image} %}{{ block('figure_component') }}{% endwith %}
```

## Include elements

Templates rendered through an include element (form, module, article) get the include element's row from
`kiss_include_data()`, just like `data` in a content element:

```twig
{% set form_data = kiss_include_data() %}
```

## Reusing the media & text component

A new card or teaser is almost always an `extends` on `kiss_component/media/_media_text_wrapper.html.twig`. You get
media types, headline, text, call-to-action and all attribute hooks for free. `news_card.html.twig` maps a news teaser
onto it:

```twig
{% extends '@Contao/kiss_component/media/_media_text_wrapper.html.twig' %}

{% set text = teaser|default %}
{% set show_as_card = true %}
```

## Attribute hooks

Everything from the media & text wrapper down to the `<img>` can be changed through variables:

| Variable                 | Targets                                                       |
|--------------------------|---------------------------------------------------------------|
| `attributes`             | outer wrapper (as card: `.card`)                              |
| `media_attributes`       | wrapper of the medium (`.media`)                              |
| `headline_classes`       | the headline                                                  |
| `topline_attributes`     | the topline                                                   |
| `text_attributes`        | wrapper of the text (`.text`, as card `.card-body`)           |
| `text_inner_attrs`       | the rich text element (`.rte`)                                |
| `cta_wrapper_attributes` | call-to-action wrapper                                        |
| `cta_attributes`         | every button inside the call-to-action                        |
| `figure_attributes`      | image: `<figure>`                                             |
| `caption_attributes`     | image: `<figcaption>`                                         |
| `link_attributes`        | image: the link around a linked image                         |
| `img_class`              | image: class on `<img>`                                       |
| `video_attributes`       | video: `<video>`                                              |
| `source_attributes`      | video: `<source>`                                             |
| `show_as_card`           | switches on the card classes                                  |
| `tag_name`               | outer tag, default `div`; `li` inside a list                  |

The content wrapper, included by everything that extends the KISS `_base`:

| Variable                  | Behaviour                                                                     |
|---------------------------|-------------------------------------------------------------------------------|
| `kiss_swiper`             | renders the items in a swiper                                                 |
| `list_mode`               | renders the list wrapper, active automatically for lists and configured grids |
| `list_tag_name`           | list wrapper tag, default `div`; `ul` or `ol` for real lists                  |
| `list_wrapper_attributes` | list wrapper, carries `.grid` and the grid settings                           |

## Images

`kiss_component/media/_image.html.twig` embeds Contao's `component/_figure.html.twig` and adds two switches:

- `hide_figcaption` hides the `<figcaption>`. Images inside a card hide it by default.
- The `figure_extra` block adds content after the caption, inside the `<figure>`:

```twig
{% extends '@Contao/content_element/image.html.twig' %}

{% block figure_extra %}
    <span class="image-badge">New</span>
{% endblock %}
```

## Accordion headlines

Elements inside an accordion use the collection headline widget, so tag and appearance can differ: an `h2` can look
like an `h3`. The core `AccordionController` only passes `header` and `header_tag`, so the template reads the
appearance through the `kiss_content_model` Twig function.

## Standalone components

Self-contained components (badge, alert, icon, switch …) live in `kiss_component/<group>/_<name>.html.twig` and follow
one structure, with `kiss_component/status/_badge.html.twig` as the reference:

1. A docblock with `@param` for every value read and a `Usage:` example
2. Exactly one root block named after the component
3. `{% set item = item|default(_context) %}` as the first line, then every value read as `item.<field>|default`, so
   the component works in a loop and in a single call
4. Classes only through `attrs()`, with a `<name>_attributes` hook as base
5. Icons only through `kiss_component/media/_icon_include.html.twig`
