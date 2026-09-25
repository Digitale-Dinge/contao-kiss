<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Option\Modifier;

use DigitaleDinge\ContaoKiss\DependencyInjection\Attribute\AsKissStyleOption;
use DigitaleDinge\ContaoKiss\Styles\Option\StyleOption;

/**
 * @method string small
 * @method string large
 */
#[AsKissStyleOption]
class SizeOption extends StyleOption
{
    public string $enumClass = Size::class;
}
