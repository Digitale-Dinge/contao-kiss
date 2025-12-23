<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Options\Margin;

use DigitaleDinge\ContaoKiss\Styles\ClassOptionsInterface;

enum Bottom: string implements ClassOptionsInterface
{
    case half = 'mb-line-1/2';
    case one = 'mb-line-1';
    case two = 'mb-line-2';
    case three = 'mb-line-3';
    case four = 'mb-line-4';
    case five = 'mb-line-5';
}
