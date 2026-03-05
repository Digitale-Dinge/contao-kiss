<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Options\Component\CallToAction;

use DigitaleDinge\ContaoKiss\Styles\StyleOption;

/**
 * @method string wide
 * @method string block
 */
final class ShapeOption extends StyleOption
{
    public string $enumClass = Shape::class;
}
