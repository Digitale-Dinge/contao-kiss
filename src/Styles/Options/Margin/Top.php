<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Options\Margin;

use DigitaleDinge\ContaoKiss\Styles\ClassOptionsInterface;

enum Top: string implements ClassOptionsInterface
{
    case half = 'mt-line-1/2';
    case one = 'mt-line-1';
    case two = 'mt-line-2';
    case three = 'mt-line-3';
    case four = 'mt-line-4';
    case five = 'mt-line-5';
}
