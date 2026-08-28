<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Option\Color;

use DigitaleDinge\ContaoKiss\Styles\Option\StyleOption;

/**
 * @method string transparent
 * @method string neutral_one
 * @method string neutral_two
 * @method string neutral_three
 * @method string primary
 * @method string secondary
 * @method string tertiary
 * @method string success
 * @method string warning
 * @method string error
 */
class BackgroundOption extends StyleOption
{
    public string $enumClass = Background::class;
}
