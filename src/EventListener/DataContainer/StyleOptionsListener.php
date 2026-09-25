<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\EventListener\DataContainer;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\DataContainer;
use DigitaleDinge\ContaoKiss\EventListener\TranslatableEnumTrait;
use DigitaleDinge\ContaoKiss\Styles\Option\Color;
use DigitaleDinge\ContaoKiss\Styles\Option\Component;
use DigitaleDinge\ContaoKiss\Styles\Option\Layout;
use DigitaleDinge\ContaoKiss\Styles\Option\Margin;
use DigitaleDinge\ContaoKiss\Styles\Option\Modifier;
use DigitaleDinge\ContaoKiss\Styles\Option\Padding;
use DigitaleDinge\ContaoKiss\Styles\Option\Typography;
use DigitaleDinge\ContaoKiss\Styles\StyleOptionRegistry;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @internal
 */
final class StyleOptionsListener
{
    use TranslatableEnumTrait;

    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly StyleOptionRegistry $registry,
    ) {
    }

    #[AsCallback('tl_content', 'fields.headline.fields.appearance.options')]
    #[AsCallback('tl_module', 'fields.headline.fields.appearance.options')]
    public function addHeadlineAppearanceOptions(): array
    {
        return $this->getGroupedOptions('appearance');
    }

    #[AsCallback('tl_content', 'fields.textAppearance.options')]
    #[AsCallback('tl_form_field', 'fields.textAppearance.options')]
    public function addTextAppearanceOptions(): array
    {
        return $this->getGroupedOptions('appearance');
    }

    #[AsCallback('tl_content', 'fields.backgroundColor.options')]
    #[AsCallback('tl_article', 'fields.backgroundColor.options')]
    public function addColorBackgroundOptions(): array
    {
        return $this->getTranslatedOptions($this->registry->getEnum(Color\Background::class));
    }

    #[AsCallback('tl_content', 'fields.textAlignment.options')]
    #[AsCallback('tl_article', 'fields.textAlignment.options')]
    #[AsCallback('tl_form_field', 'fields.textAlignment.options')]
    public function addTextAlignmentOptions(): array
    {
        return $this->getTranslatedOptions($this->registry->getEnum(Typography\Alignment::class));
    }

    #[AsCallback('tl_content', 'fields.gridColumns.options')]
    #[AsCallback('tl_module', 'fields.gridColumns.options')]
    public function addLayoutColumnOptions(): array
    {
        return $this->getTranslatedOptions($this->registry->getEnum(Layout\Column::class));
    }

    #[AsCallback('tl_form_field', 'fields.gridSpan.options')]
    public function addLayoutGridSpanOptions(): array
    {
        return $this->getTranslatedOptions($this->registry->getEnum(Layout\ColumnSpan::class));
    }

    #[AsCallback('tl_content', 'fields.gridGap.options')]
    #[AsCallback('tl_module', 'fields.gridGap.options')]
    public function addLayoutGapOptions(): array
    {
        return $this->getTranslatedOptions($this->registry->getEnum(Layout\Gap::class));
    }

    #[AsCallback('tl_content', 'fields.gridCrossAlignment.options')]
    public function addLayoutCrossAlignmentOptions(): array
    {
        return $this->getTranslatedOptions($this->registry->getEnum(Layout\CrossAlignment::class));
    }

    #[AsCallback('tl_content', 'fields.contentWidth.options')]
    #[AsCallback('tl_article', 'fields.contentWidth.options')]
    public function addLayoutContainerOptions(): array
    {
        return $this->getTranslatedOptions($this->registry->getEnum(Layout\Container::class));
    }

    #[AsCallback('tl_content', 'fields.paddingTop.options')]
    #[AsCallback('tl_article', 'fields.paddingTop.options')]
    public function addPaddingTopOptions(): array
    {
        return $this->getTranslatedOptions($this->registry->getEnum(Padding\Top::class));
    }

    #[AsCallback('tl_content', 'fields.paddingBottom.options')]
    #[AsCallback('tl_article', 'fields.paddingBottom.options')]
    public function addPaddingBottomOptions(): array
    {
        return $this->getTranslatedOptions($this->registry->getEnum(Padding\Bottom::class));
    }

    #[AsCallback('tl_content', 'fields.marginTop.options')]
    public function addMarginTopOptions(): array
    {
        return $this->getTranslatedOptions($this->registry->getEnum(Margin\Top::class));
    }

    #[AsCallback('tl_content', 'fields.marginBottom.options')]
    public function addMarginBottomOptions(): array
    {
        return $this->getTranslatedOptions($this->registry->getEnum(Margin\Bottom::class));
    }

    #[AsCallback('tl_content', 'fields.ctaType.options')]
    #[AsCallback('tl_content', 'fields.callToAction.fields.type.options')]
    public function addCtaTypeOptions(): array
    {
        return $this->getTranslatedOptions($this->registry->getEnum(Component\CallToAction\Variant::class));
    }

    #[AsCallback('tl_content', 'fields.ctaShape.options')]
    #[AsCallback('tl_form_field', 'fields.fieldShape.options')]
    public function addCtaShapeOptions(): array
    {
        return $this->getTranslatedOptions($this->registry->getEnum(Component\CallToAction\Shape::class));
    }

    #[AsCallback('tl_content', 'fields.ctaColor.options')]
    #[AsCallback('tl_content', 'fields.callToAction.fields.color.options')]
    #[AsCallback('tl_form_field', 'fields.fieldColor.options')]
    public function addColorOptions(): array
    {
        return $this->getTranslatedOptions($this->registry->getEnum(Color\Color::class));
    }

    #[AsCallback('tl_content', 'fields.ctaSize.options')]
    #[AsCallback('tl_content', 'fields.elementSize.options')]
    #[AsCallback('tl_content', 'fields.callToAction.fields.size.options')]
    #[AsCallback('tl_form_field', 'fields.fieldSize.options')]
    public function addSizeOptions(DataContainer $dc): array
    {
        return $this->getTranslatedOptions($this->registry->getEnum(Modifier\Size::class));
    }

    #[AsCallback('tl_content', 'fields.elementLayout.options')]
    public function addElementLayoutOptions(): array
    {
        return $this->getTranslatedOptions($this->registry->getEnum(Component\Media\Layout::class));
    }

    #[AsCallback('tl_content', 'fields.sliderNavigation.options')]
    public function addSliderNavigationOptions(): array
    {
        return $this->getTranslatedOptions($this->registry->getEnum(Component\Swiper\Navigation::class));
    }

    #[AsCallback('tl_content', 'fields.elementVariant.options')]
    #[AsCallback('tl_form_field', 'fields.fieldVariant.options')]
    public function addVariantOptions(DataContainer $dc): array
    {
        $type = $dc->getCurrentRecord()['type'] ?? null;

        if ('submit' === $type) {
            return $this->getTranslatedOptions($this->registry->getEnum(Component\CallToAction\Variant::class));
        }

        return $this->getTranslatedOptions($this->registry->getEnum(Modifier\Variant::class));
    }

    /**
     * @return array<string, array<string, string>>
     */
    private function getGroupedOptions(string $group): array
    {
        $options = [];

        foreach ($this->registry->getGroup($group) as $name) {
            $label = $this->translator->trans($this->registry->getLabel($name), [], 'style_options');
            $options[$label] = $this->getTranslatedOptions($this->registry->getEnum($name));
        }

        return $options;
    }
}
