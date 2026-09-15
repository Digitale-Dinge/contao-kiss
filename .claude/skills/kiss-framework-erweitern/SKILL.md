---
name: kiss-framework-erweitern
description: Erweiterung des contao-kiss Frameworks (Digitale Dinge, Contao 5 Bundle) um neue Komponenten, StyleOptions, Manipulatoren oder Templates. Diesen Skill IMMER nutzen, wenn im contao-kiss Repo oder einem KISS-basierten Contao-Projekt gearbeitet wird und es um Twig-Templates, Components (Card, media_text, CTA …), StyleOptions/Modifier, das styles-Twig-Global, rsce-Elemente, tl_content-Felder oder kiss_styles geht — auch wenn der User nur sagt "bau mir eine Component" oder "füge Option X hinzu".
---

# KISS Framework erweitern

contao-kiss ist ein Contao-5-Bundle mit einem eigenen Style-System. Fast alles, was du bauen willst, existiert in Teilen schon. Die teuersten Fehler in diesem Framework entstehen nicht durch fehlendes Können, sondern durch **Bauen ohne vorher zu lesen** und durch **ungefragte Nebenänderungen**. Dieser Skill verhindert beides.

## Regel Null: Scope-Disziplin

Ändere ausschließlich das, was verlangt wurde. Konkret:

- Kein Enum-Case, kein Feld, keine Übersetzung "weil es logisch dazugehört" — auch wenn ein Docblock es schon erwähnt.
- Keine "Bugfixes nebenbei" in Code, den du nur durchquerst. Wenn dir etwas kaputt vorkommt: dem User **melden**, nicht fixen. Das Framework hat Konventionen, die du möglicherweise nicht vollständig verstehst — was wie ein Bug aussieht, kann Absicht sein.
- Wenn eine Anforderung mehrdeutig ist ("Nutze X" — aber wie genau?): **nachfragen**, nicht die plausibelste Interpretation still umsetzen. Eine Rückfrage kostet eine Minute, eine falsche Interpretation einen Review-Zyklus.

## Schritt 1: MoSCoW-Planung vor dem ersten Edit

Zerlege die Aufgabe nach dem MoSCoW-Prinzip und halte dich daran:

- **Must** — exakt das angeforderte Feature, nicht mehr. Das ist die einzige Kategorie, die du ungefragt umsetzt.
- **Should** — Verdrahtung, ohne die das Must nicht funktioniert (Options-Callback, DCA-Feld, Übersetzungen DE **und** EN, Subpalette). Gehört zum Must dazu.
- **Could** — naheliegende Erweiterungen, fehlende Nachbar-Optionen, Verbesserungen. Dem User **vorschlagen**, niemals umsetzen.
- **Won't** — alles andere: Renames, Refactorings, "fehlende" Enum-Cases, Deprecated-Aufräumen. Explizit benennen und liegen lassen.

## Schritt 2: Bestandsaufnahme — erst lesen, dann bauen

Bevor du irgendetwas Neues anlegst, lies die existierenden Bausteine. Die Frage ist nie "wie baue ich das?", sondern "wo existiert das schon?":

| Was du suchst | Wo es liegt (im contao-kiss Repo) |
| --- | --- |
| Alle Style-Optionen (Enums + Option-Klassen) | `src/Styles/Option/` — `Modifier/` (Size, Variant), `Color/` (Color, Background), `Layout/`, `Typography/`, `Padding/`, `Margin/`, `Component/` |
| Das `styles`-Twig-Global | `src/Twig/Global/StylesVariable.php` |
| Backend-Options-Callbacks | `src/EventListener/DataContainer/StyleOptionsListener.php` |
| DCA-Felder (`targetColumn: kiss_styles`) + Subpaletten | `contao/dca/tl_content.php` |
| Übersetzungen | `translations/{de,en}/style_options.*.yaml`, `contao_tl_content.*.yaml`, `rsce.*.yaml` |
| KISS-Basis-Template (erbt vom Contao-Core) | `contao/templates/content_element/_base.html.twig` |
| Listen/Grid/Swiper-Wrapper | `contao/templates/kiss_component/_content_wrapper.html.twig` |
| Media-Komponenten (media_text, image, video, icon, text) | `contao/templates/kiss_component/media/` |
| Call-to-Action | `contao/templates/kiss_component/action/` |
| rsce-Element-Configs + Builder | `contao/templates/rsce_*_config.php`, `src/CustomElementsConfigurationBuilder.php` |

Faustregel: Bevor du ein neues Enum, ein neues Feld oder ein neues Twig-Global vorschlägst, musst du benennen können, warum **keine** der existierenden Optionen passt. "Ich habe es nicht gefunden" zählt nicht — grep zuerst.

## Das Style-System verstehen

Der Kern des Frameworks ist die Trennung von gespeichertem Wert und ausgegebener Klasse:

1. In der Datenbank (`kiss_styles`-Spalte) steht nur der **Enum-Case-Name** (z. B. `x_small`, `soft`, `accent`).
2. Das Twig-Global `styles` löst den Key zur CSS-Klasse auf: `styles.size(data.elementSize)` → `xs`. Getter siehe `StylesVariable.php` (`styles.size`, `styles.variant`, `styles.color`, `styles.background`, `styles.container`, `styles.padding_top` …).
3. Der **Kontext-Prefix gehört ins Template**, nicht ins Enum: `'card-' ~ styles.size(...)` → `card-xs`, `'btn-' ~ styles.color(...)` → `btn-primary`. So bedient ein Enum beliebig viele Komponenten.

Daraus folgt: CSS-Klassen sind jederzeit im Enum austauschbar (Neukompilierung, keine DB-Migration), Übersetzungen jederzeit änderbar, nur eine Änderung der Case-*Namen* braucht eine Migration.

**Generisch vs. komponentenspezifisch:** Optionen, die konzeptionell überall vorkommen können (Größen, Varianten wie soft/outline/glass, Farben), gehören in `Modifier/` bzw. `Color/` — auch wenn der aktuelle Anlass nur eine Komponente ist. Nur was wirklich an eine Komponente gebunden ist (z. B. ein Card-Layout `side`/`media-full`), lebt unter `Component/<Name>/`. Gleiche Logik bei Feldnamen: `elementVariant`, nicht `cardVariant`, wenn die Option wiederverwendbar ist.

## Neue Style-Option: die vollständige Kette

Wenn (und nur wenn) nach der Bestandsaufnahme wirklich etwas fehlt, besteht eine Option aus genau diesen Teilen — nichts davon weglassen, nichts hinzuerfinden:

1. **Enum-Case(s)** in einem existierenden Enum ergänzen — oder neues Enum nur bei echter neuer Dimension. Values ohne Kontext-Prefix (wie `Modifier\Size`: `xs`, nicht `card-xs`), außer die Klasse ist untrennbar (wie `Background`: `bg-base-100`). `label()` zeigt auf `style_options.*`.
2. **Option-Klasse** (`XyzOption extends StyleOption`) nur bei neuem Enum; Docblock mit `@method`-Zeilen pflegen.
3. **`StylesVariable`-Getter** nur bei neuem Enum. Neue Twig-Globals sind die Ausnahme, nicht der Reflex.
4. **Options-Callback**: bevorzugt ein zusätzliches `#[AsCallback('tl_content', 'fields.<feld>.options')]`-Attribut auf der **existierenden** Listener-Methode, keine neue Methode für dasselbe Enum.
5. **DCA-Feld** in `tl_content.php`: `inputType: select`, `targetColumn: 'kiss_styles'`, `includeBlankOption: true`. Kein `blankOptionLabel` — der leere Default (`-`) ist Konvention. Feld in die passende Subpalette (z. B. `showAsCard`) eintragen.
6. **Übersetzungen** immer paarweise DE + EN: Option-Labels in `style_options.*.yaml`, Feldlabels in `contao_tl_content.*.yaml` (zwei Zeilen: Label + Beschreibung, keine dritte Default-Zeile).
7. **Template-Verdrahtung** mit Prefix und Condition: `.addClass('card-' ~ styles.variant(data.elementVariant|default), data.elementVariant|default)`.

## Twig: Vererbung statt Überschreiben

Die Template-Kette ist `Contao Core _base` → `KISS _base` → Komponente/Element. Jede Ebene reichert `attributes` an; der Core-Block gibt am Ende aus. Diese Kette respektierst du so:

- **HtmlAttributes anreichern, nie ersetzen:** `attrs(attributes|default).addClass(...)` bzw. `attrs()...mergeWith(attributes|default)`. `addClass` nimmt als zweites Argument eine Condition — nutze sie statt `{% if %}`-Wrappern.
- **Root-Level-`set` statt Block-Override:** Ein `{% set attributes = ... %}` auf Template-Root-Ebene läuft *vor* dem Rendern der geerbten Blöcke — die Basis-Blöcke arbeiten mit deinen angereicherten Attributen weiter. Das ist der Standardweg, um Klassen beizusteuern.
- **Blöcke nur überschreiben, wenn sich die *Struktur* ändert** (anderes Markup, andere Reihenfolge) — und dann mit `{{ parent() }}`, wo immer die Eltern-Logik erhalten bleiben soll. Wer einen Block überschreibt, nur um eine Klasse zu ändern, dupliziert die gesamte Eltern-Logik und koppelt sich von zukünftigen Basis-Änderungen ab.
- **Komponenten wiederverwenden statt Parallelstrukturen bauen:** Eine "neue Card-Component" ist fast immer ein `{% extends %}` auf `kiss_component/media/_media_text_wrapper.html.twig` mit `show_as_card` (siehe `news_card.html.twig`) — kein neues Standalone-Template. Wer erbt, bekommt Media-Typen, CTA, Headline und alle Attribute-Hooks (`media_attributes`, `text_attributes`, `headline_classes` …) geschenkt.
- `{% use %}` importiert Blöcke ohne Vererbung (z. B. `_text.html.twig`, `_headline.html.twig`), `{{ include(...) }}` für in sich geschlossene Teile (`_figure`, `_icon_include`).

Konkrete Positiv- und Negativbeispiele (inkl. dokumentierter realer Fehlversuche): **lies `references/beispiele.md`, bevor du Templates schreibst.**

## Standalone-Component in `kiss_component/`: der verbindliche Aufbau

Manche Komponenten sind kein Ableger der media_text-Kette, sondern ein in sich geschlossenes Stück Markup (Badge, Alert, Icon, Switch …). Diese leben unter `contao/templates/kiss_component/<gruppe>/_<name>.html.twig` und folgen **immer** demselben Aufbau — Referenz ist `kiss_component/status/_badge.html.twig` (siehe Positivbeispiel 4 in `references/beispiele.md`):

1. **Docblock als API-Vertrag**: einleitender `{# … #}`-Kommentar mit `@param {typ} name - Beschreibung` für **jeden** gelesenen Wert plus ein `Usage:`-Beispiel. Wer einen Parameter ergänzt, ergänzt ihn in beiden Teilen.
2. **Genau ein Root-Block** mit dem Namen der Komponente (`{% block badge %}`, `{% block alert %}`, `{% block icon_text %}`). Er ist die öffentliche Schnittstelle — ohne ihn bleibt Aufrufern nur Copy-Paste.
3. **`{% set item = item|default(_context) %}`** als erste Zeile im Block. Danach werden **alle** Werte konsequent als `item.<feld>|default` gelesen — ausnahmslos, auch element-weite Optionen. So funktioniert die Komponente in der Schleife (`item` ist die Loop-Variable) wie beim Einzelaufruf (`_context` greift).
4. **Klassen nur über `attrs()`**, nie über zusammengebaute Strings oder `|join(' ')`: `attrs(<name>_attributes|default)` als Basis (Attribute-Hook für Aufrufer), dann `.addClass('<prefix>-' ~ styles.<getter>(item.x|default), item.x|default)` — Prefix im Template, Condition als zweites Argument.
5. **Icons ausschließlich über `{{ include('@Contao/kiss_component/media/_icon_include.html.twig', {icon: item.icon}) }}`** — nie `<i class="…">`. Redakteurstexte durch `|insert_tag`, HTML-fähige Felder (`allowHtml`) durch `|insert_tag_raw`.

### Die Logik lebt in der Component, das RSCE ruft nur den Block auf

Das RSCE-Template enthält **keine** Komponenten-Logik: keine `styles.*()`-Auflösung, keine Klassennamen der Komponente, keine Werte-Übergabe. Es importiert die Component per `{% use %}` und ruft ihren Block auf:

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

Warum `{% use %}` + `{{ block('badge') }}` und **nicht** `{{ include(..., {…}) }}`:

- Der Block erbt den Kontext des aufrufenden Templates. Es muss nichts durchgereicht, nichts per `|merge` ins Item gehoben, nichts umbenannt werden.
- Aufrufer können die Attribute-Hooks der Component (`badge_attributes`, `badge_outer_attributes` …) per Root-Level-`set` setzen — von überall in der Vererbungskette, ohne die Component anzufassen.
- Der Block bleibt überschreibbar. `include` friert dagegen die Aufrufsignatur ein und erzeugt eine zweite, parallele API.

Die Feldnamen der Component sind deshalb **identisch mit den Feldnamen der `rsce_<name>_config.php`** (`badgeSize`, `badgeShape`, nicht `size`/`shape`). Umbenennen im RSCE-Template ist ein Symptom dafür, dass noch durchgereicht statt vererbt wird.

**Faustregel:** Steht im RSCE-Template ein `styles.`, ein `badge-`/`alert-`-Klassenname oder eine Variablen-Map an `include()`, gehört das in die Component.

### Globale Enums und Builder-Funktionen zuerst

Vor jedem handgeschriebenen Options-Array, jeder Übersetzungszeile im PHP und jeder neuen Twig-Variable gilt: **es existiert wahrscheinlich schon etwas Globales.**

- **Style-Werte kommen aus den globalen Enums** unter `src/Styles/Option/` (`Modifier\Size`, `Modifier\Variant`, `Color\Color` …) — nie aus einem komponentenspezifischen Parallel-Enum, nie aus einer Liste von Strings im Config-File.
- **Options-Listen baut der Builder**, nicht das Config-File. `->addStyleOptionsField('<feld>', <Enum>::class)` erzeugt Select, Optionen und Labels aus dem Enum (intern `TranslatableEnumTrait::getTranslatedOptions()`). Für Options ohne Enum: `->addDependsOnField('<feld>', ['', 'a', 'b'])` — führendes `''` erzeugt die Blank-Option, die Labels kommen aus `rsce.field.<feld>.options.*`.
- **Nie Klartext-Labels im Config-File.** `'dashed' => 'Gestrichelt'` gehört in `translations/{de,en}/rsce.*.yaml`, nicht in PHP. Das gilt auch für Element-Label und -Beschreibung: `->create('<name>', …)` mit der String-Form, Texte unter `rsce.<name>.label`/`.description`.
- Fehlt eine Builder-Methode für einen wiederkehrenden Fall, **ergänze sie am Builder** statt sie im Config-File auszuschreiben — das ist der Ort, an dem sie alle Elemente erreicht.

### Lokale Sonderwerte gehören in die `_config.php`, nicht ins Enum

Braucht eine Komponente neben den globalen Werten noch eigene (z. B. Badge kennt `soft`/`outline` aus `Modifier\Variant`, dazu aber ein badge-eigenes `dashed`), dann wird das **nur in der `rsce_<name>_config.php` zusammengesetzt** — mit `addDependsOnField()` und den Labels in der `rsce.*.yaml`:

```php
// Badge styles per _badge.scss: the shared soft/outline, extended with a badge-only dashed
->addDependsOnField('variant', ['', 'soft', 'outline', 'dashed'], ['tl_class' => 'w25'])
```

Das globale Enum wird dafür **nicht** erweitert: Ein Case, den nur eine Komponente rendern kann, macht ihn in jedem anderen Auswahlfeld zu einer toten Option. Umgekehrt gilt: Ist der Wert konzeptionell überall brauchbar, gehört er ins globale Enum und nicht ins Config-File. Die Entscheidungsfrage lautet nicht „wo ist es bequemer", sondern „würde eine zweite Komponente diesen Wert rendern können?"

In der Component gehen solche lokalen Werte kommentiert 1:1 durch, ohne `styles.*()`:

```twig
{# Variante und Form sind badge-lokal und werden 1:1 auf die badge-* Klassen abgebildet #}
.addClass('badge-' ~ item.variant|default, item.variant|default)
```

**SCSS dazu:** Icons innerhalb einer Komponente bekommen ihre Größe aus der Schriftgröße, nicht aus festen Werten, damit sie über alle Größenvarianten mitwachsen:

```scss
svg {
    width: 1em;
    height: 1em;

    @apply shrink-0;
}
```

Pro Größenvariante (`badge-xs`, `badge-xl` …) wird dann nur noch die Schriftgröße gesetzt — keine eigene Icon-Regel. Genauso gilt: Was Tailwind schon löst, wird nicht als eigenes Token nachgebaut (`@apply rounded-full` statt `border-radius: var(--radius-full)`).

## Neues Content-Element: immer als RSCE, die vollständige Kette

Neue Content-Elemente werden grundsätzlich als RSCE gebaut (`madeyourday/contao-rocksolid-custom-elements`), nicht als eigene DCA/Model-Klasse. Ein Element besteht aus genau zwei gleichnamigen Dateien in `contao/templates/` plus Übersetzungen:

1. **Bestandsaufnahme zuerst**: das existierende `rsce_*_config.php` mit dem ähnlichsten Bedarf (Liste vs. Einzelelement, Media-Typen) als Vorlage lesen. Prüfen, ob `CustomElementsConfigurationBuilder` (`src/CustomElementsConfigurationBuilder.php`) bereits eine Helper-Methode für das benötigte Feld hat (`addImageField`, `addHeadlineField`, `addIconField`, `addCallToActionField`, `addDependsOnField`, …). Neue `addField()`-Aufrufe nur für wirklich elementspezifische Felder — für alles Wiederverwendbare eine neue Helper-Methode am Builder ergänzen statt Inline-Duplikat.
2. **Config-Datei** `rsce_<name>_config.php`: holt sich `kiss.rsce_config.builder` aus dem Container und startet mit `->create('<name>', '<category>', ['types' => ['content'], 'standardFields' => [...]])`. `<name>` bestimmt per RockSolid-Namenskonvention automatisch den Content-Element-Typ im Backend. `<category>` (zweiter Parameter) an bestehenden Elementen orientieren (`media`, `texts`, …), keine neue Kategorie ohne Rücksprache. Für Listen-Elemente die Item-Felder in `->startList()/->endList()` kapseln; `->addGridGroup()` nur anhängen, wenn ein Grid-Layout gebraucht wird.
3. **Twig-Template** `rsce_<name>.html.twig`: erbt immer von `@Contao/content_element/_base.html.twig`. Für das Markup **keine neue Standalone-Struktur schreiben** — bestehende Komponenten aus `kiss_component/media/` bzw. `kiss_component/action/` per `{% use %}` importieren oder per `{{ include() }}` einbinden (siehe `rsce_icon.html.twig`, `rsce_media_text.html.twig`). Style-Klassen wie im Rest des Frameworks über `styles.*()` mit Kontext-Prefix im Template setzen (siehe Abschnitte oben) — RSCE-Templates sind kein Sonderfall des Style-Systems.
4. **`data.<feld>` vs. Top-Level-Variable — vor dem Template-Schreiben prüfen:** Nur Felder, die als echte Spalte in `tl_content.php` deklariert sind (`headline`, `cssID`, `icon`, `elementVariant`, `backgroundColor`, `cardLayout` …), kommen im RSCE-Template über `data.<feld>` an. Felder, die **nur** in der `rsce_<name>_config.php` per `addField()` definiert wurden und keine eigene DCA-Deklaration in `tl_content.php` haben, landen dagegen ohne `data.`-Präfix direkt als Top-Level-Variable im Template. Vergleiche `rsce_icon.html.twig`/`_icon_text.html.twig`, wo das RSCE-eigene Feld `text` als `text|default` (kein `data.`) gelesen wird, mit `rsce_media_text.html.twig`, das die echte Spalte `elementVariant` als `data.elementVariant` liest. Faustregel: existiert für das Feld ein `$GLOBALS['TL_DCA']['tl_content']['fields']['<feld>']`-Eintrag → `data.<feld>`; sonst (reines RSCE-Feld) → `<feld>` direkt. Diese Unterscheidung lässt sich nicht erraten — im Zweifel den Feldnamen in `tl_content.php` suchen.
5. **Übersetzungen** immer paarweise DE + EN in `translations/{de,en}/rsce.*.yaml`: Element-Label/-Beschreibung unter `rsce.<name>.label`/`.description`, elementspezifische Feldlabels unter `rsce.<name>.field.<feld>.label`/`.description`, wiederverwendbare/generische Feldlabels (z. B. `type`, `callToAction`) unter `rsce.field.<feld>.*`.

Selbstkontrolle speziell für RSCE: Wurde eine bestehende Media-/Action-Komponente per `{% use %}`/`include()` wiederverwendet statt dupliziert? Hat jedes neue Feld einen Eintrag in **beiden** `rsce.*.yaml`-Dateien? Wurde vor jedem neuen `addField()` geprüft, ob nicht schon ein Builder-Helper existiert? Wurde für jedes im Template gelesene Feld geprüft, ob es eine echte `tl_content.php`-Spalte ist (`data.<feld>`) oder ein reines RSCE-Feld (`<feld>` ohne Präfix)?

## Selbstkontrolle vor der Abgabe

- `grep` nach allen neuen Bezeichnern: keine verwaisten Referenzen (Listener, DCA, Templates, Übersetzungen konsistent)?
- `php -l` auf jede geänderte PHP-Datei, YAML-Dateien parsen.
- Diff nochmal lesen mit der Frage: "Welche Zeile hat niemand bestellt?" — jede solche Zeile zurücknehmen oder als Vorschlag melden.
- DE- und EN-Übersetzung beide vorhanden?
- Ist irgendwo ein Prefix im Enum gelandet, der ins Template gehört?
- Hat jede neue Standalone-Component in `kiss_component/` Docblock mit `@param` + `Usage`, einen Root-Block, `item|default(_context)`, `attrs()` statt String-Klassen und `_icon_include.html.twig` statt `<i class>`?
- Bindet das RSCE die Component per `{% use %}` + `{{ block('…') }}` ein, ohne Variablen zu übergeben — und heißen die Component-Felder genau wie die Config-Felder?
- Steht im Config-File noch ein Klartext-Label, ein `foreach` über ein Enum oder ein `$GLOBALS['TL_LANG']`-Verweis statt `addStyleOptionsField()` / `addDependsOnField()` + `rsce.*.yaml`?
