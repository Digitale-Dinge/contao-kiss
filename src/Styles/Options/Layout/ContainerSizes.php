<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Options\Layout;

use DigitaleDinge\ContaoKiss\Styles\ClassOptionsInterface;

enum ContainerSizes: string implements ClassOptionsInterface
{
    case SMALL = 'container-small';
    case BASE = 'container-base';
    case NARROW = 'container-narrow';
    case FULL_PAD = 'container-full-pad';
    case FULL = 'container-full';
}
