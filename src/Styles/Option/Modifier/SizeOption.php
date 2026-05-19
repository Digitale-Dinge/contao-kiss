<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Option\Modifier;

use DigitaleDinge\ContaoKiss\Styles\Option\StyleOption;

/**
 * @method string x_small
 * @method string small
 * @method string large
 * @method string x_large
 */
class SizeOption extends StyleOption
{
    public string $enumClass = Size::class;
}
