<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use DigitaleDinge\ContaoKiss\DigitaleDingeContaoKissBundle;


class Plugin implements BundlePluginInterface
{

    public function getBundles(ParserInterface $parser): array
    {
        return [
            BundleConfig::create(DigitaleDingeContaoKissBundle::class)
                ->setLoadAfter([
                    ContaoCoreBundle::class
                ])
        ];
    }

}
