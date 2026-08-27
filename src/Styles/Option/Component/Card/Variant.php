<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Option\Component\Card;

use Contao\CoreBundle\Translation\TranslatableLabelInterface;
use Symfony\Component\Translation\TranslatableMessage;

enum Variant: string implements TranslatableLabelInterface
{
    case soft = 'card-soft';
    case outline = 'card-outline';
    case glass = 'card-glass';

    public function label(): TranslatableMessage
    {
        return new TranslatableMessage('style_options.component.card.variant.'.$this->name, [], 'style_options');
    }
}
