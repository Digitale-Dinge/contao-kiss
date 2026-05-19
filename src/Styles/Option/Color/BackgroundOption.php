<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Option\Color;

use DigitaleDinge\ContaoKiss\Styles\Option\StyleOption;

/**
 * @method string transparent
 * @method string base_100
 * @method string base_200
 * @method string base_300
 * @method string primary
 * @method string secondary
 * @method string accent
 * @method string info
 * @method string success
 * @method string warning
 * @method string error
 */
class BackgroundOption extends StyleOption
{
    public string $enumClass = Background::class;
}
