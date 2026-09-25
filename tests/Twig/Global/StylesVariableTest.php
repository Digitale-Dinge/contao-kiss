<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Tests\Twig\Global;

use DigitaleDinge\ContaoKiss\Styles\Option\Color;
use DigitaleDinge\ContaoKiss\Styles\Option\Component;
use DigitaleDinge\ContaoKiss\Styles\Option\Layout;
use DigitaleDinge\ContaoKiss\Styles\Option\Margin;
use DigitaleDinge\ContaoKiss\Styles\Option\Modifier;
use DigitaleDinge\ContaoKiss\Styles\Option\Padding;
use DigitaleDinge\ContaoKiss\Styles\Option\Typography;
use DigitaleDinge\ContaoKiss\Tests\Fixtures\Styles\StyleOptionRegistryFactory;
use DigitaleDinge\ContaoKiss\Tests\Fixtures\Styles\SwappedLayoutOption;
use DigitaleDinge\ContaoKiss\Twig\Global\StylesVariable;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class StylesVariableTest extends TestCase
{
    #[DataProvider('provideGetters')]
    public function testEveryGetterResolvesItsOption(string $getter, string $optionClass): void
    {
        $this->assertInstanceOf($optionClass, $this->createStyles()->{$getter}());
    }

    public static function provideGetters(): iterable
    {
        yield ['getContainer', Layout\ContainerOption::class];
        yield ['getColumn', Layout\ColumnOption::class];
        yield ['getGap', Layout\GapOption::class];
        yield ['getSpan', Layout\ColumnSpanOption::class];
        yield ['getCrossAlignment', Layout\CrossAlignmentOption::class];
        yield ['getHeading', Typography\HeadingOption::class];
        yield ['getFont_appearance', Typography\ResponsiveOption::class];
        yield ['getText_alignment', Typography\AlignmentOption::class];
        yield ['getBackground', Color\BackgroundOption::class];
        yield ['getColor', Color\ColorOption::class];
        yield ['getMargin_top', Margin\TopOption::class];
        yield ['getMargin_bottom', Margin\BottomOption::class];
        yield ['getPadding_top', Padding\TopOption::class];
        yield ['getPadding_bottom', Padding\BottomOption::class];
        yield ['getSize', Modifier\SizeOption::class];
        yield ['getVariant', Modifier\VariantOption::class];
        yield ['getCta_shape', Component\CallToAction\ShapeOption::class];
        yield ['getCta_type', Component\CallToAction\VariantOption::class];
        yield ['getMedia_layout', Component\Media\LayoutOption::class];
    }

    public function testGettersPassTheKeyThrough(): void
    {
        $this->assertSame('side-reverse', (string) $this->createStyles()->getMedia_layout('side_reverse'));
    }

    public function testOptionResolvesTheOptionClass(): void
    {
        $option = $this->createStyles()->option(Component\Media\LayoutOption::class, 'media_background');

        $this->assertInstanceOf(Component\Media\LayoutOption::class, $option);
        $this->assertSame('media-background', (string) $option);
    }

    public function testOptionResolvesTheEnumClass(): void
    {
        $this->assertInstanceOf(Component\Media\LayoutOption::class, $this->createStyles()->option(Component\Media\Layout::class));
    }

    public function testOptionRejectsAnUnknownName(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->createStyles()->option('does.not_exist');
    }

    /**
     * @param list<string|null> $keys
     */
    #[DataProvider('provideKeys')]
    public function testOptionsJoinsSeveralKeys(array $keys, string $expected): void
    {
        $this->assertSame($expected, $this->createStyles()->options(Component\Media\LayoutOption::class, $keys));
    }

    public static function provideKeys(): iterable
    {
        yield 'several keys' => [['side', 'side_reverse'], 'side side-reverse'];
        yield 'no keys' => [[], ''];
        yield 'unknown keys are dropped' => [['side', 'does_not_exist'], 'side'];
        yield 'empty values are dropped' => [['default', 'side'], 'side'];
    }

    public function testCallResolvesANameWithoutDots(): void
    {
        $styles = $this->createStyles(['name' => 'shape_radius']);

        $this->assertSame('swapped-side', (string) $styles->shape_radius('side'));
    }

    public function testAHigherPriorityRegistrationReplacesAKissOption(): void
    {
        $styles = $this->createStyles(['name' => Component\Media\LayoutOption::class, 'priority' => 10]);

        $this->assertInstanceOf(SwappedLayoutOption::class, $styles->getMedia_layout('side'));
        $this->assertSame('swapped-side', (string) $styles->getMedia_layout('side'));
        $this->assertSame('swapped-side', (string) $styles->option(Component\Media\Layout::class, 'side'));
    }

    /**
     * @param array<string, mixed> $swappedLayout tag attributes to register SwappedLayoutOption with, if any
     */
    private function createStyles(array $swappedLayout = []): StylesVariable
    {
        $additional = [] === $swappedLayout ? [] : [SwappedLayoutOption::class => $swappedLayout];

        return new StylesVariable(StyleOptionRegistryFactory::fromKissOptions($additional));
    }
}
