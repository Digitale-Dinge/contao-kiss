<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Event;

use Symfony\Contracts\EventDispatcher\Event;

class ExcludeToplineEvent extends Event
{
    private array $defaults = [
        '__selector__',
        'markdown',
        'template',
        'form',
    ];

    public function __construct(
        public array $types = [],
    ) {
    }

    public function setTypes(array $types): void
    {
        $this->types = $types;
    }

    public function getTypes(): array
    {
        return [
            ...$this->defaults,
            ...$this->types
        ];
    }
}
