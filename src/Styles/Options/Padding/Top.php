<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Options\Padding;

use DigitaleDinge\ContaoKiss\Styles\ClassOptionsInterface;

enum Top: string implements ClassOptionsInterface
{
    case HALF = 'pt-line-1/2';
    case ONE = 'pt-line-1';
    case TWO = 'pt-line-2';
    case THREE = 'pt-line-3';
    case FOUR = 'pt-line-4';
    case FIVE = 'pt-line-5';
}
