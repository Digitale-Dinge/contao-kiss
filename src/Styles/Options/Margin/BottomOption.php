<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Options\Margin;

use DigitaleDinge\ContaoKiss\Styles\StyleOption;

/**
 * @method string half
 * @method string one
 * @method string two
 * @method string three
 * @method string four
 * @method string five
 * @method string six
 */
final class BottomOption extends StyleOption
{
    protected string $enumClass = Bottom::class;
}
