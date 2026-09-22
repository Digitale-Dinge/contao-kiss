# Repository guidelines

Guidance for coding agents working in contao-kiss, Digitale Dinge's starter kit bundle for Contao. Change exactly what
was asked, reuse what exists, and follow the conventions of the area being edited. **Do not over-engineer!**

## Core constraints (strict)

- Preserve backwards compatibility: enum case names, field names, service IDs, template names and blocks, style option
  keys stored in `kiss_styles`.
- Change exclusively what was requested. No enum case, field or translation because it logically belongs.
- Fix what the task requires. In code you are only passing through, report instead of fixing.
- Never remove existing wiring while adding your own.
- No new Twig globals, enums or `attributes` blocks without a sentence saying why no existing option fits.
- Do not create Git commits or touch generated files in `vendor/` or `node_modules/`.
- English everywhere: code, comments, docblocks, identifiers, commit messages, replies. The only German is the content
  of `translations/de/*.yaml`.
- Comments only for intent the code cannot express. In Twig the only allowed `{# #}` is a component docblock.

## Repository context

```
build/                                      npm package, @digitaledinge/contao-kiss
├── vite.mjs                                buildVite(), the projects' Vite config
├── biome.json, stylelint.config.js
└── assets/
    ├── js/controllers/                     Stimulus controllers, exported from js/index.js
    └── css/                                framework stylesheets

src/
├── Styles/Option/                          Modifier/, Color/, Layout/, Typography/,
│                                           Padding/, Margin/, Component/
├── Twig/Global/StylesVariable.php          the `styles` Twig global
├── EventListener/DataContainer/            backend options callbacks
├── CustomElementsConfigurationBuilder.php  rsce element builder
├── Asset/VersionStrategy/                  asset() resolution
├── ContaoManager/Plugin.php
└── DigitaleDingeContaoKissBundle.php

contao/
├── dca/tl_content.php                      fields on kiss_styles, subpalettes
└── templates/
    ├── content_element/_base.html.twig     KISS base template
    ├── page/layout.html.twig               page layout, theme asset tags
    ├── kiss_component/
    │   ├── _content_wrapper.html.twig      list / grid / swiper wrapper
    │   ├── media/                          media_text, image, video, icon, text
    │   └── action/                         call-to-action
    └── rsce_*_config.php, rsce_*.html.twig

translations/{de,en}/                       style_options.*, contao_tl_content.*, rsce.*
public/dist/                                backend assets, committed and served from vendor/
vendor-bin/                                 isolated tooling
```

PHP is PSR-4 under `src/`, tests under `tests/`. `.editorconfig` is authoritative.

## Further reading

| Path | Covers |
| --- | --- |
| `.claude/skills/*/SKILL.md` | the full rules for extending the framework |
| `.claude/skills/*/references/examples.md` | attribute hooks, worked examples, failed attempts |
| `docs/` | the asset pipeline and how projects consume it |

`references/examples.md` is mandatory before the first Twig edit.

## Workflow

1. **Clarify scope.** A spec (a list of options, sizes, colors) is answered with questions and a mapping, not with code.
   Ambiguous requirements get a question, never the most plausible interpretation.
2. **MoSCoW.** Must and Should get implemented, Could and Won't get proposed at the end.
3. **Inventory.** The question is never "how do I build this?" but "where does this already exist?". Grep the paths
   above and map every item onto an existing enum, template hook or builder helper. "I didn't find it" does not count.
4. **Build.** The full chain for style options, inheritance instead of overriding in Twig.
5. **Self-check.** Read your own diff against the list at the end.

Ask for files you cannot read by path instead of guessing their contents.

## The style system

The stored value and the emitted class are separate:

1. `kiss_styles` stores the enum case name (`x_small`, `soft`, `accent`).
2. `styles.size(data.elementSize)` resolves it to `xs`. Getters live in `StylesVariable.php`.
3. The context prefix belongs in the template: `'card-' ~ styles.size(…)`, never in the enum.

Options that can occur anywhere — sizes, variants, colors — belong in `Modifier/` or `Color/`, even when the current
occasion is a single component. Only what is bound to one component lives under `Component/<Name>/`. Field names follow
the same rule: `elementVariant`, not `cardVariant`.

`Color\Background` (`bg-*`, wired as `data.backgroundColor`) and `Color\Color` (primary, secondary, accent, …) are
different dimensions — ask which one is meant.

A new option is a complete chain: enum case, option class and `StylesVariable` getter only for a genuinely new enum,
`#[AsCallback]` on the existing listener method, DCA field with `targetColumn: 'kiss_styles'` in the matching
subpalette, DE **and** EN translations, template wiring with prefix and condition.

## Templates

The chain is Contao core `_base` → KISS `content_element/_base.html.twig` → the element or component template. Each
level enriches `attributes` and the core block emits at the end, so a child sets variables and hooks rather than
re-emitting markup:

```twig
{% extends '@Contao/content_element/_base.html.twig' %}

{% block content %}
    {% set attributes = attrs(attributes|default).addClass('card-' ~ styles.variant(data.elementVariant|default), data.elementVariant|default) %}
    {{ parent() }}
{% endblock %}
```

Everything from `_media_text_wrapper` down to the `<img>` is steerable through variables, so overriding a block is
almost never necessary:

| Variable | Targets |
| --- | --- |
| `attributes` | outer wrapper (as card: `.card`) |
| `media_attributes` | wrapper of the medium (`.media`), inherited into image / video / icon |
| `headline_classes` | classes directly on the headline |
| `topline_attributes` | topline element |
| `text_attributes` | wrapper of the content text (`.text`, as card `.card-body`) |
| `text_inner_attrs` | the richtext element (Contao's `.rte`) |
| `cta_wrapper_attributes` | call-to-action wrapper (`.cta_outer`) |
| `cta_attributes` | every button inside the CTA wrapper |
| `figure_attributes` | image: `<figure>` |
| `caption_attributes` | image: `<figcaption>` |
| `link_attributes` | image: the outer link when the image is linked |
| `img_class` | image: class on `<img>` |
| `video_attributes` | video: `<video>`, including `poster` |
| `source_attributes` | video: `<source>` |
| `show_as_card` | switches on the card classes and their size/variant/layout/background wiring |
| `tag_name` | outer element tag, default `div`; `li` inside a list |

`_base` includes `kiss_component/_content_wrapper.html.twig`, which controls list, grid and swiper behaviour:

| Variable | Behaviour |
| --- | --- |
| `kiss_swiper` | activates the Swiper wrapper; list mode is skipped |
| `list_mode` | activates the list wrapper; auto-activates for a non-empty `list`, for configured `data.gridColumns` and for `grid_ratio_active` |
| `list_tag_name` | list wrapper tag, default `div`; `ul` / `ol` for real lists, then `tag_name: 'li'` on the items |
| `list_wrapper_attributes` | the list-mode wrapper, carries `.grid` and the grid-ratio settings |

Inherit, do not override what a hook already exposes. `references/examples.md` has the full table and the cases where
overriding was tried and reverted.

Standalone components in `kiss_component/` always have a docblock with `@param` and `Usage`, exactly one root block
named after the component, `{% set item = item|default(_context) %}` as its first line, classes only via `attrs()`, and
icons only via `_icon_include.html.twig`.

For content elements there is the RSCE builder: `rsce_<name>_config.php` goes through
`CustomElementsConfigurationBuilder`, `rsce_<name>.html.twig` extends `content_element/_base.html.twig` and imports
existing components rather than writing new markup. Check for a builder helper before every `addField()`. Whether a
value arrives as `data.<field>` or top-level depends on where the builder stores it — open the helper, do not guess.

## Assets

- `build/vite.mjs` is the projects' Vite config. Changing a default changes every project's build.
- `build/assets/css/` holds the component stylesheets as `.pcss`. Run `npm run lint` in `build/` after every change.
- Tailwind 4, no preprocessor. Nesting is native; `&-suffix` concatenation does not exist in CSS.
- Stylesheet imports spell out the extension.
- New Stimulus controllers get exported from `build/assets/js/index.js`.

## Verification

Run the narrowest useful check first.

```bash
composer unit-tests
composer unit-tests -- --filter TestName
composer depcheck
composer twig-cs-fixer

cd build && npm run lint

# everything CI runs
composer ci
```

## Self-check before handing over

- `grep` every new identifier: listener, DCA, template and translations consistent, no orphans?
- `php -l` on changed PHP files, parse changed YAML.
- DE **and** EN translations present?
- Read the diff and ask which line nobody ordered — and which existing line got removed unasked.
- Any comment restating the code below it? Any `{# #}` outside a component docblock? Any non-English word?
- Did a prefix land in an enum that belongs in the template? Did a new Twig global or `Component\<X>\Variant` appear
  although `Modifier\Variant` exists?
- Do new components have docblock, one root block, `item|default(_context)`, `attrs()`, `_icon_include`?
- Return Could and Won't items as proposals. Unanswered questions stay questions, not assumptions.