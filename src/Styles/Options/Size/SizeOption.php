<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Options\Size;

use DigitaleDinge\ContaoKiss\Styles\StyleOption;

/**
 * @method string x_small
 * @method string small
 * @method string large
 * @method string x_large
 */
final class SizeOption extends StyleOption
{
    public string $enumClass = Size::class;
}
