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

    public function __construct(private readonly string|null $key = null)
    {
    }

    public function __toString(): string
    {
        try {
            return (string) $this->getCase($this->key ?? $this->default)?->value;
        }
        catch (\Throwable) {
            return '';
        }
    }

    /**
     * @param array<mixed> $arguments
     */
    public function __call(string $name, array $arguments): string|null
    {
        try {
            $value = $this->getCase($name)?->value;

            return null === $value ? null : (string) $value;
        }
        catch (\Throwable) {
            return null;
        }
    }

    private function getCase(string $name): \BackedEnum|null
    {
        $case = \constant($this->enumClass.'::'.$name);

        return $case instanceof \BackedEnum ? $case : null;
    }
}
