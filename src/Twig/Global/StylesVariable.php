<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Twig\Global;

use DigitaleDinge\ContaoKiss\Styles\Option\Color;
use DigitaleDinge\ContaoKiss\Styles\Option\Component;
use DigitaleDinge\ContaoKiss\Styles\Option\Layout;
use DigitaleDinge\ContaoKiss\Styles\Option\Margin;
use DigitaleDinge\ContaoKiss\Styles\Option\Modifier;
use DigitaleDinge\ContaoKiss\Styles\Option\Padding;
use DigitaleDinge\ContaoKiss\Styles\Option\StyleOption;
use DigitaleDinge\ContaoKiss\Styles\Option\Typography;
use DigitaleDinge\ContaoKiss\Styles\StyleOptionRegistry;

/**
 * @experimental
 */
class StylesVariable
{
    public function __construct(private readonly StyleOptionRegistry $registry)
    {
    }

    /**
     * Make registered names without dots callable as e.g. styles.name(key).
     */
    public function __call(string $name, array $arguments): StyleOption
    {
        return $this->option($name, $arguments[0] ?? null);
    }

    /**
     * Resolve registered style option by the option class, enum class or whatever.
     */
    public function option(string $option, string|null $key = null): StyleOption
    {
        return $this->registry->create($option, $key);
    }

    /**
     * Resolves multiple keys of a style option and return them as classes.
     */
    public function options(string $option, array $keys): string
    {
        return implode(' ', array_filter(array_map(
            fn (string|null $key): string => (string) $this->option($option, $key),
            $keys,
        )));
    }

    /**
     * Layout.
     */
    public function getContainer(string|null $key = null): Layout\ContainerOption|StyleOption
    {
        return $this->option(Layout\ContainerOption::class, $key);
    }

    public function getColumn(string|null $key = null): Layout\ColumnOption|StyleOption
    {
        return $this->option(Layout\ColumnOption::class, $key);
    }

    public function getGap(string|null $key = null): Layout\GapOption|StyleOption
    {
        return $this->option(Layout\GapOption::class, $key);
    }

    public function getSpan(string|null $key = null): Layout\ColumnSpanOption|StyleOption
    {
        return $this->option(Layout\ColumnSpanOption::class, $key);
    }

    public function getCrossAlignment(string|null $key = null): Layout\CrossAlignmentOption|StyleOption
    {
        return $this->option(Layout\CrossAlignmentOption::class, $key);
    }

    /**
     * Typography.
     */
    public function getHeading(string|null $key = null): StyleOption|Typography\HeadingOption
    {
        return $this->option(Typography\HeadingOption::class, $key);
    }

    public function getFont_appearance(string|null $key = null): StyleOption|Typography\ResponsiveOption
    {
        return $this->option(Typography\ResponsiveOption::class, $key);
    }

    public function getText_alignment(string|null $key = null): StyleOption|Typography\AlignmentOption
    {
        return $this->option(Typography\AlignmentOption::class, $key);
    }

    /**
     * Color.
     */
    public function getBackground(string|null $key = null): Color\BackgroundOption|StyleOption
    {
        return $this->option(Color\BackgroundOption::class, $key);
    }

    public function getColor(string|null $key = null): Color\ColorOption|StyleOption
    {
        return $this->option(Color\ColorOption::class, $key);
    }

    /**
     * Margin.
     */
    public function getMargin_top(string|null $key = null): Margin\TopOption|StyleOption
    {
        return $this->option(Margin\TopOption::class, $key);
    }

    public function getMargin_bottom(string|null $key = null): Margin\BottomOption|StyleOption
    {
        return $this->option(Margin\BottomOption::class, $key);
    }

    /**
     * Padding.
     */
    public function getPadding_top(string|null $key = null): Padding\TopOption|StyleOption
    {
        return $this->option(Padding\TopOption::class, $key);
    }

    public function getPadding_bottom(string|null $key = null): Padding\BottomOption|StyleOption
    {
        return $this->option(Padding\BottomOption::class, $key);
    }

    /**
     * Modifiers.
     */
    public function getSize(string|null $key = null): Modifier\SizeOption|StyleOption
    {
        return $this->option(Modifier\SizeOption::class, $key);
    }

    public function getVariant(string|null $key = null): Modifier\VariantOption|StyleOption
    {
        return $this->option(Modifier\VariantOption::class, $key);
    }

    /**
     * Call to action design.
     */
    public function getCta_shape(string|null $key = null): Component\CallToAction\ShapeOption|StyleOption
    {
        return $this->option(Component\CallToAction\ShapeOption::class, $key);
    }

    /**
     * ToDo: Might use getVariant instead.
     */
    public function getCta_type(string|null $key = null): Component\CallToAction\VariantOption|StyleOption
    {
        return $this->option(Component\CallToAction\VariantOption::class, $key);
    }

    public function getMedia_layout(string|null $key = null): Component\Media\LayoutOption|StyleOption
    {
        return $this->option(Component\Media\LayoutOption::class, $key);
    }
}
