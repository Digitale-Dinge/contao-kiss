<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Option\Typography;

use DigitaleDinge\ContaoKiss\Styles\Option\StyleOption;;

/**
 * @method string start
 * @method string center
 * @method string end
 */
class AlignmentOption extends StyleOption
{
    public string $enumClass = Alignment::class;
}
