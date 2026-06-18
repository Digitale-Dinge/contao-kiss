<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Option\Layout;

use DigitaleDinge\ContaoKiss\Styles\Option\StyleOption;

/**
 * @method string start
 * @method string center
 * @method string end
 */
class CrossAlignmentOption extends StyleOption
{
    protected string $enumClass = CrossAlignment::class;
}
