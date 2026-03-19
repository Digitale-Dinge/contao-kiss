<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Option\Typography;

use DigitaleDinge\ContaoKiss\Styles\Option\StyleOption;;

/**
 * @method string x_small
 * @method string small
 * @method string medium
 * @method string large
 * @method string x_large
 * @method string xx_large
 * @method string xxx_large
 */
class FontSizeOption extends StyleOption
{
    public string $enumClass = FontSize::class;
}
