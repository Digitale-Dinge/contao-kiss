<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Tests\Fixtures\Styles;

use DigitaleDinge\ContaoKiss\DependencyInjection\Attribute\AsKissStyleOption;
use DigitaleDinge\ContaoKiss\DependencyInjection\Compiler\AddStyleOptionsPass;
use DigitaleDinge\ContaoKiss\Styles\StyleOptionRegistry;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class StyleOptionRegistryFactory
{
    /**
     * @param array<class-string, array<string, mixed>> $additional
     */
    public static function fromKissOptions(array $additional = []): StyleOptionRegistry
    {
        $container = new ContainerBuilder();
        $container->register(StyleOptionRegistry::class, StyleOptionRegistry::class);

        foreach (self::getKissOptionClasses() as $class) {
            $definition = $container->register($class, $class);

            foreach (new \ReflectionClass($class)->getAttributes(AsKissStyleOption::class) as $attribute) {
                $definition->addTag(StyleOptionRegistry::TAG_NAME, $attribute->newInstance()->attributes);
            }
        }

        foreach ($additional as $class => $attributes) {
            $container->register('additional.'.$class, $class)->addTag(StyleOptionRegistry::TAG_NAME, $attributes);
        }

        new AddStyleOptionsPass()->process($container);

        return self::fromContainer($container);
    }

    public static function fromContainer(ContainerBuilder $container): StyleOptionRegistry
    {
        $registry = new StyleOptionRegistry();

        foreach ($container->getDefinition(StyleOptionRegistry::class)->getMethodCalls() as [$method, $arguments]) {
            $registry->{$method}(...$arguments);
        }

        return $registry;
    }

    /**
     * @return list<class-string>
     */
    public static function getKissOptionClasses(): array
    {
        $root = \dirname(__DIR__, 3).'/src/Styles/Option';
        $classes = [];

        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($root, \FilesystemIterator::SKIP_DOTS)) as $file) {
            if (!str_ends_with($file->getFilename(), 'Option.php') || 'StyleOption.php' === $file->getFilename()) {
                continue;
            }

            $relative = substr($file->getPathname(), \strlen($root) + 1, -4);
            $classes[] = 'DigitaleDinge\\ContaoKiss\\Styles\\Option\\'.str_replace('/', '\\', $relative);
        }

        sort($classes);

        return $classes;
    }
}
