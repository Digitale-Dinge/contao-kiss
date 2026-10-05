<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Tests;

use Contao\Config;
use Contao\DcaLoader;
use Contao\System;
use DigitaleDinge\ContaoKiss\CustomElementsConfigurationBuilder;
use DigitaleDinge\ContaoKiss\Tests\Fixtures\Styles\StyleOptionRegistryFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Contracts\Translation\TranslatorInterface;

final class CustomElementsConfigurationBuilderTest extends TestCase
{
    /**
     * @throws \ReflectionException
     */
    protected function setUp(): void
    {
        parent::setUp();

        $GLOBALS['TL_LANG']['MSC']['url'] = ['URL', 'Enter a web address.'];
        $GLOBALS['TL_DCA']['tl_content']['fields']['url'] = [
            'label' => &$GLOBALS['TL_LANG']['MSC']['url'],
            'inputType' => 'text',
        ];

        new \ReflectionProperty(DcaLoader::class, 'arrLoaded')->setValue(null, [
            'dcaFiles' => array_fill_keys(['tl_content', 'tl_module', 'tl_company', 'tl_member'], true),
        ]);

        System::setContainer($this->createContainer());
    }

    protected function tearDown(): void
    {
        DcaLoader::reset();

        unset($GLOBALS['TL_DCA'], $GLOBALS['TL_LANG']);

        parent::tearDown();
    }

    public function testRelabelingACopiedListFieldKeepsTheOriginalLabel(): void
    {
        $config = $this->createBuilder()
            ->create('test')
            ->startList()
            ->addImageUrlField()
            ->endList()
            ->build()
        ;

        $this->assertSame(['URL', 'Enter a web address.'], $GLOBALS['TL_LANG']['MSC']['url']);
        $this->assertSame('rsce.field.imageUrl.label', $config['fields']['list']['fields']['imageUrl']['label'][0]);
    }

    private function createBuilder(): CustomElementsConfigurationBuilder
    {
        $translator = $this->createStub(TranslatorInterface::class);
        $translator
            ->method('trans')
            ->willReturnArgument(0)
        ;

        return new CustomElementsConfigurationBuilder($translator, StyleOptionRegistryFactory::fromKissOptions());
    }

    private function createContainer(): Container
    {
        $container = new Container();
        $container->setParameter('kernel.debug', false);
        $container->set(Config::class, $this->createStub(Config::class));
        $container->set('request_stack', new class() {
            public function getCurrentRequest(): null
            {
                return null;
            }
        });

        return $container;
    }
}
