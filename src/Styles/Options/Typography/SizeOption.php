<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Options\Typography;

use DigitaleDinge\ContaoKiss\Styles\StyleOption;

/**
 * @method string x_small
 * @method string small
 * @method string medium
 * @method string large
 * @method string x_large
 * @method string xx_large
 * @method string xxx_large
 */
final class SizeOption extends StyleOption
{
    public string $enumClass = Size::class;
}
