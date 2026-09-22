<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use DigitaleDinge\CompanyBundle\DigitaleDingeCompanyBundle;
use DigitaleDinge\GridRatioWidgetBundle\DigitaleDingeGridRatioWidgetBundle;
use PHPUnit\Framework\TestCase;
use Symfony\Reprise\RepriseBundle;

class PluginTest extends TestCase
{
    public function testReturnsTheBundleConfiguration(): void
    {
        $configs = new Plugin()->getBundles($this->createStub(ParserInterface::class));

        $this->assertCount(2, $configs);
        $this->assertSame(RepriseBundle::class, $configs[0]->getName());

        $config = $configs[1];

        $plugins = [
            ContaoCoreBundle::class,
            DigitaleDingeCompanyBundle::class,
            DigitaleDingeGridRatioWidgetBundle::class,
            RepriseBundle::class,
        ];

        $this->assertInstanceOf(BundleConfig::class, $config);
        $this->assertSame($plugins, $config->getLoadAfter());
    }
}
