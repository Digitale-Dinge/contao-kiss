<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Options\Color;

use DigitaleDinge\ContaoKiss\Styles\ClassOptionsInterface;

enum Background: string implements ClassOptionsInterface
{
    case TRANSPARENT = 'transparent';
    case WHITE = 'background-white';
    case PRIMARY = 'background-primary';
    case SECONDARY = 'background-secondary';
    case ADDITIONAL_ONE = 'background-additional-1';
    case ADDITIONAL_TWO = 'background-additional-2';
}
