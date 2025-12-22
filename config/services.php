<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function(ContainerConfigurator $container)
{
    $container->services()

        ->defaults()
            ->autoconfigure()
            ->autowire()
            ->public()

        ->load('DigitaleDinge\\ContaoKiss\\', '../src/*')
            ->exclude('../src/{Event,Model,Util,DigitaleDingeKissBundle.php}')
    ;
};
