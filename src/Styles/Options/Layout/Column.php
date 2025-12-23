<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Options\Layout;

use DigitaleDinge\ContaoKiss\Styles\ClassOptionsInterface;

enum Column: string implements ClassOptionsInterface
{
    case one = 'cols_1';
    case two = 'cols_2';
    case three = 'cols_3';
    case four = 'cols_4';
    case five = 'cols_5';
    case six = 'cols_6';
    case seven = 'cols_7';
    case eight = 'cols_8';
    case nine = 'cols_9';
    case ten = 'cols_10';
    case eleven = 'cols_11';
    case twelve = 'cols_12';
}
