<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Options\Color;

use DigitaleDinge\ContaoKiss\Styles\ClassOptionsInterface;

enum Background: string implements ClassOptionsInterface
{
    case transparent = 'transparent';
    case white = 'background-white';
    case primary = 'background-primary';
    case secondary = 'background-secondary';
    case additional_one = 'background-additional-1';
    case additional_two = 'background-additional-2';
}
