<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Options\Layout;

use DigitaleDinge\ContaoKiss\Styles\StyleOption;

/**
 * @method string one
 * @method string two
 * @method string three
 * @method string four
 * @method string five
 * @method string six
 * @method string seven
 * @method string eight
 * @method string nine
 * @method string ten
 * @method string eleven
 * @method string twelve
 */
class ColumnStyle extends StyleOption
{
    protected string $enumClass = Column::class;
}
