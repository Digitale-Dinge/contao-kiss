<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\DependencyInjection\Attribute;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::IS_REPEATABLE)]
class AsKissStyleOption
{
    public array $attributes;

    /**
     * @param list<string> $groups
     */
    public function __construct(string|null $name = null, array $groups = [], string|null $label = null, int $priority = 0)
    {
        $this->attributes = [
            'name' => $name,
            'groups' => $groups,
            'label' => $label,
            'priority' => $priority,
        ];
    }
}
