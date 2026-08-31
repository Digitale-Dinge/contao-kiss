<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Option\Typography;

use DigitaleDinge\ContaoKiss\Styles\Option\StyleOption;

/**
 * @method string display_one
 * @method string display_two
 * @method string display_three
 * @method string headline_one
 * @method string headline_two
 * @method string headline_three
 * @method string body_one
 * @method string body_two
 * @method string body_three
 */
class ResponsiveOption extends StyleOption
{
    public string $enumClass = Responsive::class;
}
