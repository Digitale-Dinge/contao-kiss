<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles;

use DigitaleDinge\ContaoKiss\Styles\Option\StyleOption;

/**
 * Option by default or custom name
 * Enum that was registered with an option resolves to the same name
 */
final class StyleOptionRegistry
{
    public const string TAG_NAME = 'contao_kiss.style_option';

    /**
     * @var array<string, array{class: class-string<StyleOption>, enum: class-string<\BackedEnum>, label: string}>
     */
    private array $options = [];

    /**
     * @var array<class-string<\BackedEnum>, string>
     */
    private array $enums = [];

    /**
     * @var array<string, list<string>>
     */
    private array $groups = [];

    /**
     * @param array<string, array{class: class-string<StyleOption>, enum: class-string<\BackedEnum>, label: string}> $options
     * @param array<class-string<\BackedEnum>, string>                                                               $enums
     * @param array<string, list<string>>                                                                            $groups
     */
    public function setStyleOptions(array $options, array $enums, array $groups): void
    {
        $this->options = $options;
        $this->enums = $enums;
        $this->groups = $groups;
    }

    public function has(string $option): bool
    {
        return isset($this->options[$option]) || isset($this->enums[$option]);
    }

    /**
     * @param string $option a registered name, option class or enum class
     */
    public function create(string $option, string|null $key = null): StyleOption
    {
        return new ($this->get($option)['class'])($key);
    }

    /**
     * @param string $option a registered name, option class or enum class
     *
     * @return class-string<\BackedEnum>
     */
    public function getEnum(string $option): string
    {
        return $this->get($option)['enum'];
    }

    /**
     * @param string $option a registered name, option class or enum class
     */
    public function getLabel(string $option): string
    {
        return $this->get($option)['label'];
    }

    /**
     * @return list<string>
     */
    public function getGroup(string $group): array
    {
        return $this->groups[$group] ?? [];
    }

    /**
     * @return array{class: class-string<StyleOption>, enum: class-string<\BackedEnum>, label: string}
     */
    private function get(string $option): array
    {
        $name = isset($this->options[$option]) ? $option : ($this->enums[$option] ?? null);

        if (null === $name) {
            throw new \InvalidArgumentException(\sprintf('There is no style option registered as "%s".', $option));
        }

        return $this->options[$name];
    }
}
