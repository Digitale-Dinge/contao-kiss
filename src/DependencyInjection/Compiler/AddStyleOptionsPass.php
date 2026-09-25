<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\DependencyInjection\Compiler;

use DigitaleDinge\ContaoKiss\Styles\Option\StyleOption;
use DigitaleDinge\ContaoKiss\Styles\StyleOptionRegistry;
use DigitaleDinge\ContaoKiss\Twig\Global\StylesVariable;
use Symfony\Component\Config\Definition\Exception\InvalidDefinitionException;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class AddStyleOptionsPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has(StyleOptionRegistry::class)) {
            return;
        }

        $candidates = [];

        foreach ($container->findTaggedServiceIds(StyleOptionRegistry::TAG_NAME) as $serviceId => $tags) {
            $class = $container->getParameterBag()->resolveValue($container->findDefinition($serviceId)->getClass() ?? $serviceId);

            if (!is_subclass_of($class, StyleOption::class)) {
                throw new InvalidDefinitionException(\sprintf('The style option "%s" must extend "%s".', $class, StyleOption::class));
            }

            foreach ($tags as $attributes) {
                $name = $attributes['name'] ?? $class;

                if ('' === $name) {
                    throw new InvalidDefinitionException(\sprintf('The style option "%s" has an empty name.', $class));
                }

                $candidates[$name][] = [
                    'class' => $class,
                    'groups' => $attributes['groups'] ?? [],
                    'label' => $attributes['label'] ?? null,
                    'priority' => (int) ($attributes['priority'] ?? 0),
                ];
            }

            $container->removeDefinition($serviceId);
        }

        $reserved = $this->getReservedNames();
        $options = [];
        $enumNames = [];
        $groups = [];

        foreach ($candidates as $name => $registrations) {
            if ($this->isCallableName($name) && \in_array(strtolower($name), $reserved, true)) {
                throw new InvalidDefinitionException(\sprintf('The style option name "%s" is reserved by "%s".', $name, StylesVariable::class));
            }

            usort($registrations, static fn (array $a, array $b): int => $b['priority'] <=> $a['priority']);

            if (isset($registrations[1]) && $registrations[0]['priority'] === $registrations[1]['priority']) {
                throw new InvalidDefinitionException(\sprintf('The style option "%s" is registered by "%s" and "%s" with the same priority %d.', $name, $registrations[0]['class'], $registrations[1]['class'], $registrations[0]['priority']));
            }

            foreach ($registrations as $registration) {
                $enumNames[$this->getEnumClass($registration['class'])][$name] = true;
            }

            $winner = $registrations[0];

            $options[$name] = [
                'class' => $winner['class'],
                'enum' => $this->getEnumClass($winner['class']),
                'label' => $winner['label'] ?? $this->getDefaultLabel($name),
            ];

            foreach ($winner['groups'] as $group) {
                $groups[$group][] = ['name' => $name, 'priority' => $winner['priority']];
            }
        }

        foreach ($groups as $group => $members) {
            usort($members, static fn (array $a, array $b): int => [$b['priority'], $a['name']] <=> [$a['priority'], $b['name']]);
            $groups[$group] = array_column($members, 'name');
        }

        $enums = [];

        foreach ($enumNames as $enum => $names) {
            if (1 === \count($names)) {
                $enums[$enum] = array_key_first($names);
            }
        }

        ksort($options);
        ksort($enums);

        $container->findDefinition(StyleOptionRegistry::class)->addMethodCall('setStyleOptions', [$options, $enums, $groups]);
    }

    /**
     * A name like shape_radius can be called in Twig as styles.shape_radius(key)
     * No calling class names and with a dot.
     */
    private function isCallableName(string $name): bool
    {
        return !str_contains($name, '.') && !str_contains($name, '\\');
    }

    /**
     * Resolve default label -> Typography\HeadingOption -> style_options.heading + custom name = last segment
     */
    private function getDefaultLabel(string $name): string
    {
        if (str_contains($name, '\\')) {
            $short = substr((string) strrchr($name, '\\'), 1);
            $short = str_ends_with($short, 'Option') ? substr($short, 0, -6) : $short;

            return 'style_options.'.strtolower((string) preg_replace('/(?<!^)[A-Z]/', '_$0', $short));
        }

        return 'style_options.'.substr((string) strrchr('.'.$name, '.'), 1);
    }

    /**
     * @param class-string<StyleOption> $class
     *
     * @return class-string<\BackedEnum>
     * @throws \ReflectionException
     */
    private function getEnumClass(string $class): string
    {
        $enum = (new \ReflectionProperty($class, 'enumClass'))->getDefaultValue();

        if (!\is_string($enum) || !is_subclass_of($enum, \BackedEnum::class)) {
            throw new InvalidDefinitionException(\sprintf('The style option "%s" must set $enumClass to a backed enum.', $class));
        }

        return $enum;
    }

    /**
     * The Twig Globals e.g. styles.name is case-insensitive.
     * Just making sure they are not shadowed when falling back to the magic __call method.
     *
     * @return list<string>
     */
    private function getReservedNames(): array
    {
        $names = [];

        foreach (new \ReflectionClass(StylesVariable::class)->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
            $methodName = $method->getName();

            if (str_starts_with($methodName, '__')) {
                continue;
            }

            $names[] = strtolower(str_starts_with($methodName, 'get') ? substr($methodName, 3) : $methodName);
        }

        return $names;
    }
}
