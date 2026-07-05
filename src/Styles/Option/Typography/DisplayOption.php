<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Option\Typography;

use DigitaleDinge\ContaoKiss\Styles\Option\StyleOption;

/**
 * @method string display_one
 * @method string display_two
 * @method string display_three
 */
class DisplayOption extends StyleOption
{
    public string $enumClass = Display::class;
}
