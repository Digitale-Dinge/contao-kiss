<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\EventListener\DataContainer;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;

use DigitaleDinge\ContaoKiss\EventListener\TranslatableEnumTrait;
use DigitaleDinge\ContaoKiss\Styles\Options\Color;
use DigitaleDinge\ContaoKiss\Styles\Options\Component;
use DigitaleDinge\ContaoKiss\Styles\Options\Layout;
use DigitaleDinge\ContaoKiss\Styles\Options\Margin;
use DigitaleDinge\ContaoKiss\Styles\Options\Padding;
use DigitaleDinge\ContaoKiss\Styles\Options\Size;
use DigitaleDinge\ContaoKiss\Styles\Options\Typography;
use Symfony\Contracts\Translation\TranslatorInterface;

final class StyleOptionsListener
{
    use TranslatableEnumTrait;

    public function __construct(private readonly TranslatorInterface $translator)
    {}

    #[AsCallback('tl_content', 'fields.headline.fields.appearance.options')]
    #[AsCallback('tl_content', 'fields.textAppearance.options')]
    #[AsCallback('tl_module', 'fields.headline.fields.appearance.options')]
    public function addHeadlineAppearanceOptions(): array
    {
        return [
            $this->translator->trans('style_options.size', [], 'style_options') => $this->getTranslatedOptions(Typography\Size::class),
            $this->translator->trans('style_options.heading', [], 'style_options') => $this->getTranslatedOptions(Typography\Heading::class),
        ];
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

    #[AsCallback('tl_content', 'fields.callToAction.fields.type.options')]
    public function addCtaTypeOptions(): array
    {
        return $this->getTranslatedOptions(Component\Button\Type::class);
    }

    #[AsCallback('tl_content', 'fields.callToAction.fields.color.options')]
    public function addColorOptions(): array
    {
        return $this->getTranslatedOptions(Color\Color::class);
    }

    #[AsCallback('tl_content', 'fields.callToAction.fields.size.options')]
    public function addSizeOptions(): array
    {
        return $this->getTranslatedOptions(Size\Size::class);
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

    #[AsCallback('tl_content', 'fields.buttonStyle.options')]
    public function addButtonStyleOptions(): array
    {
        return $this->getTranslatedOptions(Component\Button\Type::class);
    }

    #[AsCallback('tl_content', 'fields.buttonColor.options')]
    public function addButtonColorOptions(): array
    {
        return $this->getTranslatedOptions(Component\Button\Color::class);
    }

    #[AsCallback('tl_content', 'fields.buttonSize.options')]
    public function addButtonSizeOptions(): array
    {
        return $this->getTranslatedOptions(Component\Button\Size::class);
    }

    #[AsCallback('tl_content', 'fields.buttonShape.options')]
    public function addButtonShapeOptions(): array
    {
        return $this->getTranslatedOptions(Component\Button\Shape::class);
    }
}
