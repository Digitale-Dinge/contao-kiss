<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Option\Component\Media;

use DigitaleDinge\ContaoKiss\DependencyInjection\Attribute\AsKissStyleOption;
use DigitaleDinge\ContaoKiss\Styles\Option\StyleOption;

/**
 * @method string reverse
 * @method string side
 * @method string side_reverse
 * @method string media_background
 */
#[AsKissStyleOption]
class LayoutOption extends StyleOption
{
    public string $enumClass = Layout::class;
}
