<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Option\Component\Swiper;

use DigitaleDinge\ContaoKiss\DependencyInjection\Attribute\AsKissStyleOption;
use DigitaleDinge\ContaoKiss\Styles\Option\StyleOption;

/**
 * @method string overlay
 * @method string outside
 */
#[AsKissStyleOption]
class NavigationOption extends StyleOption
{
    public string $enumClass = Navigation::class;
}
