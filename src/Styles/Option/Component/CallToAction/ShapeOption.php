<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Option\Component\CallToAction;

use DigitaleDinge\ContaoKiss\DependencyInjection\Attribute\AsKissStyleOption;
use DigitaleDinge\ContaoKiss\Styles\Option\StyleOption;

/**
 * @method string wide
 * @method string block
 */
#[AsKissStyleOption]
class ShapeOption extends StyleOption
{
    public string $enumClass = Shape::class;
}
