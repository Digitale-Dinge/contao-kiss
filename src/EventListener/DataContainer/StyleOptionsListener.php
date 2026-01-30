<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\EventListener\DataContainer;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use DigitaleDinge\ContaoKiss\EventListener\TranslatableEnumTrait;
use DigitaleDinge\ContaoKiss\Styles\Options\Color\Background;
use DigitaleDinge\ContaoKiss\Styles\Options\Layout\Column;
use DigitaleDinge\ContaoKiss\Styles\Options\Layout\Container;
use DigitaleDinge\ContaoKiss\Styles\Options\Margin;
use DigitaleDinge\ContaoKiss\Styles\Options\Padding\Bottom;
use DigitaleDinge\ContaoKiss\Styles\Options\Padding\Top;
use DigitaleDinge\ContaoKiss\Styles\Options\Typography\Heading;
use DigitaleDinge\ContaoKiss\Styles\Options\Typography\Size;
use Symfony\Contracts\Translation\TranslatorInterface;

final readonly class StyleOptionsListener
{
    use TranslatableEnumTrait;

    public function __construct(
        private TranslatorInterface $translator,
    ) {
    }

    #[AsCallback('tl_content', 'fields.headline.fields.appearance.options')]
    #[AsCallback('tl_module', 'fields.headline.fields.appearance.options')]
    public function addHeadlineAppearanceOptions(): array
    {
        return [
            $this->translator->trans('style_options.size', [], 'style_options') => $this->getTranslatedOptions(Size::class),
            $this->translator->trans('style_options.heading', [], 'style_options') => $this->getTranslatedOptions(Heading::class),
        ];
    }

    #[AsCallback('tl_article', 'fields.backgroundColor.options')]
    public function addColorBackgroundOptions(): array
    {
        return $this->getTranslatedOptions(Background::class);
    }

    #[AsCallback('tl_form_field', 'fields.gridColumns.options')]
    public function addLayoutColumnOptions(): array
    {
        return $this->getTranslatedOptions(Column::class);
    }

    #[AsCallback('tl_content', 'fields.contentWidth.options')]
    #[AsCallback('tl_article', 'fields.contentWidth.options')]
    public function addLayoutContainerOptions(): array
    {
        return $this->getTranslatedOptions(Container::class);
    }

    #[AsCallback('tl_content', 'fields.paddingTop.options')]
    #[AsCallback('tl_article', 'fields.paddingTop.options')]
    public function addPaddingTopOptions(): array
    {
        return $this->getTranslatedOptions(Top::class);
    }

    #[AsCallback('tl_content', 'fields.paddingBottom.options')]
    #[AsCallback('tl_article', 'fields.paddingBottom.options')]
    public function addPaddingBottomOptions(): array
    {
        return $this->getTranslatedOptions(Bottom::class);
    }

    #[AsCallback('tl_content', 'fields.marginTop.options')]
    public function addMarginTopOptions(): array
    {
        return $this->getTranslatedOptions(Margin\Top::class);
    }

    #[AsCallback('tl_content', 'fields.marginBottom.options')]
    public function addMarginBottomOptions(): array
    {
        return $this->getTranslatedOptions(Margin\Bottom::class);
    }
}
