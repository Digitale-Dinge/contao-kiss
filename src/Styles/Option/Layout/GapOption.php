<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Option\Layout;

use DigitaleDinge\ContaoKiss\Styles\Option\StyleOption;

/**
 * @method string half
 * @method string one
 * @method string two
 * @method string three
 * @method string four
 * @method string five
 */
class GapOption extends StyleOption
{
    protected string $enumClass = Gap::class;
}
