<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Twig\Global;

use DigitaleDinge\ContaoKiss\Styles\Options\Color;
use DigitaleDinge\ContaoKiss\Styles\Options\Layout;
use DigitaleDinge\ContaoKiss\Styles\Options\Margin;
use DigitaleDinge\ContaoKiss\Styles\Options\Padding;
use DigitaleDinge\ContaoKiss\Styles\Options\Typography;

class StylesVariable
{
    public function getContainer(string|null $key = null): Layout\ContainerStyle
    {
        return new Layout\ContainerStyle($key);
    }

    public function getColumn(string $key): Layout\ColumnStyle
    {
        return new Layout\ColumnStyle($key);
    }

    public function getSize(string $key): Typography\SizeStyle
    {
        return new Typography\SizeStyle($key);
    }

    public function getBackground(string $key): Color\BackgroundStyle
    {
        return new Color\BackgroundStyle($key);
    }

    public function getMargin_top(string $key): Margin\TopStyle
    {
        return new Margin\TopStyle($key);
    }

    public function getMargin_bottom(string $key): Margin\BottomStyle
    {
        return new Margin\BottomStyle($key);
    }

    public function getPadding_top(string $key): Padding\TopStyle
    {
        return new Padding\TopStyle($key);
    }

    public function getPadding_bottom(string $key): Padding\BottomStyle
    {
        return new Padding\BottomStyle($key);
    }
}
