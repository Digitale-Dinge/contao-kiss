<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Option\Typography;

use DigitaleDinge\ContaoKiss\Styles\Option\StyleOption;

/**
 * @deprecated No longer offered in any backend dropdown.
 *
 * @method string x_small
 * @method string small
 * @method string medium
 * @method string large
 * @method string x_large
 * @method string xx_large
 * @method string xxx_large
 */
class FontSizeOption extends StyleOption
{
    public string $enumClass = FontSize::class;

    public function __toString(): string
    {
        $value = parent::__toString();

        if ('' !== $value) {
            trigger_deprecation('digitaledinge/contao-kiss', '1.0', 'Font sizes are no longer used since the design system was introduced. Use "Responsive" instead.');
        }

        return $value;
    }
}
