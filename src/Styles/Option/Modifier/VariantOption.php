<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Option\Modifier;

use DigitaleDinge\ContaoKiss\DependencyInjection\Attribute\AsKissStyleOption;
use DigitaleDinge\ContaoKiss\Styles\Option\StyleOption;

/**
 * @method string soft
 * @method string outline
 * @method string glass
 */
#[AsKissStyleOption]
class VariantOption extends StyleOption
{
    public string $enumClass = Variant::class;
}
