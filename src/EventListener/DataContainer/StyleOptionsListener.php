<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\EventListener\DataContainer;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;

use DigitaleDinge\ContaoKiss\EventListener\TranslatableEnumTrait;
use DigitaleDinge\ContaoKiss\Styles\Options\Color;
use DigitaleDinge\ContaoKiss\Styles\Options\Layout;
use DigitaleDinge\ContaoKiss\Styles\Options\Margin;
use DigitaleDinge\ContaoKiss\Styles\Options\Padding;
use DigitaleDinge\ContaoKiss\Styles\Options\Typography;

final class StyleOptionsListener
{
    use TranslatableEnumTrait;

    #[AsCallback('tl_content', 'fields.headline.fields.size.options')]
    #[AsCallback('tl_module', 'fields.headline.fields.size.options')]
    public function addHeadlineSizeOptions(): array
    {
        return $this->getTranslatedOptions(Typography\Size::class);
    }

    #[AsCallback('tl_article', 'fields.backgroundColor.options')]
    public function addColorBackgroundOptions(): array
    {
        return $this->getTranslatedOptions(Color\Background::class);
    }

    #[AsCallback('tl_form_field', 'fields.gridColumns.options')]
    public function addLayoutColumnOptions(): array
    {
        return $this->getTranslatedOptions(Layout\Column::class);
    }

    #[AsCallback('tl_content', 'fields.contentWidth.options')]
    #[AsCallback('tl_article', 'fields.contentWidth.options')]
    public function addLayoutContainerOptions(): array
    {
        return $this->getTranslatedOptions(Layout\Container::class);
    }

    #[AsCallback('tl_content', 'fields.paddingTop.options')]
    #[AsCallback('tl_article', 'fields.paddingTop.options')]
    public function addPaddingTopOptions(): array
    {
        return $this->getTranslatedOptions(Padding\Top::class);
    }

    #[AsCallback('tl_content', 'fields.paddingBottom.options')]
    #[AsCallback('tl_article', 'fields.paddingBottom.options')]
    public function addPaddingBottomOptions(): array
    {
        return $this->getTranslatedOptions(Padding\Bottom::class);
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
