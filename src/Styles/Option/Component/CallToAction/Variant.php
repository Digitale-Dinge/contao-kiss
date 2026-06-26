<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Option\Component\CallToAction;

use Contao\CoreBundle\Translation\TranslatableLabelInterface;
use Symfony\Component\Translation\TranslatableMessage;

enum Variant: string implements TranslatableLabelInterface
{
    case soft = 'btn-soft';
    case outline = 'btn-outline';
    case text = 'btn-text';
    case link = 'btn-link';

    public function label(): TranslatableMessage
    {
        return new TranslatableMessage('style_options.component.button.variant.'.$this->name, [], 'style_options');
    }
}
