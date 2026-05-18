<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Twig\Global;

use DigitaleDinge\ContaoKiss\Event\ContaoKissEvents;
use DigitaleDinge\ContaoKiss\Event\Styles\StyleOptionEvent;
use DigitaleDinge\ContaoKiss\Styles\Option\Color;
use DigitaleDinge\ContaoKiss\Styles\Option\Component;
use DigitaleDinge\ContaoKiss\Styles\Option\Layout;
use DigitaleDinge\ContaoKiss\Styles\Option\Margin;
use DigitaleDinge\ContaoKiss\Styles\Option\Padding;
use DigitaleDinge\ContaoKiss\Styles\Option\Size;
use DigitaleDinge\ContaoKiss\Styles\Option\StyleOption;
use DigitaleDinge\ContaoKiss\Styles\Option\Typography;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class StylesVariable
{
    public function __construct(
        private readonly EventDispatcherInterface $eventDispatcher,
        #[Autowire('%contao_kiss.style_definition_override%')]
        private readonly bool $enableStyleOverride,
    ) {}

    /**
     * Layout
     */
    public function getContainer(string|null $key = null): StyleOption|Layout\ContainerOption
    {
        return $this->getStyleOption(Layout\ContainerOption::class, $key);
    }

    public function getColumn(string|null $key = null): StyleOption|Layout\ColumnOption
    {
        return $this->getStyleOption(Layout\ColumnOption::class, $key);
    }

    public function getGap(string|null $key = null): StyleOption|Layout\GapOption
    {
        return $this->getStyleOption(Layout\GapOption::class, $key);
    }

    public function getSpan(string|null $key = null): StyleOption|Layout\ColumnSpanOption
    {
        return $this->getStyleOption(Layout\ColumnSpanOption::class, $key);
    }

    /**
     * Typography
     */
    public function getFont_size(string|null $key = null): StyleOption|Typography\FontSizeOption
    {
        return $this->getStyleOption(Typography\FontSizeOption::class, $key);
    }

    public function getHeading(string|null $key = null): StyleOption|Typography\HeadingOption
    {
        return $this->getStyleOption(Typography\HeadingOption::class, $key);
    }

    public function getText_alignment(string|null $key = null): StyleOption|Typography\AlignmentOption
    {
        return $this->getStyleOption(Typography\AlignmentOption::class, $key);
    }

    /**
     * Color
     */
    public function getBackground(string|null $key = null): StyleOption|Color\BackgroundOption
    {
        return $this->getStyleOption(Color\BackgroundOption::class, $key);
    }

    public function getColor(string|null $key = null): StyleOption|Color\ColorOption
    {
        return $this->getStyleOption(Color\ColorOption::class, $key);
    }

    /**
     * Margin
     */
    public function getMargin_top(string|null $key = null): StyleOption|Margin\TopOption
    {
        return $this->getStyleOption(Margin\TopOption::class, $key);
    }

    public function getMargin_bottom(string|null $key = null): StyleOption|Margin\BottomOption
    {
        return $this->getStyleOption(Margin\BottomOption::class, $key);
    }

    /**
     * Padding
     */
    public function getPadding_top(string|null $key = null): StyleOption|Padding\TopOption
    {
        return $this->getStyleOption(Padding\TopOption::class, $key);
    }

    public function getPadding_bottom(string|null $key = null): StyleOption|Padding\BottomOption
    {
        return $this->getStyleOption(Padding\BottomOption::class, $key);
    }

    public function getSize(string|null $key = null): StyleOption|Size\SizeOption
    {
        return $this->getStyleOption(Size\SizeOption::class, $key);
    }

    /**
     * Call to action design
     */
    public function getCta_shape(string|null $key = null): StyleOption|Component\CallToAction\ShapeOption
    {
        return $this->getStyleOption(Component\CallToAction\ShapeOption::class, $key);
    }

    public function getCta_type(string|null $key = null): StyleOption|Component\CallToAction\TypeOption
    {
        return $this->getStyleOption(Component\CallToAction\TypeOption::class, $key);
    }

    protected function getStyleOption(string $styleOption, string|null $key): StyleOption
    {
        if (!is_subclass_of($styleOption, StyleOption::class)) {
            throw new \LogicException(\sprintf('Invalid usage. Class "%s" must extend StyleOption.', $styleOption));
        }

        if (!$this->enableStyleOverride) {
            return new $styleOption($key);
        }

        $event = new StyleOptionEvent($styleOption, $key);

        $this->eventDispatcher->dispatch(
            $event,
            $this->getStyleEventForClass($styleOption)
        );

        return $event->getOption();
    }

    private function getStyleEventForClass(string $type): string
    {
        return match ($type) {
            Layout\ContainerOption::class => ContaoKissEvents::STYLE_LAYOUT_CONTAINER,
            Layout\ColumnOption::class    => ContaoKissEvents::STYLE_LAYOUT_COLUMN,
            Layout\GapOption::class       => ContaoKissEvents::STYLE_LAYOUT_GAP,
            Color\BackgroundOption::class => ContaoKissEvents::STYLE_COLOR_BACKGROUND,
            Color\ColorOption::class      => ContaoKissEvents::STYLE_COLOR,
            default                       => ContaoKissEvents::STYLE_DEFAULT,
        };
    }
}
