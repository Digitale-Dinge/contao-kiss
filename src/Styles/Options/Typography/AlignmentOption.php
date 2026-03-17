<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Options\Typography;

use DigitaleDinge\ContaoKiss\Styles\StyleOption;

/**
 * @method string start
 * @method string center
 * @method string end
 */
final class AlignmentOption extends StyleOption
{
    public string $enumClass = Alignment::class;
}
