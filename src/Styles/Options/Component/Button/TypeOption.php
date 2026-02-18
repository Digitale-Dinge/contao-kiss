<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Options\Component\Button;

use DigitaleDinge\ContaoKiss\Styles\StyleOption;

/**
 * @method string solid
 * @method string soft
 * @method string outline
 * @method string text
 */
final class TypeOption extends StyleOption
{
    public string $enumClass = Type::class;
}
