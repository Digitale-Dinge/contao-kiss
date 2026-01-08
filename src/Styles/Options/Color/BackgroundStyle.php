<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Options\Color;

use DigitaleDinge\ContaoKiss\Styles\StyleOption;

/**
 * @method string transparent
 * @method string white
 * @method string primary
 * @method string secondary
 * @method string additional_one
 * @method string additional_two
 */
class BackgroundStyle extends StyleOption
{
    public string $enumClass = Background::class;
}
