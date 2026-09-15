# Beispiele: KISS-Templates richtig erweitern

Alle Negativbeispiele hier sind real passiert (dokumentierter Chatverlauf beim Bau der Card-Manipulatoren). Sie sind nicht hypothetisch — genau diese Fehler macht man, wenn man das Framework nicht vorher liest.

## Positivbeispiel 1: `rsce_media_text_list.html.twig` (Referenz-Extending)

So sieht eine Komponente aus, die die Vererbungskette voll ausnutzt:

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

Warum das gut ist:

- `extends` auf das KISS-`_base` → Container, Paddings, Grid/Liste, Headline kommen geschenkt.
- `use` holt Blöcke aus `_media_text` → Media-Typen, Text, CTA kommen geschenkt.
- Attribute werden auf **Root-Ebene** per `set` angereichert (`attrs()....mergeWith(...)`), die geerbten Blöcke arbeiten damit weiter.
- Der einzige Block-Override (`media_text`) ändert wirklich Struktur (Wrapper-Div pro Item) — und ruft `{{ parent() }}` auf.
- Conditions als zweites `addClass`-Argument statt `{% if %}`-Verschachtelung.

## Positivbeispiel 2: `news_card.html.twig` (Fremd-Template auf KISS-Component mappen)

Ein Contao-Core-Template (News-Liste) wird zur Card, indem es die existierende Component erbt — nicht indem eine neue gebaut wird:

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

Fremde Variablen (`teaser`, `linkHeadline`) werden per `set` auf die Component-Variablen (`text`, `headline`-Block) gemappt. Mehr braucht es nicht.

## Positivbeispiel 3: `rsce_card_group.html.twig` (Card-Einstellungen einmal fürs ganze Element)

Ein RSCE-Listen-Element, bei dem Größe/Layout/Variante/Hintergrund **nicht pro Listen-Item**, sondern einmal für das ganze Element eingestellt werden und automatisch auf jede Card wirken — weil sie aus `data.*` (Element-Ebene) statt `item.*` (Item-Ebene) gelesen werden:

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

Config-seitig (`rsce_card_group_config.php`) liefert `->addCardStyleFields()` (Builder-Helper, bündelt `backgroundColor`, `elementSize`, `cardLayout`, `elementVariant` als Top-Level-Felder) genau die vier Werte, die hier aus `data.*` gelesen werden — pro Card in der Liste (`item.*`) gibt es dafür **keine** eigenen Felder.

Warum das gut ist:

- Fast identisch zu Positivbeispiel 1 (`rsce_media_text_list.html.twig`) — bewusst kopiert statt neu erfunden, weil es exakt dieselbe Vererbungskette ist.
- Kein `showAsCard`-Toggle: Der Elementname "Card Group" impliziert immer Karten, also entfällt die Bedingung (`show_as_card|default`) komplett — weniger Code für denselben Zweck.
- Root-Level-`set` für `attributes`, **kein** Block-Override: die Basis-Klassen (Container, Paddings, Margins, Background, Text-Alignment) kommen unverändert aus `_base`. Weil es keinen Toggle gibt, muss `backgroundColor` auch nicht aus dem äußeren Wrapper herausgerechnet werden (vgl. Negativbeispiel 3) — es landet bewusst sowohl außen als auch pro Card, das ist hier kein Konflikt.

## Positivbeispiel 4: `_badge.html.twig` + `rsce_badge.html.twig` (Standalone-Component, per `use` eingebunden)

Der Referenzaufbau für Komponenten, die **nicht** von der media_text-Kette erben (Badge, Alert, Switch …). Die Component kennt die ganze Klassen- und Markup-Logik, das RSCE-Template nur noch Wrapper und Schleife.

`contao/templates/kiss_component/status/_badge.html.twig`:

```twig
{#
  KISS Badge Component

  @param {string} text - Badge-Text (Insert-Tags erlaubt)
  @param {string} icon - Icon-Name für svg_icon()
  @param {string} color - Key aus Color (primary, secondary, success …)
  @param {string} variant - Badge-Stil: soft|outline|dashed
  @param {string} badgeSize - Key aus Modifier\Size (x_small, small, large …)
  @param {string} badgeShape - Badge-Form: pill|square
  @param {HtmlAttributes} badge_attributes - zusätzliche Attribute

  Alle Werte werden aus `item` gelesen, ersatzweise aus dem Kontext.

  Usage:
  {% use '@Contao/kiss_component/status/_badge.html.twig' %}
  {{ block('badge') }}
#}

{% block badge %}
    {% set item = item|default(_context) %}

    {# Variante und Form sind badge-lokal und werden 1:1 auf die badge-* Klassen abgebildet #}
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

{# Badges flow inline, so the grid wrapper the list mode would add is replaced by a flex wrapper #}
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

`contao/templates/rsce_badge_config.php` (gekürzt):

```php
->create('badge', 'texts', ['types' => ['content'], 'standardFields' => ['cssID']])

->addStyleOptionsField('badgeSize', Size::class)
// Badge shapes per _badge.scss, the blank option keeps the default corner radius
->addDependsOnField('badgeShape', ['', 'pill', 'square'], ['tl_class' => 'w25'])

->startList()
    ->addStyleOptionsField('color', Color::class)
    // Badge styles per _badge.scss: the shared soft/outline, extended with a badge-only dashed
    ->addDependsOnField('variant', ['', 'soft', 'outline', 'dashed'], ['tl_class' => 'w25'])
    ->addIconField()
->endList()
```

Warum das gut ist:

- **`{% use %}` + `{{ block('badge') }}` statt `include`:** Der Block erbt den Kontext des RSCE-Templates. Nichts wird durchgereicht, nichts umbenannt, nichts per `|merge` ins Item gehoben — und der Block bleibt überall überschreibbar.
- **Feldnamen identisch zur Config** (`badgeSize`, `badgeShape`): Genau deshalb braucht es keine Übergabe. Ein `size:`/`shape:`-Alias im RSCE wäre das Symptom einer durchgereichten statt vererbten Component.
- **Keine Logik im RSCE:** kein `styles.*()`, kein `badge-`-Klassenname. Das RSCE liefert nur den Flex-Wrapper — über `badge_outer_attributes` mit `mergeWith`, damit Aufrufer Klassen ergänzen *und* entfernen können.
- **Globale Enums über den Builder:** `addStyleOptionsField()` zieht Optionen und Labels aus `Modifier\Size` bzw. `Color\Color`. Kein `foreach (Size::cases())` im Config-File, keine Klartext-Labels in PHP.
- **Lokaler Sonderwert `dashed` nur in der Config:** `Modifier\Variant` bleibt unangetastet, weil kein anderes Element `badge-dashed` rendern kann. Label steht in `rsce.field.variant.options.dashed`.
- **Docblock als Vertrag**, Icons über `_icon_include.html.twig`, Text über `|insert_tag` — kein `<i class="…">`, kein `|raw`.

Dazu im SCSS (`assets/scss/components/_badge.scss`) — Icons wachsen mit der Badge-Größe mit, weil sie ihre Größe aus der Schriftgröße beziehen:

```scss
.badge {
    font-size: var(--badge-text);

    // Icons scale with the badge font size instead of keeping their intrinsic size
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

Die Größenvarianten (`.badge-xs` … `.badge-xl`) setzen nur noch `--badge-text` — keine einzige eigene Icon-Regel. Und `rounded-full` kommt von Tailwind, statt als eigenes Token nachgebaut zu werden.

## Negativbeispiel 1: Standalone-Component statt Extending

**Falsch (real passiert):** Ein eigenständiges `_card.html.twig` mit eigenen Blöcken (`card_header`, `card_media`, `card_body` …) und eigener Variablen-API bauen — obwohl `_media_text_wrapper` mit `show_as_card` bereits genau das leistet.

Folgen: doppelte Pflege, zwei APIs für dieselbe Sache, die Standalone-Version kennt weder `kiss_swiper` noch Grid noch die Attribute-Hooks. Die richtige Antwort auf "bau mir eine Card-Component" in KISS ist ein `extends` (siehe Positivbeispiel 2).

## Negativbeispiel 2: Parallel-Enums statt existierender Optionen

**Falsch (real passiert):** Für Card-Varianten ein neues `Component\Card\Variant`-Enum (`card-soft`, `card-outline`, `card-glass`) anlegen — obwohl `Modifier\Variant` existierte und bereits einen `soft`-Case hatte. Dazu ein `Component\Card\Color`, obwohl `Color\Color` existierte. Und für beide neue Twig-Globals `styles.card_variant` / `styles.card_color`.

**Richtig:** `Modifier\Variant` um `outline` und `glass` erweitern (Varianten sind generisch, nicht Card-spezifisch), `Color\Color` unverändert nutzen, Prefix im Template: `'card-' ~ styles.variant(...)`. Das exakt gleiche Muster stand schon sichtbar im Code: `'card-' ~ styles.size(data.elementSize)`. Wer das gelesen hätte, hätte den Fehler nicht gemacht.

Erkennungsfrage: "Enthält mein neuer Enum-Value einen Komponenten-Prefix (`card-`, `btn-` …)?" → Wenn ja, gehört der Prefix ins Template und das Enum ist wahrscheinlich überflüssig.

## Negativbeispiel 3: Block-Override statt Vererbung

**Falsch (real passiert):** In `rsce_media_text.html.twig` den `attributes`-Block überschreiben und die komplette Klassenliste aus dem KISS-`_base` (Container, Paddings, Margins, Background …) duplizieren, nur um eine Klasse zu konditionalisieren:

```twig
{# NICHT SO — dupliziert die gesamte _base-Logik #}
{% block attributes %}
    {% set attributes = attrs(attributes|default)
        .addClass([styles.container(...), styles.padding_top(...), ...])
        .addClass(styles.background(...), not show_as_card)
    %}
    {{ attributes }}
{% endblock %}
```

**Richtig:** Klassen auf Root-Ebene in den vorhandenen `set` einhängen und die Vererbung ihre Arbeit machen lassen:

```twig
{% set attributes = attrs(attributes|default)
    .addClass(['media_text', 'responsive-body3'])
    .addClass('card', show_as_card|default)
    .addClass('card-' ~ styles.size(data.elementSize|default), data.elementSize|default)
%}
```

Jeder Block-Override friert den Stand der Elternlogik ein: Ändert sich `_base`, rendert das überschreibende Template stillschweigend anders als der Rest des Systems.

## Negativbeispiel 4: Scope-Creep

Alle drei real passiert, alle drei mussten im Review zurückgebaut werden:

1. **Ungefragter Enum-Case:** `info` zu `Color\Background` hinzugefügt, "weil der Docblock ihn erwähnt und die Spec ihn braucht". Der Case war absichtlich deprecated. Docblocks sind kein Auftrag.
2. **"Bugfix nebenbei":** In einem Template, das nur durchquert wurde, ein vermeintlich fehlendes `{{ attributes }}` ergänzt. Ob Bug oder Absicht — es war nicht Teil des Auftrags. Melden, nicht fixen.
3. **Ungefragte UX-Extras:** `blankOptionLabel` mit eigener "Standard"-Übersetzung auf neue Selects gesetzt. Konvention des Frameworks ist die leere Option (`-`). Neue Felder kopieren die Konvention der Nachbarfelder, nicht die eigene Vorstellung.

## Negativbeispiel 5: `{% embed %}` um eine Component pro Listen-Item "wiederzuverwenden"

**Falsch (real passiert, beim Bau von `rsce_card_group.html.twig`):** Um die Card-Klassenlogik aus `_media_text_wrapper.html.twig` in einer Schleife wiederzuverwenden, wurde für jedes Listen-Item ein `{% embed %}` der Wrapper-Datei gebaut, weil ihre Card-Logik auf Root-Ebene steht (kein `{% block %}`) und `{% use %}` deshalb nicht greift:

```twig
{# NICHT SO — neues, im Repo unbekanntes Twig-Konstrukt für ein gelöstes Problem #}
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

Technisch funktioniert das (jede `{% embed %}`-Instanz führt den Root-Code der eingebetteten Datei wirklich aus), aber es führt ein komplett neues Twig-Konstrukt ins Repo ein, um etwas zu lösen, das die Kette schon löst: `rsce_media_text_list.html.twig` (Positivbeispiel 1) steht seit Langem im Code und macht exakt dasselbe — Card-Klassen pro Item, in einer Schleife — mit `{% use %}` + Block-Override.

**Richtig:** die paar Zeilen Card-Klassenlogik aus dem Wrapper kopieren (wie in Positivbeispiel 1 und 3), keinen neuen Rendering-Mechanismus einführen:

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

Erkennungsfrage: "Gibt es für dieses Rendering-Muster (N Items, jedes mit denselben paar Zusatzklassen) schon ein Vorbild im Repo?" → Bei RSCE-Listen fast immer ja (`rsce_media_text_list.html.twig`). Ein neues Twig-Feature einzuführen ist teurer als drei Zeilen Klassenlogik zu kopieren — Clean Code schlägt Cleverness.

## Negativbeispiel 6: Werte per `include` an eine Component durchreichen

**Falsch (real passiert, im PR-Review von `rsce_badge` beanstandet):** Die element-weiten Optionen wurden im RSCE-Template umbenannt und per `include` in die Component geschoben:

```twig
{# NICHT SO — reicht durch, statt zu vererben #}
{% for item in list %}
    {{ include('@Contao/kiss_component/status/_badge.html.twig', {
        item: item|merge({size: badgeSize|default, shape: badgeShape|default})
    }) }}
{% endfor %}
```

Drei Folgefehler stecken darin: das `|merge` als Workaround dafür, dass `item` in der Schleife die Loop-Variable ist; die Umbenennung `badgeSize` → `size`, die eine zweite Namens-API erzeugt; und `include`, das die Component von der Vererbungskette abschneidet, sodass Aufrufer weder Attribute setzen noch den Block überschreiben können.

**Richtig:** Feldnamen der Config in der Component verwenden (`item.badgeSize`), `{% use %}` oben, in der Schleife nur `{{ block('badge') }}` — siehe Positivbeispiel 4.

Erkennungsfrage: „Übergebe ich beim Aufruf einer kiss_component überhaupt Variablen?" → Wenn ja, stimmen fast immer die Feldnamen nicht überein, oder es hätte `{% use %}` sein müssen. `include()` bleibt richtig für in sich geschlossene Fragmente ohne Konfiguration (`_icon_include`, `_figure`).

## Negativbeispiel 7: Optionen und Labels von Hand im Config-File

**Falsch (real passiert, im PR-Review von `rsce_badge` beanstandet):**

```php
// NICHT SO — dupliziert den Builder und versteckt Übersetzungen in PHP
$sizeOptions = [];

foreach (Size::cases() as $case) {
    $sizeOptions[$case->name] = $case->label()->trans($translator);
}

$shapeOptions = [
    'pill' => 'Abgerundet',
    'square' => 'Eckig',
];

->addField('badgeShape', [
    'label' => ['Form', 'Bestimmen Sie die Form aller Badges'],
    'inputType' => 'select',
    'options' => $shapeOptions,
    …
])
```

Gleich drei Konventionsbrüche: die Enum-Schleife dupliziert `TranslatableEnumTrait::getTranslatedOptions()`, die Labels stehen als deutscher Klartext in PHP statt in `translations/{de,en}/rsce.*.yaml`, und `create(['Badge', '…'])` in Array-Form verhindert, dass `'label' => true` die Element-Übersetzungen auflösen kann.

**Richtig:**

```php
->create('badge', 'texts', […])
->addStyleOptionsField('badgeSize', Size::class)
->addDependsOnField('badgeShape', ['', 'pill', 'square'], ['tl_class' => 'w25'])
```

Labels und Optionen landen in **beiden** YAML-Dateien unter `rsce.field.badgeShape.label`/`.description`/`.options.*` und `rsce.badge.label`/`.description`.

Erkennungsfrage: „Steht in meinem Config-File ein deutscher oder englischer Satz, ein `foreach` über ein Enum oder ein `$GLOBALS['TL_LANG']`-Verweis?" → Alle drei gehören dort nicht hin.

## Attribute-Hooks der media_text-Kette (Spickzettel)

Wer von `_media_text_wrapper` / `_media_text` erbt, steuert bis zum `<img>` hinunter alles über Variablen — kein Override nötig:

| Variable | Greift auf |
| --- | --- |
| `attributes` | äußerer Wrapper (z. B. `.card`) |
| `media_attributes` | Wrapper des Mediums (`.media`), vererbt in Image/Video/Icon |
| `headline_classes` | Klassen der Headline |
| `topline_attributes` | Topline |
| `text_attributes` | Text-Wrapper (`.text`, als Card `.card-body`) |
| `text_inner_attrs` | Richtext-Element (`.rte`) |
| `cta_wrapper_attributes` / `cta_attributes` | CTA-Wrapper / einzelne Buttons |
| `figure_attributes`, `caption_attributes`, `link_attributes`, `img_class` | Bild-Anatomie |
| `video_attributes`, `source_attributes` | Video-Anatomie |
| `show_as_card`, `tag_name`, `list_tag_name`, `kiss_swiper`, `list_wrapper_attributes` | Verhalten des Wrappers / Content-Wrappers |
