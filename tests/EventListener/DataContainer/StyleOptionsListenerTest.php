<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Tests\EventListener\DataContainer;

use Contao\DataContainer;
use DigitaleDinge\ContaoKiss\EventListener\DataContainer\StyleOptionsListener;
use DigitaleDinge\ContaoKiss\Styles\Option\Component\CallToAction;
use DigitaleDinge\ContaoKiss\Styles\Option\Modifier;
use DigitaleDinge\ContaoKiss\Styles\Option\Typography;
use DigitaleDinge\ContaoKiss\Tests\Fixtures\Styles\StyleOptionRegistryFactory;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Translation\TranslatorInterface;

final class StyleOptionsListenerTest extends TestCase
{
    public function testGroupsTheHeadlineAppearanceIntoHeadingAndResponsive(): void
    {
        $options = $this->createListener()->addHeadlineAppearanceOptions();

        $this->assertSame(['style_options.heading', 'style_options.responsive'], array_keys($options));
        $this->assertSame($this->caseNames(Typography\Heading::class), array_keys($options['style_options.heading']));
        $this->assertSame($this->caseNames(Typography\Responsive::class), array_keys($options['style_options.responsive']));
    }

    /**
     * @param class-string<\BackedEnum> $enum
     */
    #[DataProvider('provideVariantTypes')]
    public function testVariantOptionsDependOnTheRecordType(string|null $type, string $enum): void
    {
        $dc = $this->createStub(DataContainer::class);
        $dc
            ->method('getCurrentRecord')
            ->willReturn(null === $type ? null : ['type' => $type])
        ;

        $this->assertSame($this->caseNames($enum), array_keys($this->createListener()->addVariantOptions($dc)));
    }

    public static function provideVariantTypes(): iterable
    {
        yield 'submit field uses the call-to-action variants' => ['submit', CallToAction\Variant::class];
        yield 'any other type uses the generic variants' => ['text', Modifier\Variant::class];
        yield 'no record uses the generic variants' => [null, Modifier\Variant::class];
    }

    private function createListener(): StyleOptionsListener
    {
        $translator = $this->createStub(TranslatorInterface::class);
        $translator
            ->method('trans')
            ->willReturnArgument(0)
        ;

        return new StyleOptionsListener($translator, StyleOptionRegistryFactory::fromKissOptions());
    }

    /**
     * @param class-string<\BackedEnum> $enum
     *
     * @return list<string>
     */
    private function caseNames(string $enum): array
    {
        return array_map(static fn (\BackedEnum $case): string => $case->name, $enum::cases());
    }
}
