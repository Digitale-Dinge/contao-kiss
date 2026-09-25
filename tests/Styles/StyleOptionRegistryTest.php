<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Tests\Styles;

use DigitaleDinge\ContaoKiss\Styles\Option\Component\Media\Layout;
use DigitaleDinge\ContaoKiss\Styles\Option\Component\Media\LayoutOption;
use DigitaleDinge\ContaoKiss\Styles\StyleOptionRegistry;
use PHPUnit\Framework\TestCase;

final class StyleOptionRegistryTest extends TestCase
{
    public function testResolvesARegisteredOption(): void
    {
        $registry = $this->createRegistry();
        $option = $registry->create(LayoutOption::class, 'side_reverse');

        $this->assertTrue($registry->has(LayoutOption::class));
        $this->assertInstanceOf(LayoutOption::class, $option);
        $this->assertSame('side-reverse', (string) $option);
        $this->assertSame(Layout::class, $registry->getEnum(LayoutOption::class));
        $this->assertSame('style_options.layout', $registry->getLabel(LayoutOption::class));
        $this->assertSame([LayoutOption::class], $registry->getGroup('media'));
    }

    public function testResolvesTheEnumClassToItsOption(): void
    {
        $registry = $this->createRegistry();

        $this->assertTrue($registry->has(Layout::class));
        $this->assertInstanceOf(LayoutOption::class, $registry->create(Layout::class));
    }

    public function testAnUnknownGroupIsEmpty(): void
    {
        $this->assertSame([], $this->createRegistry()->getGroup('does_not_exist'));
    }

    public function testRejectsAnUnknownName(): void
    {
        $registry = $this->createRegistry();

        $this->assertFalse($registry->has('does.not_exist'));

        $this->expectException(\InvalidArgumentException::class);

        $registry->create('does.not_exist');
    }

    private function createRegistry(): StyleOptionRegistry
    {
        $registry = new StyleOptionRegistry();
        $registry->setStyleOptions(
            [LayoutOption::class => ['class' => LayoutOption::class, 'enum' => Layout::class, 'label' => 'style_options.layout']],
            [Layout::class => LayoutOption::class],
            ['media' => [LayoutOption::class]],
        );

        return $registry;
    }
}
