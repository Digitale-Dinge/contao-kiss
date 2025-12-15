<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Twig\Options\Padding;

use DigitaleDinge\ContaoKiss\Twig\Options\ClassOptionsInterface;

enum Bottom: string implements ClassOptionsInterface
{
    case HALF = 'pb-line-1/2';
    case ONE = 'pb-line-1';
    case TWO = 'pb-line-2';
    case THREE = 'pb-line-3';
    case FOUR = 'pb-line-4';
    case FIVE = 'pb-line-5';
}
