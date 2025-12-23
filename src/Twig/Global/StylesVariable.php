<?php

namespace DigitaleDinge\ContaoKiss\Twig\Global;

use DigitaleDinge\ContaoKiss\Styles\ClassOptionsInterface;
use DigitaleDinge\ContaoKiss\Styles\Options\Color;
use DigitaleDinge\ContaoKiss\Styles\Options\Layout;
use DigitaleDinge\ContaoKiss\Styles\Options\Margin;
use DigitaleDinge\ContaoKiss\Styles\Options\Padding;

class StylesVariable
{
    public function getContainer(string $key = 'base'): string|null
    {
        return $this->getClassOptionValue(Layout\Container::class, $key);
    }

    public function getColumn(string $key): string|null
    {
        return $this->getClassOptionValue(Layout\Column::class, $key);
    }

    public function getBackground(string $key): string|null
    {
        return $this->getClassOptionValue(Color\Background::class, $key);
    }

    public function getMargin_top(string $key): string|null
    {
        return $this->getClassOptionValue(Margin\Top::class, $key);
    }

    public function getMargin_bottom(string $key): string|null
    {
        return $this->getClassOptionValue(Margin\Bottom::class, $key);
    }

    public function getPadding_top(string $key): string|null
    {
        return $this->getClassOptionValue(Padding\Top::class, $key);
    }

    public function getPadding_bottom(string $key): string|null
    {
        return $this->getClassOptionValue(Padding\Bottom::class, $key);
    }

    private function getClassOptionValue(string $class, string $key): string|null
    {
        if (!is_a($class, ClassOptionsInterface::class, \true)) {
            return null;
        }

        try {
            $value = $class::{$key}?->value;
        } catch (\Error) {
            $value = null;
        }

        return $value;
    }
}
