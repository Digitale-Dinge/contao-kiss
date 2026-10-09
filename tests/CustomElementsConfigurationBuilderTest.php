<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Tests;

use Contao\Config;
use Contao\Controller;
use Contao\CoreBundle\Framework\Adapter;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\DcaLoader;
use Contao\System;
use DigitaleDinge\ContaoKiss\CustomElementsConfigurationBuilder;
use DigitaleDinge\ContaoKiss\Tests\Fixtures\Styles\StyleOptionRegistryFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Translation\TranslatorInterface;

final class CustomElementsConfigurationBuilderTest extends TestCase
{
    /**
     * @throws \ReflectionException
     */
    protected function setUp(): void
    {
        parent::setUp();

        // @phpstan-ignore offsetAccess.nonOffsetAccessible, offsetAccess.nonOffsetAccessible ($GLOBALS is untyped)
        $GLOBALS['TL_LANG']['MSC']['url'] = ['URL', 'Enter a web address.'];
        // @phpstan-ignore offsetAccess.nonOffsetAccessible, offsetAccess.nonOffsetAccessible, offsetAccess.nonOffsetAccessible ($GLOBALS is untyped)
        $GLOBALS['TL_DCA']['tl_content']['fields']['url'] = [
            'label' => &$GLOBALS['TL_LANG']['MSC']['url'],
            'inputType' => 'text',
        ];

        new \ReflectionProperty(DcaLoader::class, 'arrLoaded')->setValue(null, [
            'dcaFiles' => array_fill_keys(['tl_content', 'tl_company', 'tl_member'], true),
        ]);

        System::setContainer($this->createContainer());
    }

    protected function tearDown(): void
    {
        DcaLoader::reset();

        new Filesystem()->remove($this->getCacheDir());

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

        // @phpstan-ignore offsetAccess.nonOffsetAccessible, offsetAccess.nonOffsetAccessible ($GLOBALS is untyped)
        $this->assertSame(['URL', 'Enter a web address.'], $GLOBALS['TL_LANG']['MSC']['url']);
        $this->assertSame('rsce.field.imageUrl.label', $this->getPath($config, 'fields', 'list', 'fields', 'imageUrl', 'label', 0));
    }

    public function testBuildingInASubRequestLoadsTheDcaOfThatRequest(): void
    {
        DcaLoader::reset();

        $requestStack = new RequestStack([new Request()]);

        System::setContainer($this->createContainer($requestStack));

        $builder = $this->createBuilder();
        $buildCallToActionList = static fn (): array => $builder
            ->create('test')
            ->startList()
            ->addCallToActionField()
            ->endList()
            ->build()
        ;

        $buildCallToActionList();

        // Contao starts every request without a DCA as soon as any DCA is loaded in it
        $requestStack->push(new Request());
        DcaLoader::switchToCurrentRequest();

        $fields = $this->getPath($buildCallToActionList(), 'fields', 'list', 'fields', 'callToAction', 'fields');

        $this->assertSame('text', $this->getPath($fields, 'url', 'inputType'));
        $this->assertSame('w25', $this->getPath($fields, 'target', 'eval', 'tl_class'));
    }

    private function getPath(mixed $value, int|string ...$keys): mixed
    {
        foreach ($keys as $key) {
            if (!\is_array($value) || !\array_key_exists($key, $value)) {
                $this->fail(\sprintf('Missing key "%s".', $key));
            }

            $value = $value[$key];
        }

        return $value;
    }

    private function createBuilder(): CustomElementsConfigurationBuilder
    {
        $translator = $this->createStub(TranslatorInterface::class);
        $translator
            ->method('trans')
            ->willReturnArgument(0)
        ;

        $framework = $this->createStub(ContaoFramework::class);
        $framework
            ->method('getAdapter')
            ->willReturn(new Adapter(Controller::class))
        ;

        return new CustomElementsConfigurationBuilder($translator, StyleOptionRegistryFactory::fromKissOptions(), $framework);
    }

    private function createContainer(RequestStack|null $requestStack = null): Container
    {
        $container = new Container();
        $container->setParameter('kernel.debug', false);
        $container->setParameter('kernel.cache_dir', $this->getCacheDir());
        $container->set(Config::class, $this->createStub(Config::class));
        $container->set('request_stack', $requestStack ?? new class() {
            public function getCurrentRequest(): null
            {
                return null;
            }
        });
        $container->set('contao.resource_locator', new class() {
            public function locate(string $name): array
            {
                $path = __DIR__.'/Fixtures/'.$name;

                if (!new Filesystem()->exists($path)) {
                    throw new \InvalidArgumentException();
                }

                return [$path];
            }
        });

        return $container;
    }

    private function getCacheDir(): string
    {
        return sys_get_temp_dir().'/contao-kiss-tests';
    }
}
