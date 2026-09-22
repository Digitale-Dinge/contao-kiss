<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use DigitaleDinge\CompanyBundle\DigitaleDingeCompanyBundle;
use DigitaleDinge\ContaoKiss\DigitaleDingeContaoKissBundle;
use DigitaleDinge\GridRatioWidgetBundle\DigitaleDingeGridRatioWidgetBundle;
use Symfony\Reprise\RepriseBundle;

class Plugin implements BundlePluginInterface
{
    public function getBundles(ParserInterface $parser): array
    {
        return [
            new BundleConfig(RepriseBundle::class),
            new BundleConfig(DigitaleDingeContaoKissBundle::class)
                ->setLoadAfter([
                    ContaoCoreBundle::class,
                    DigitaleDingeCompanyBundle::class,
                    DigitaleDingeGridRatioWidgetBundle::class,
                    RepriseBundle::class,
                ]),
        ];
    }
}
