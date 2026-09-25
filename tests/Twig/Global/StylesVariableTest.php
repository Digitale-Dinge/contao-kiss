<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Tests\Twig\Global;

use DigitaleDinge\ContaoKiss\Event\ContaoKissEvents;
use DigitaleDinge\ContaoKiss\Event\Styles\StyleOptionEvent;
use DigitaleDinge\ContaoKiss\Styles\Option\Component\Media\LayoutOption;
use DigitaleDinge\ContaoKiss\Tests\Fixtures\Styles\NotAStyleOption;
use DigitaleDinge\ContaoKiss\Tests\Fixtures\Styles\SwappedLayoutOption;
use DigitaleDinge\ContaoKiss\Twig\Global\StylesVariable;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class StylesVariableTest extends TestCase
{
    public function testReturnsTheOptionWithoutDispatchingWhenOverridesAreDisabled(): void
    {
        $dispatcher = $this->createMock(EventDispatcherInterface::class);
        $dispatcher
            ->expects($this->never())
            ->method('dispatch')
        ;

        $option = new StylesVariable($dispatcher, false)->getMedia_layout('side_reverse');

        $this->assertInstanceOf(LayoutOption::class, $option);
        $this->assertSame('side-reverse', (string) $option);
    }

    #[DataProvider('provideEventNames')]
    public function testDispatchesTheEventOfTheOptionClass(string $getter, string $eventName): void
    {
        $dispatcher = new EventDispatcher();
        $calls = 0;

        $dispatcher->addListener($eventName, static function (StyleOptionEvent $event) use (&$calls): void {
            ++$calls;
        });

        new StylesVariable($dispatcher, true)->{$getter}();

        $this->assertSame(1, $calls);
    }

    public static function provideEventNames(): iterable
    {
        yield 'container' => ['getContainer', ContaoKissEvents::STYLE_LAYOUT_CONTAINER];
        yield 'column' => ['getColumn', ContaoKissEvents::STYLE_LAYOUT_COLUMN];
        yield 'gap' => ['getGap', ContaoKissEvents::STYLE_LAYOUT_GAP];
        yield 'background' => ['getBackground', ContaoKissEvents::STYLE_COLOR_BACKGROUND];
        yield 'color' => ['getColor', ContaoKissEvents::STYLE_COLOR];
        yield 'any other option' => ['getMedia_layout', ContaoKissEvents::STYLE_DEFAULT];
    }

    public function testAListenerCanSwapTheOptionClass(): void
    {
        $dispatcher = new EventDispatcher();

        $dispatcher->addListener(ContaoKissEvents::STYLE_DEFAULT, static function (StyleOptionEvent $event): void {
            $event->setOptionClass(SwappedLayoutOption::class);
        });

        $option = new StylesVariable($dispatcher, true)->getMedia_layout('side');

        $this->assertInstanceOf(SwappedLayoutOption::class, $option);
        $this->assertSame('swapped-side', (string) $option);
    }

    #[DataProvider('provideUnresolvableOptionClasses')]
    public function testRejectsASwappedClassThatCannotBeResolved(string $class): void
    {
        $dispatcher = new EventDispatcher();

        $dispatcher->addListener(ContaoKissEvents::STYLE_DEFAULT, static function (StyleOptionEvent $event) use ($class): void {
            $event->setOptionClass($class);
        });

        $this->expectException(\LogicException::class);

        new StylesVariable($dispatcher, true)->getMedia_layout('side');
    }

    public static function provideUnresolvableOptionClasses(): iterable
    {
        yield 'class does not exist' => ['DigitaleDinge\\ContaoKiss\\Tests\\Fixtures\\Styles\\DoesNotExist'];
        yield 'class is not a style option' => [NotAStyleOption::class];
    }
}
