<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Options\Padding;

use DigitaleDinge\ContaoKiss\Styles\ClassOptionsInterface;

enum Bottom: string implements ClassOptionsInterface
{
    case half = 'pb-line-1/2';
    case one = 'pb-line-1';
    case two = 'pb-line-2';
    case three = 'pb-line-3';
    case four = 'pb-line-4';
    case five = 'pb-line-5';
}
