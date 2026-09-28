<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Twig\Runtime;

use DigitaleDinge\ContaoKiss\EventListener\IncludeStylesDataListener;
use Twig\Extension\RuntimeExtensionInterface;

final readonly class IncludeStylesDataRuntime implements RuntimeExtensionInterface
{
    public function __construct(
        private IncludeStylesDataListener $listener,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function getData(): array
    {
        return $this->listener->getCurrentData();
    }
}
