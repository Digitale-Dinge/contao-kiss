<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Twig\Global;

use DigitaleDinge\ContaoKiss\Styles\Options\Color\BackgroundOption;
use DigitaleDinge\ContaoKiss\Styles\Options\Layout\ColumnOption;
use DigitaleDinge\ContaoKiss\Styles\Options\Layout\ContainerOption;
use DigitaleDinge\ContaoKiss\Styles\Options\Margin\BottomOption;
use DigitaleDinge\ContaoKiss\Styles\Options\Margin\TopOption;
use DigitaleDinge\ContaoKiss\Styles\Options\Padding;
use DigitaleDinge\ContaoKiss\Styles\Options\Typography\HeadingOption;
use DigitaleDinge\ContaoKiss\Styles\Options\Typography\SizeOption;

class StylesVariable
{
    public function getContainer(string|null $key = null): ContainerOption
    {
        return new ContainerOption($key);
    }

    public function getColumn(string $key): ColumnOption
    {
        return new ColumnOption($key);
    }

    public function getSize(string $key): SizeOption
    {
        return new SizeOption($key);
    }

    public function getHeading(string $key): HeadingOption
    {
        return new HeadingOption($key);
    }

    public function getBackground(string $key): BackgroundOption
    {
        return new BackgroundOption($key);
    }

    public function getMargin_top(string $key): TopOption
    {
        return new TopOption($key);
    }

    public function getMargin_bottom(string $key): BottomOption
    {
        return new BottomOption($key);
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
