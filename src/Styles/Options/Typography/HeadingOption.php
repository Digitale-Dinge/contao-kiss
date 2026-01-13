<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Options\Typography;

use DigitaleDinge\ContaoKiss\Styles\StyleOption;

/**
 * @method string h1
 * @method string h2
 * @method string h3
 * @method string h4
 * @method string h5
 * @method string h6
 */
final class HeadingOption extends StyleOption
{
    public string $enumClass = Heading::class;
}
