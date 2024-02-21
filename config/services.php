<?php

declare(strict_types=1);

use DigitaleDinge\ContaoKiss\EventListener\SymlinkListener;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\param;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function(ContainerConfigurator $container)
{
    $container->services()

        ->defaults()
            ->autoconfigure()
            ->autowire()

        ->load('DigitaleDinge\\ContaoKiss\\', '../src/*')

        ->set(SymlinkListener::class)
            ->args([
                param('kernel.project_dir'),
                param('contao.web_dir'),
                service('filesystem'),
                service('assets._package_digitale_dinge_contao_kiss')
            ])
    ;
};
