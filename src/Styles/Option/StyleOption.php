<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Option;

abstract class StyleOption implements \Stringable
{
    protected string $default = '';

    /**
     * @return class-string<\BackedEnum>
     */
    protected string $enumClass;

    public function __construct(
        private readonly string|null $key = null,
    ) {
    }

    public function __toString(): string
    {
        try {
            return (string) $this->enumClass::{$this->key ?? $this->default}?->value;
        }
        catch (\Throwable) {
            return '';
        }
    }

    public function __call(string $name, array $arguments): string|null
    {
        try {
            return $this->enumClass::{$name}?->value;
        }
        catch (\Throwable) {
            return null;
        }
    }
}
