<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Options\Layout;

use DigitaleDinge\ContaoKiss\Styles\ClassOptionsInterface;

enum Container: string implements ClassOptionsInterface
{
    case small = 'container-small';
    case base = 'container-base';
    case narrow = 'container-narrow';
    case full_pad = 'container-full-pad';
    case full = 'container-full';
}
