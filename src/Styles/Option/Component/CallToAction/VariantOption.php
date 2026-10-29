<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Option\Component\CallToAction;

use DigitaleDinge\ContaoKiss\DependencyInjection\Attribute\AsKissStyleOption;
use DigitaleDinge\ContaoKiss\Styles\Option\StyleOption;

/**
 * @method string muted()
 * @method string outline()
 * @method string text()
 * @method string none()
 */
#[AsKissStyleOption]
class VariantOption extends StyleOption
{
    public string $enumClass = Variant::class;
}
