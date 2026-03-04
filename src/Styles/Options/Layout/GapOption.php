<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Options\Layout;

use DigitaleDinge\ContaoKiss\Styles\StyleOption;

/**
 * @method string half
 * @method string one
 * @method string two
 * @method string three
 * @method string four
 * @method string five
 */
final class GapOption extends StyleOption
{
    protected string $enumClass = Gap::class;
}
