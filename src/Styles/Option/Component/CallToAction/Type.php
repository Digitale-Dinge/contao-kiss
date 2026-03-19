<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Option\Component\CallToAction;

use Contao\CoreBundle\Translation\TranslatableLabelInterface;
use Symfony\Component\Translation\TranslatableMessage;

enum Type: string implements TranslatableLabelInterface
{
    case soft = 'btn-soft';
    case outline = 'btn-outline';
    case text = 'btn-text';

    public function label(): TranslatableMessage
    {
        return new TranslatableMessage('style_options.component.button.type.'.$this->name, [], 'style_options');
    }
}
