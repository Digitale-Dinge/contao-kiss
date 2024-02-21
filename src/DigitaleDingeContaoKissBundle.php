<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use function dirname;


class DigitaleDingeContaoKissBundle extends Bundle
{

    public function getPath(): string
    {
        return dirname(__DIR__);
    }

}
