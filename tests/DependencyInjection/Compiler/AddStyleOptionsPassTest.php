<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Tests\DependencyInjection\Compiler;

use DigitaleDinge\ContaoKiss\DependencyInjection\Compiler\AddStyleOptionsPass;
use DigitaleDinge\ContaoKiss\Styles\Option\Component\Media\Layout;
use DigitaleDinge\ContaoKiss\Styles\Option\Component\Media\LayoutOption;
use DigitaleDinge\ContaoKiss\Styles\Option\Layout\CrossAlignmentOption;
use DigitaleDinge\ContaoKiss\Styles\StyleOptionRegistry;
use DigitaleDinge\ContaoKiss\Tests\Fixtures\Styles\NotAStyleOption;
use DigitaleDinge\ContaoKiss\Tests\Fixtures\Styles\StyleOptionRegistryFactory;
use DigitaleDinge\ContaoKiss\Tests\Fixtures\Styles\SwappedLayout;
use DigitaleDinge\ContaoKiss\Tests\Fixtures\Styles\SwappedLayoutOption;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Exception\InvalidDefinitionException;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class AddStyleOptionsPassTest extends TestCase
{
    public function testDoesNothingWithoutTheRegistry(): void
    {
        $container = new ContainerBuilder();
        $container->register('option', LayoutOption::class)->addTag(StyleOptionRegistry::TAG_NAME, []);

        new AddStyleOptionsPass()->process($container);

        $this->assertTrue($container->hasDefinition('option'));
    }

    public function testRegistersTheOptionUnderItsClassAndRemovesItsDefinition(): void
    {
        $container = $this->createContainer(['option' => [LayoutOption::class, []]]);

        new AddStyleOptionsPass()->process($container);

        $registry = StyleOptionRegistryFactory::fromContainer($container);

        $this->assertFalse($container->hasDefinition('option'));
        $this->assertInstanceOf(LayoutOption::class, $registry->create(LayoutOption::class));
        $this->assertInstanceOf(LayoutOption::class, $registry->create(Layout::class));
        $this->assertSame(Layout::class, $registry->getEnum(LayoutOption::class));
    }

    public function testRegistersACustomName(): void
    {
        $container = $this->createContainer(['option' => [LayoutOption::class, ['name' => 'shape_radius']]]);

        new AddStyleOptionsPass()->process($container);

        $this->assertInstanceOf(LayoutOption::class, StyleOptionRegistryFactory::fromContainer($container)->create('shape_radius'));
    }

    /**
     * @param array<string, mixed> $attributes
     */
    #[DataProvider('provideLabels')]
    public function testDerivesTheLabel(string $class, array $attributes, string $name, string $expected): void
    {
        $container = $this->createContainer(['option' => [$class, $attributes]]);

        new AddStyleOptionsPass()->process($container);

        $this->assertSame($expected, StyleOptionRegistryFactory::fromContainer($container)->getLabel($name));
    }

    public static function provideLabels(): iterable
    {
        yield 'option class' => [LayoutOption::class, [], LayoutOption::class, 'style_options.layout'];
        yield 'option class, several words' => [CrossAlignmentOption::class, [], CrossAlignmentOption::class, 'style_options.cross_alignment'];
        yield 'dotted name' => [LayoutOption::class, ['name' => 'typography.heading'], 'typography.heading', 'style_options.heading'];
        yield 'name without dots' => [LayoutOption::class, ['name' => 'shape_radius'], 'shape_radius', 'style_options.shape_radius'];
        yield 'explicit label' => [LayoutOption::class, ['label' => 'app.layouts'], LayoutOption::class, 'app.layouts'];
    }

    public function testTheHigherPriorityWinsAndKeepsAnsweringToTheReplacedEnum(): void
    {
        $container = $this->createContainer([
            'kiss' => [LayoutOption::class, []],
            'app' => [SwappedLayoutOption::class, ['name' => LayoutOption::class, 'priority' => 10]],
        ]);

        new AddStyleOptionsPass()->process($container);

        $registry = StyleOptionRegistryFactory::fromContainer($container);

        $this->assertInstanceOf(SwappedLayoutOption::class, $registry->create(LayoutOption::class));
        $this->assertSame(SwappedLayout::class, $registry->getEnum(LayoutOption::class));
        $this->assertInstanceOf(SwappedLayoutOption::class, $registry->create(Layout::class));
        $this->assertInstanceOf(SwappedLayoutOption::class, $registry->create(SwappedLayout::class));
    }

    public function testAnEnumUsedUnderTwoNamesIsNotResolvable(): void
    {
        $container = $this->createContainer([
            'a' => [LayoutOption::class, []],
            'b' => [LayoutOption::class, ['name' => 'shape_radius']],
        ]);

        new AddStyleOptionsPass()->process($container);

        $registry = StyleOptionRegistryFactory::fromContainer($container);

        $this->assertFalse($registry->has(Layout::class));
        $this->assertTrue($registry->has(LayoutOption::class));
        $this->assertTrue($registry->has('shape_radius'));
    }

    public function testRejectsTwoRegistrationsWithTheSamePriority(): void
    {
        $container = $this->createContainer([
            'kiss' => [LayoutOption::class, []],
            'app' => [SwappedLayoutOption::class, ['name' => LayoutOption::class]],
        ]);

        $this->expectException(InvalidDefinitionException::class);
        $this->expectExceptionMessageIsOrContains('with the same priority');

        new AddStyleOptionsPass()->process($container);
    }

    #[DataProvider('provideInvalidClasses')]
    public function testRejectsAClassThatIsNotAStyleOption(string $class): void
    {
        $container = $this->createContainer(['option' => [$class, []]]);

        $this->expectException(InvalidDefinitionException::class);
        $this->expectExceptionMessageIsOrContains('must extend');

        new AddStyleOptionsPass()->process($container);
    }

    public static function provideInvalidClasses(): iterable
    {
        yield 'class does not exist' => ['DigitaleDinge\\ContaoKiss\\Tests\\Fixtures\\Styles\\DoesNotExist'];
        yield 'class is not a style option' => [NotAStyleOption::class];
    }

    public function testRejectsAnEmptyName(): void
    {
        $container = $this->createContainer(['option' => [LayoutOption::class, ['name' => '']]]);

        $this->expectException(InvalidDefinitionException::class);
        $this->expectExceptionMessageIsOrContains('empty name');

        new AddStyleOptionsPass()->process($container);
    }

    #[DataProvider('provideReservedNames')]
    public function testRejectsANameReservedByStylesVariable(string $name): void
    {
        $container = $this->createContainer(['option' => [LayoutOption::class, ['name' => $name]]]);

        $this->expectException(InvalidDefinitionException::class);
        $this->expectExceptionMessageIsOrContains('is reserved');

        new AddStyleOptionsPass()->process($container);
    }

    public static function provideReservedNames(): iterable
    {
        yield 'option method' => ['option'];
        yield 'options method' => ['options'];
        yield 'getter' => ['column'];
        yield 'getter, other case' => ['CrossAlignment'];
    }

    public function testOrdersGroupsByPriorityThenName(): void
    {
        $container = $this->createContainer([
            'b' => [LayoutOption::class, ['name' => 'group.b', 'groups' => ['appearance']]],
            'a' => [LayoutOption::class, ['name' => 'group.a', 'groups' => ['appearance']]],
            'top' => [LayoutOption::class, ['name' => 'group.z', 'groups' => ['appearance'], 'priority' => 5]],
        ]);

        new AddStyleOptionsPass()->process($container);

        $this->assertSame(['group.z', 'group.a', 'group.b'], StyleOptionRegistryFactory::fromContainer($container)->getGroup('appearance'));
    }

    public function testAnOverrideWithoutGroupsLeavesTheGroup(): void
    {
        $container = $this->createContainer([
            'kiss' => [LayoutOption::class, ['groups' => ['appearance']]],
            'app' => [SwappedLayoutOption::class, ['name' => LayoutOption::class, 'groups' => [], 'priority' => 10]],
        ]);

        new AddStyleOptionsPass()->process($container);

        $this->assertSame([], StyleOptionRegistryFactory::fromContainer($container)->getGroup('appearance'));
    }

    /**
     * @param array<string, array{0: string, 1: array<string, mixed>}> $options service id => [class, tag attributes]
     */
    private function createContainer(array $options): ContainerBuilder
    {
        $container = new ContainerBuilder();
        $container->register(StyleOptionRegistry::class, StyleOptionRegistry::class);

        foreach ($options as $id => [$class, $attributes]) {
            $container->register($id, $class)->addTag(StyleOptionRegistry::TAG_NAME, $attributes);
        }

        return $container;
    }
}
