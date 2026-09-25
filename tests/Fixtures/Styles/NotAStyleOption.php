<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Tests\Fixtures\Styles;

final readonly class NotAStyleOption
{
    public function __construct(
        public string|null $key = null,
    ) {
    }
}
