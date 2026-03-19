<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Option\Typography;

use DigitaleDinge\ContaoKiss\Styles\Option\StyleOption;;

/**
 * @method string h1
 * @method string h2
 * @method string h3
 * @method string h4
 * @method string h5
 * @method string h6
 */
class HeadingOption extends StyleOption
{
    public string $enumClass = Heading::class;
}
