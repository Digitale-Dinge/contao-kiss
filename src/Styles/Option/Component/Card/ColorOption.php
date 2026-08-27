<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Option\Component\Card;

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
 * @method string base_content
 */
class ColorOption extends StyleOption
{
    public string $enumClass = Color::class;
}
