<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Options\Padding;

use DigitaleDinge\ContaoKiss\Styles\StyleOption;

/**
 * @method string half
 * @method string one
 * @method string two
 * @method string three
 * @method string four
 * @method string five
 */
final class TopOption extends StyleOption
{
    protected string $enumClass = Top::class;
}
