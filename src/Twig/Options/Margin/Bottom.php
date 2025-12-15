<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Twig\Options\Margin;

use DigitaleDinge\ContaoKiss\Twig\Options\ClassOptionsInterface;

enum Bottom: string implements ClassOptionsInterface
{
    case HALF = 'mb-line-1/2';
    case ONE = 'mb-line-1';
    case TWO = 'mb-line-2';
    case THREE = 'mb-line-3';
    case FOUR = 'mb-line-4';
    case FIVE = 'mb-line-5';
}
