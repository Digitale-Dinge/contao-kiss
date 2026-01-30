<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Twig\Extension;

use DigitaleDinge\ContaoKiss\Twig\Global\StylesVariable;
use DigitaleDinge\ContaoKiss\Twig\Runtime\FileItemRuntime;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFilter;

class ContaoKissExtension extends AbstractExtension implements GlobalsInterface
{
    public function __construct(
        private readonly StylesVariable $stylesVariable,
    ) {
    }

    #[\Override]
    public function getFilters(): array
    {
        return [
            new TwigFilter('file',
                [FileItemRuntime::class, 'getFile'],
            ),
        ];
    }

    public function getGlobals(): array
    {
        return [
            'styles' => $this->stylesVariable,
        ];
    }
}
