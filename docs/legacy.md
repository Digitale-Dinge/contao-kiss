# Legacy page layout

contao-kiss renders pages with the modern layout, `page/layout.html.twig`, and its slots. It does not work with
`fe_page.html.twig`. Projects that still use the legacy page layout have to add the template themselves.

> [!CAUTION]
> **Disclaimer:** `contao-kiss` does not support `.html5` templates.
> The framework builds on Twig inheritance (`extends`, `use`, blocks), which `.html5` templates cannot take part in.
> No `.html5` template, `fe_page.html5` included, will work with contao-kiss.

## Usage with `fe_page.html.twig`

Create `templates/fe_page.html.twig` in your project:

```twig
{% extends '@Contao/fe_page' %}

{% set bodyAttributes = attrs(bodyAttributes|default).addClass(['text-base', 'bg-neutral-surface-1', 'text-neutral-on-strong', 'min-h-screen']) %}

{% block head %}
    {{ parent() }}
    {% block head_kiss %}
        {{ include('@Contao/kiss_component/theme/_init_script.html.twig') }}
    {% endblock %}
{% endblock %}

{% block header %}
    <header{{ attrs(header_attributes|default).set('id', 'header') }}>
        {{ include('@Contao/partials/page/header/_main.html.twig') }}
        {{ header|raw }}
    </header>
{% endblock %}

{% block footer %}
    <footer{{ attrs(footer_attributes|default).set('id', 'footer') }}>
        <div id="footer-wrapper">
            {{ include('@Contao/partials/page/footer/_main.html.twig') }}
            {{ footer|raw }}
        </div>
    </footer>
{% endblock %}
```

## Limitations

- **No slots.** The slots of `page/layout.html.twig` (`header_meta`, `header`, `main`, `footer_wrapper`, `footer`,
  `footer_meta`) do not exist in the legacy layout. Modules are placed through the layout sections of the page layout,
  and you customize them through the blocks of core's `fe_page` (`head`, `header`, `footer` etc.).
- **No theme assets.** The `kiss_theme_assets` block is part of `page/layout.html.twig` only. You will have to include
  your theme's JS and CSS yourself (examples in the `build-tools.md` will NOT work with your legacy template).
- Attribute hooks like `header_meta_attributes` or `main_attributes` are not available. `bodyAttributes`,
  `header_attributes` and `footer_attributes` are.
