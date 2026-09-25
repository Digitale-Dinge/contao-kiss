<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Tests\Styles\Option;

use DigitaleDinge\ContaoKiss\Styles\Option\Component\Media\LayoutOption;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class StyleOptionTest extends TestCase
{
    #[DataProvider('provideKeys')]
    public function testResolvesTheKeyToTheEnumValue(string|null $key, string $expected): void
    {
        $this->assertSame($expected, (string) new LayoutOption($key));
    }

    public static function provideKeys(): iterable
    {
        yield 'case name differs from its value' => ['side_reverse', 'side-reverse'];
        yield 'case name equals its value' => ['side', 'side'];
        yield 'case with an empty value' => ['default', ''];
        yield 'unknown key' => ['does_not_exist', ''];
        yield 'no key falls back to the empty default' => [null, ''];
    }

    public function testMagicCallReturnsTheValueOfTheNamedCase(): void
    {
        $option = new LayoutOption();

        $this->assertSame('media-background', $option->media_background());
        $this->assertNull($option->does_not_exist());
    }
}
