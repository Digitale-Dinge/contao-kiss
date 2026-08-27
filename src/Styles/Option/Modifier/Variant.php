<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Option\Modifier;

use Contao\CoreBundle\Translation\TranslatableLabelInterface;
use Symfony\Component\Translation\TranslatableMessage;

enum Variant: string implements TranslatableLabelInterface
{
    case soft = 'soft';
    case outline = 'outline';
    case glass = 'glass';

    public function label(): TranslatableMessage
    {
        return new TranslatableMessage('style_options.modifier.variant.'.$this->name, [], 'style_options');
    }
}
