<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss;

use DigitaleDinge\ContaoKiss\Asset\VersionStrategy\ViteVersionStrategy;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class DigitaleDingeContaoKissBundle extends AbstractBundle
{
    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->rootNode()
            ->children()
                ->booleanNode('style_definition_override')
                    ->defaultFalse()
                ->end()
            ->end()
        ;
    }

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->import('../config/services.yaml');
        $container->import('../config/migrations.yaml');

        $builder->setParameter('contao_kiss.style_definition_override', $config['style_definition_override']);
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
