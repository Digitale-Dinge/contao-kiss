<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Option\Component\CallToAction;

use DigitaleDinge\ContaoKiss\Styles\Option\StyleOption;

/**
 * @method string soft
 * @method string outline
 * @method string text
 */
class VariantOption extends StyleOption
{
    public string $enumClass = Variant::class;
}
