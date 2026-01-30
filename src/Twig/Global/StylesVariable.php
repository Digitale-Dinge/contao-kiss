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
    public function getContainer(string|null $key = null): Layout\ContainerOption
    {
        return new Layout\ContainerOption($key);
    }

    public function getColumn(string $key): Layout\ColumnOption
    {
        return new Layout\ColumnOption($key);
    }

    public function getSize(string $key): Typography\SizeOption
    {
        return new Typography\SizeOption($key);
    }

    public function getHeading(string $key): Typography\HeadingOption
    {
        return new Typography\HeadingOption($key);
    }

    public function getBackground(string $key): Color\BackgroundOption
    {
        return new Color\BackgroundOption($key);
    }

    public function getMargin_top(string $key): Margin\TopOption
    {
        return new Margin\TopOption($key);
    }

    public function getMargin_bottom(string $key): Margin\BottomOption
    {
        return new Margin\BottomOption($key);
    }

    public function getPadding_top(string $key): Padding\TopOption
    {
        return new Padding\TopOption($key);
    }

    public function getPadding_bottom(string $key): Padding\BottomOption
    {
        return new Padding\BottomOption($key);
    }
}
