<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Tests\Styles\Option;

use DigitaleDinge\ContaoKiss\Styles\Option\Color;
use DigitaleDinge\ContaoKiss\Styles\Option\Component;
use DigitaleDinge\ContaoKiss\Styles\Option\IconPosition;
use DigitaleDinge\ContaoKiss\Styles\Option\Layout;
use DigitaleDinge\ContaoKiss\Styles\Option\Margin;
use DigitaleDinge\ContaoKiss\Styles\Option\Modifier;
use DigitaleDinge\ContaoKiss\Styles\Option\Padding;
use DigitaleDinge\ContaoKiss\Styles\Option\Typography;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Case names are what we store in kiss_styles -> removing or renaming one will break existing content and will need
 * a migration. Additions only emit a warning until added to the cases.
 */
final class StyleOptionCasesTest extends TestCase
{
    /**
     * @var array<class-string<\BackedEnum>, list<string>>
     */
    private const array CASES = [
        Color\Background::class => ['transparent', 'neutral_one', 'neutral_two', 'neutral_three', 'primary', 'secondary', 'tertiary', 'success', 'warning', 'error', 'neutral_inverse'],
        Color\Color::class => ['primary', 'secondary', 'tertiary', 'success', 'warning', 'error'],
        Component\CallToAction\Shape::class => ['wide', 'block'],
        Component\CallToAction\Variant::class => ['soft', 'outline', 'text', 'link'],
        Component\Media\Layout::class => ['default', 'reverse', 'side', 'side_reverse', 'media_background'],
        Component\Swiper\Navigation::class => ['overlay', 'outside'],
        IconPosition::class => ['left', 'right'],
        Layout\Column::class => ['one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven', 'twelve'],
        Layout\ColumnSpan::class => ['one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven'],
        Layout\Container::class => ['base', 'narrower', 'narrow', 'full_pad', 'full', 'reset'],
        Layout\CrossAlignment::class => ['start', 'center', 'end'],
        Layout\Gap::class => ['x_small', 'small', 'medium', 'large', 'x_large', 'xx_large'],
        Margin\Bottom::class => ['half', 'one', 'two', 'three', 'four', 'five', 'six'],
        Margin\Top::class => ['half', 'one', 'two', 'three', 'four', 'five', 'six'],
        Modifier\Size::class => ['x_small', 'small', 'large', 'x_large'],
        Modifier\Variant::class => ['soft', 'outline', 'glass'],
        Padding\Bottom::class => ['half', 'one', 'two', 'three', 'four', 'five', 'six'],
        Padding\Top::class => ['half', 'one', 'two', 'three', 'four', 'five', 'six'],
        Typography\Alignment::class => ['start', 'center', 'end'],
        Typography\Heading::class => ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
        Typography\Responsive::class => ['display_one', 'display_two', 'display_three', 'headline_one', 'headline_two', 'headline_three', 'body_one', 'body_two', 'body_three'],
    ];

    /**
     * @param class-string<\BackedEnum> $enum
     * @param list<string>              $expected
     */
    #[DataProvider('provideEnums')]
    public function testNoCaseWasRemovedOrRenamed(string $enum, array $expected): void
    {
        $actual = array_map(static fn (\UnitEnum $case): string => $case->name, $enum::cases());

        $this->assertSame(
            [],
            array_values(array_diff($expected, $actual)),
            \sprintf('Cases of %s were removed or renamed, which breaks stored content.', $enum),
        );

        $added = array_values(array_diff($actual, $expected));

        if ([] !== $added) {
            $this->markTestIncomplete(\sprintf('New cases in %s, register them in StyleOptionCasesTest::CASES: %s', $enum, implode(', ', $added)));
        }
    }

    public static function provideEnums(): iterable
    {
        foreach (self::CASES as $enum => $cases) {
            yield $enum => [$enum, $cases];
        }
    }

    public function testEveryStyleOptionEnumIsRegistered(): void
    {
        $unregistered = array_values(array_diff(self::discoverEnums(), array_keys(self::CASES)));

        if ([] !== $unregistered) {
            $this->markTestIncomplete(\sprintf('New style option enums, register them in StyleOptionCasesTest::CASES: %s', implode(', ', $unregistered)));
        }

        $this->assertSame([], $unregistered);
    }

    /**
     * @return list<class-string<\BackedEnum>>
     */
    private static function discoverEnums(): array
    {
        $root = \dirname(__DIR__, 3).'/src/Styles/Option';
        $enums = [];

        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($root, \FilesystemIterator::SKIP_DOTS)) as $file) {
            if ('php' !== $file->getExtension()) {
                continue;
            }

            $relative = substr($file->getPathname(), \strlen($root) + 1, -4);
            $class = 'DigitaleDinge\\ContaoKiss\\Styles\\Option\\'.str_replace('/', '\\', $relative);

            if (enum_exists($class)) {
                $enums[] = $class;
            }
        }

        sort($enums);

        return $enums;
    }
}
