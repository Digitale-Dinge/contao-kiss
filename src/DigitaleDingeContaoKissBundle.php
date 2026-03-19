<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss;

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
}
