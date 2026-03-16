<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Twig\Extension;

use DigitaleDinge\ContaoKiss\Twig\Global\StylesVariable;
use DigitaleDinge\ContaoKiss\Twig\Runtime\BackendStylesRuntime;
use DigitaleDinge\ContaoKiss\Twig\Runtime\FileItemRuntime;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFilter;
use Twig\TwigFunction;

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

    #[\Override]
    public function getFunctions(): array
    {
        return [
            new TwigFunction('getGridClasses',
                [BackendStylesRuntime::class, 'getGridClasses'],
            ),
            new TwigFunction('getGridLabel',
                [BackendStylesRuntime::class, 'getGridLabel'],
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
