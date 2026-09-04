<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use DigitaleDinge\CompanyBundle\DigitaleDingeCompanyBundle;
use DigitaleDinge\GridRatioWidgetBundle\DigitaleDingeGridRatioWidgetBundle;
use PHPUnit\Framework\TestCase;

class PluginTest extends TestCase
{
    public function testReturnsTheBundleConfiguration(): void
    {
        $config = new Plugin()->getBundles($this->createStub(ParserInterface::class))[0];

        $plugins = [
            ContaoCoreBundle::class,
            DigitaleDingeCompanyBundle::class,
            DigitaleDingeGridRatioWidgetBundle::class,
        ];

        $this->assertInstanceOf(BundleConfig::class, $config);
        $this->assertSame($plugins, $config->getLoadAfter());
    }
}
