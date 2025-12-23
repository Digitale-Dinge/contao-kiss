<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Options\Padding;

use DigitaleDinge\ContaoKiss\Styles\ClassOptionsInterface;

enum Top: string implements ClassOptionsInterface
{
    case half = 'pt-line-1/2';
    case one = 'pt-line-1';
    case two = 'pt-line-2';
    case three = 'pt-line-3';
    case four = 'pt-line-4';
    case five = 'pt-line-5';
}
