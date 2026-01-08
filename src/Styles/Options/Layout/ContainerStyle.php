<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Options\Layout;

use DigitaleDinge\ContaoKiss\Styles\StyleOption;

/**
 * @method string small
 * @method string base
 * @method string narrow
 * @method string full_pad
 * @method string full
 */
class ContainerStyle extends StyleOption
{
    protected string $default = Container::base->name;

    protected string $enumClass = Container::class;
}
