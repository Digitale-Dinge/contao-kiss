<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Twig\Global;

use DigitaleDinge\ContaoKiss\Styles\Options\Color;
use DigitaleDinge\ContaoKiss\Styles\Options\Component;
use DigitaleDinge\ContaoKiss\Styles\Options\Layout;
use DigitaleDinge\ContaoKiss\Styles\Options\Margin;
use DigitaleDinge\ContaoKiss\Styles\Options\Padding;
use DigitaleDinge\ContaoKiss\Styles\Options\Size;
use DigitaleDinge\ContaoKiss\Styles\Options\Typography;

class StylesVariable
{
    /**
     * Layout
     */
    public function getContainer(string|null $key = null): Layout\ContainerOption
    {
        return new Layout\ContainerOption($key);
    }

    public function getColumn(string $key): Layout\ColumnOption
    {
        return new Layout\ColumnOption($key);
    }

    public function getGap(string $key): Layout\GapOption
    {
        return new Layout\GapOption($key);
    }

    /**
     * Typography
     */
    public function getFont_size(string $key): Typography\FontSizeOption
    {
        return new Typography\FontSizeOption($key);
    }

    public function getHeading(string $key): Typography\HeadingOption
    {
        return new Typography\HeadingOption($key);
    }

    public function getText_alignment(string $key): Typography\AlignmentOption
    {
        return new Typography\AlignmentOption($key);
    }

    /**
     * Color
     */
    public function getBackground(string $key): Color\BackgroundOption
    {
        return new Color\BackgroundOption($key);
    }

    public function getColor(string $key): Color\ColorOption
    {
        return new Color\ColorOption($key);
    }

    /**
     * Margin
     */
    public function getMargin_top(string $key): Margin\TopOption
    {
        return new Margin\TopOption($key);
    }

    public function getMargin_bottom(string $key): Margin\BottomOption
    {
        return new Margin\BottomOption($key);
    }

    /**
     * Padding
     */
    public function getPadding_top(string $key): Padding\TopOption
    {
        return new Padding\TopOption($key);
    }

    public function getPadding_bottom(string $key): Padding\BottomOption
    {
        return new Padding\BottomOption($key);
    }

    public function getSize(string $key): Size\SizeOption
    {
        return new Size\SizeOption($key);
    }

    /**
     * Call to action design
     */
    public function getCta_shape(string $key): Component\CallToAction\ShapeOption
    {
        return new Component\CallToAction\ShapeOption($key);
    }

    public function getCta_type(string $key): Component\CallToAction\TypeOption
    {
        return new Component\CallToAction\TypeOption($key);
    }
}
