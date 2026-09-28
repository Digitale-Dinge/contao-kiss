<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss;

use DigitaleDinge\ContaoKiss\Asset\VersionStrategy\ViteVersionStrategy;
use DigitaleDinge\ContaoKiss\DependencyInjection\Attribute\AsKissStyleOption;
use DigitaleDinge\ContaoKiss\DependencyInjection\Compiler\AddStyleOptionsPass;
use DigitaleDinge\ContaoKiss\Styles\StyleOptionRegistry;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class DigitaleDingeContaoKissBundle extends AbstractBundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new AddStyleOptionsPass());
    }

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->import('../config/services.yaml');
        $container->import('../config/migrations.yaml');

        $builder->registerAttributeForAutoconfiguration(
            AsKissStyleOption::class,
            static function (ChildDefinition $definition, AsKissStyleOption $attribute): void {
                $definition->addTag(StyleOptionRegistry::TAG_NAME, $attribute->attributes);
            },
        );
    }

    public function prependExtension(ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        if (!$builder->hasExtension('reprise')) {
            return;
        }

        $builder->prependExtensionConfig('reprise', [
            'output_path' => '%kernel.project_dir%/public/layout',
            'cache' => !$builder->getParameter('kernel.debug'),
        ]);

        $builder->prependExtensionConfig('framework', [
            'assets' => [
                'packages' => [
                    'kiss_theme' => [
                        'version_strategy' => ViteVersionStrategy::class,
                    ],
                ],
            ],
        ]);
    }
}
