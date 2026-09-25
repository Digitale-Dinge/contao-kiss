<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Tests\EventListener;

use DigitaleDinge\ContaoKiss\EventListener\TranslatableEnumTrait;
use DigitaleDinge\ContaoKiss\Styles\Option\Component\Media\Layout;
use DigitaleDinge\ContaoKiss\Tests\Fixtures\Styles\PlainEnum;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Translation\TranslatorInterface;

final class TranslatableEnumTraitTest extends TestCase
{
    public function testMapsCaseNamesToTranslatedLabels(): void
    {
        $this->assertSame(
            [
                'default' => 'style_options.component.media.layout.default',
                'reverse' => 'style_options.component.media.layout.reverse',
                'side' => 'style_options.component.media.layout.side',
                'side_reverse' => 'style_options.component.media.layout.side_reverse',
                'media_background' => 'style_options.component.media.layout.media_background',
            ],
            $this->createSubject()->getTranslatedOptions(Layout::class),
        );
    }

    public function testFallsBackToTheValueWhenTheEnumHasNoLabel(): void
    {
        $this->assertSame(
            ['first' => 'first-value', 'second' => 'second-value'],
            $this->createSubject()->getTranslatedOptions(PlainEnum::class),
        );
    }

    public function testRejectsAClassThatIsNotABackedEnum(): void
    {
        $this->expectException(\LogicException::class);

        $this->createSubject()->getTranslatedOptions(\stdClass::class);
    }

    private function createSubject(): object
    {
        $translator = $this->createStub(TranslatorInterface::class);
        $translator
            ->method('trans')
            ->willReturnArgument(0)
        ;

        return new class($translator) {
            use TranslatableEnumTrait;
        };
    }
}
