<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Twig\Options;

enum Columns: string implements ClassOptionsInterface
{
    case TWO = 'cols_2';
    case THREE = 'cols_3';
    case FOUR = 'cols_4';
    case FIVE = 'cols_5';
    case SIX = 'cols_6';
    case SEVEN = 'cols_7';
    case EIGHT = 'cols_8';
    case NINE = 'cols_9';
    case TEN = 'cols_10';
    case ELEVEN = 'cols_11';
    case TWELVE = 'cols_12';
}
