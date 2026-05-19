<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Option\Layout;

use DigitaleDinge\ContaoKiss\Styles\Option\StyleOption;

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
class ColumnOption extends StyleOption
{
    protected string $enumClass = Column::class;
}
