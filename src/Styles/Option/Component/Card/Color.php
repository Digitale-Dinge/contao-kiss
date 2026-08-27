<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Option\Component\Card;

use Contao\CoreBundle\Translation\TranslatableLabelInterface;
use Symfony\Component\Translation\TranslatableMessage;

/**
 * Resolves the same stored keys as Color\Background, but to semantic card
 * color classes when an element is shown as a card. Keys without a semantic
 * card color keep their background class, so the card falls back to its
 * default styling.
 */
enum Color: string implements TranslatableLabelInterface
{
    case transparent = 'bg-transparent';
    case base_100 = 'bg-base-100';
    case base_200 = 'bg-base-200';
    case base_300 = 'bg-base-300';
    case primary = 'card-primary';
    case secondary = 'card-secondary';
    case accent = 'card-accent'; // ToDo: Migrate accent option
    case info = 'card-info';
    case success = 'card-success';
    case warning = 'card-warning';
    case error = 'card-error';
    case base_content = 'bg-base-content';

    public function label(): TranslatableMessage
    {
        return new TranslatableMessage('style_options.color.'.$this->name, [], 'style_options');
    }
}
