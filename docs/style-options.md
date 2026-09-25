# Style options

Style options map a stored key to a CSS class. Each option is an enum of cases
plus an option class, registered with `#[AsKissStyleOption]`.

## Using them

```twig
{{ styles.column(data.gridColumns) }}                    {# kiss getter #}
{{ styles.shape_radius(data.shapeRadius) }}              {# custom name #}
{{ styles.options('shape_radius', data.shapeRadius) }}   {# several keys, space-separated #}
```

In PHP, an option resolves by its option class, its enum class or its custom name:

```php
$styles->option(ColumnOption::class, $key);
$builder->addStyleOptionsField('gridColumns', Column::class);
```

`option()` and the getters return the option object; `options()` returns a
string and drops empty and unknown keys.

## Kiss options

| Option class | Getter |
| --- | --- |
| `Color\BackgroundOption` | `styles.background` |
| `Color\ColorOption` | `styles.color` |
| `Component\CallToAction\ShapeOption` | `styles.cta_shape` |
| `Component\CallToAction\VariantOption` | `styles.cta_type` |
| `Component\Media\LayoutOption` | `styles.media_layout` |
| `Component\Swiper\NavigationOption` | |
| `Layout\ColumnOption` | `styles.column` |
| `Layout\ColumnSpanOption` | `styles.span` |
| `Layout\ContainerOption` | `styles.container` |
| `Layout\CrossAlignmentOption` | `styles.crossAlignment` |
| `Layout\GapOption` | `styles.gap` |
| `Margin\BottomOption` | `styles.margin_bottom` |
| `Margin\TopOption` | `styles.margin_top` |
| `Modifier\SizeOption` | `styles.size` |
| `Modifier\VariantOption` | `styles.variant` |
| `Padding\BottomOption` | `styles.padding_bottom` |
| `Padding\TopOption` | `styles.padding_top` |
| `Typography\AlignmentOption` | `styles.text_alignment` |
| `Typography\HeadingOption` | `styles.heading` |
| `Typography\ResponsiveOption` | `styles.font_appearance` |

All live below `DigitaleDinge\ContaoKiss\Styles\Option`.

## Adding an option

A border radius picked per corner, as a checkbox group on the image element.

The enum holds the stored keys and the classes they emit:

```php
namespace App\Styles\Options\Shape;

enum BorderRadius: string implements TranslatableLabelInterface
{
    case rounded_top_left = 'rounded-tl-[50%]';
    case rounded_top_right = 'rounded-tr-[50%]';
    case rounded_bottom_right = 'rounded-br-[50%]';
    case rounded_bottom_left = 'rounded-bl-[50%]';

    public function label(): TranslatableMessage
    {
        return new TranslatableMessage('style_options.shape.border_radius.'.$this->name, [], 'style_options');
    }
}
```

The option class registers it. The name makes it callable as `styles.shape_radius()`:

```php
namespace App\Styles\Options\Shape;

#[AsKissStyleOption('shape_radius')]
final class BorderRadiusOption extends StyleOption
{
    public string $enumClass = BorderRadius::class;
}
```

Without a name, the option is registered under its class. Option classes in the
project's `src/` are picked up by the default service resource, and Tailwind's
source detection finds the classes in the enum.

The field, stored in `kiss_styles`:

```php
$GLOBALS['TL_DCA']['tl_content']['fields']['shapeBorderRadius'] = [
    'exclude' => true,
    'inputType' => 'checkbox',
    'targetColumn' => 'kiss_styles',
    'eval' => ['multiple' => true, 'tl_class' => 'clr'],
];

PaletteManipulator::create()
    ->addField('shapeBorderRadius', 'source_legend', PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('image', 'tl_content')
;
```

Its options, resolved through the registry so a replacement applies here too:

```php
final class ShapeOptionsListener
{
    use TranslatableEnumTrait;

    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly StyleOptionRegistry $registry,
    ) {
    }

    #[AsCallback('tl_content', 'fields.shapeBorderRadius.options')]
    public function __invoke(): array
    {
        return $this->getTranslatedOptions($this->registry->getEnum(BorderRadius::class));
    }
}
```

The labels, in `translations/style_options.{de,en}.yaml`:

```yaml
style_options:
    shape:
        border_radius:
            rounded_top_left: Rounded top left
            rounded_top_right: Rounded top right
            rounded_bottom_right: Rounded bottom right
            rounded_bottom_left: Rounded bottom left
```

And the template. The field holds several keys, so it uses `options()`:

```twig
{% extends '@Contao/content_element/image.html.twig' %}

{% set shape_border_radius = data.shapeBorderRadius ?? null %}

{% if shape_border_radius %}
    {% set img_attributes = attrs(img_attributes|default)
        .addClass(styles.options('shape_radius', shape_border_radius|deserialize))
    %}
{% endif %}
```

A field that holds a single key uses `styles.shape_radius(data.x)` instead.

## Replacing an option

Register another class under the kiss option class at a higher priority. It
replaces the option everywhere — templates, backend selects and RSCE fields —
and lookups by the kiss enum resolve to it too.

The replacing enum is always the complete set: an enum can't extend another. Its
case names are what content stores, so keep the ones you don't mean to drop. The
values are free.

```php
namespace App\Styles\Options\Layout;

use DigitaleDinge\ContaoKiss\Styles\Option\Layout\GapOption;

#[AsKissStyleOption(GapOption::class, priority: 10)]
final class AppGapOption extends StyleOption
{
    public string $enumClass = Gap::class;
}
```

Each variant below is the `Gap` enum this option points to. Reusing kiss's label
keys keeps its translations. Kiss's `Layout\Gap` is:

```php
case x_small = 'gap-2';
case small = 'gap-4';
case medium = 'gap-6';
case large = 'gap-10';
case x_large = 'gap-12';
case xx_large = 'gap-20';
```

### Changing every value

Same cases, new classes:

```php
namespace App\Styles\Options\Layout;

enum Gap: string implements TranslatableLabelInterface
{
    case x_small = 'gap-1';
    case small = 'gap-3';
    case medium = 'gap-5';
    case large = 'gap-8';
    case x_large = 'gap-16';
    case xx_large = 'gap-24';

    public function label(): TranslatableMessage
    {
        return new TranslatableMessage('style_options.layout.gap.'.$this->name, [], 'style_options');
    }
}
```

`styles.gap()`, the grid gap select and every template using them now emit the
new classes. Stored content keeps working, since the case names didn't change.

### Changing one value

List every case and change the one:

```php
case x_small = 'gap-2';
case small = 'gap-4';
case medium = 'gap-8';
case large = 'gap-10';
case x_large = 'gap-12';
case xx_large = 'gap-20';
```

### Adding a case

Kiss's cases plus the new one, and a label for it:

```php
case none = 'gap-0';
case x_small = 'gap-2';
case small = 'gap-4';
case medium = 'gap-6';
case large = 'gap-10';
case x_large = 'gap-12';
case xx_large = 'gap-20';
```

```yaml
style_options:
    layout:
        gap:
            none: No gap
```

The select lists cases in enum order.

### Dropping a case

Leave it out:

```php
case x_small = 'gap-2';
case small = 'gap-4';
case medium = 'gap-6';
case large = 'gap-10';
case x_large = 'gap-12';
```

The select no longer offers `xx_large`. Content that stored it renders no class
until it's re-saved with another value.

## Groups

Options in a group appear as optgroups in the same select. Kiss groups
`Typography\HeadingOption` and `Typography\ResponsiveOption` as `appearance`,
used by the headline and text appearance fields.

```php
#[AsKissStyleOption(groups: ['appearance'])]
```

A group is ordered by priority, then by name. The optgroup label is
`style_options.<option>`, derived from the class name (`HeadingOption` →
`style_options.heading`) or the last segment of a custom name, unless `label` is
given. To remove an option from a group, replace it with `groups: []`.

## Rules

The container build fails when:

- two classes register the same name with the same priority
- a registered class does not extend `StyleOption`
- `$enumClass` is not a backed enum
- a custom name collides with a `StylesVariable` method, such as `option`,
  `options` or `column`

An enum registered under more than one name can't be used for lookups; use the
option class or the name instead.
