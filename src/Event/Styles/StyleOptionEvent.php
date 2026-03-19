<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Event\Styles;

use DigitaleDinge\ContaoKiss\Styles\Option\StyleOption;
use Symfony\Contracts\EventDispatcher\Event;

class StyleOptionEvent extends Event
{
    private array $options = [];

    public function __construct(
        private string $optionClass,
        private readonly string|null $key,
    ) {
    }

    public function getKey(): string|null
    {
        return $this->key;
    }

    public function getOptionClass(): string
    {
        return $this->optionClass;
    }

    public function setOptionClass(string $class): void
    {
        $this->optionClass = $class;
    }

    public function getOption(): StyleOption
    {
        try {
            return (new $this->optionClass($this->key));
        } catch (\Throwable) {
            throw new \LogicException(\sprintf('The style option class %s does not exist: ', $this->optionClass));
        }
    }
}
