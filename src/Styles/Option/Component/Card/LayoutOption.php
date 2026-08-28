<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Option\Component\Card;

use DigitaleDinge\ContaoKiss\Styles\Option\StyleOption;

/**
 * @method string reverse
 * @method string side
 * @method string side_reverse
 * @method string media_full
 */
class LayoutOption extends StyleOption
{
    public string $enumClass = Layout::class;
}
