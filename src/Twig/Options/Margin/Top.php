<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Twig\Options\Margin;

use DigitaleDinge\ContaoKiss\Twig\Options\ClassOptionsInterface;

enum Top: string implements ClassOptionsInterface
{
    case HALF = 'mt-line-1/2';
    case ONE = 'mt-line-1';
    case TWO = 'mt-line-2';
    case THREE = 'mt-line-3';
    case FOUR = 'mt-line-4';
    case FIVE = 'mt-line-5';
}
