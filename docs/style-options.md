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

An enum, an option class, and the attribute:

```php
enum BorderRadius: string implements TranslatableLabelInterface
{
    case top_left = 'rounded-tl-[50%]';
    case top_right = 'rounded-tr-[50%]';

    public function label(): TranslatableMessage
    {
        return new TranslatableMessage('style_options.shape.border_radius.'.$this->name, [], 'style_options');
    }
}
```

```php
#[AsKissStyleOption('shape_radius')]
final class BorderRadiusOption extends StyleOption
{
    public string $enumClass = BorderRadius::class;
}
```

Without a name, the option is registered under its class. Give it a name without
dots to call it as `styles.<name>()` in Twig. Option classes in the project's
`src/` are picked up by the default service resource.

## Replacing an option

Register another class under the kiss option class at a higher priority. It
replaces the option everywhere — templates, backend selects and RSCE fields:

```php
#[AsKissStyleOption(ColumnOption::class, priority: 10)]
final class AppColumnOption extends StyleOption
{
    public string $enumClass = Columns::class;
}
```

Lookups by the kiss enum resolve to the replacement too. To add or remove cases,
give the replacing enum the full set it should have.

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
