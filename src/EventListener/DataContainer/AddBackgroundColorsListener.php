<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\EventListener\DataContainer;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use DigitaleDinge\ContaoKiss\Styles\Options\Color\Background;
use Symfony\Contracts\Translation\TranslatorInterface;

final readonly class AddBackgroundColorsListener
{
    public function __construct(private TranslatorInterface $translator)
    {}

    #[AsCallback('tl_article', 'fields.backgroundColor.options')]
    #[AsCallback('tl_content', 'fields.backgroundColor.options')]
    public function addBackgroundOptions(): array
    {
        $options = [];

        // ToDO: Add settings in the future
        foreach (array_column(Background::cases(), 'value') as $color) {
            $options[$color] = $this->translator->trans('tl_article.backgroundColorOptions.' . $color, [], 'contao_default');
        }

        return $options;
    }
}
