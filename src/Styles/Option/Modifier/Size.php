<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Option\Modifier;

use Contao\CoreBundle\Translation\TranslatableLabelInterface;
use Symfony\Component\Translation\TranslatableMessage;

enum Size: string implements TranslatableLabelInterface
{
    case x_small = 'xs';
    case small = 'sm';
    case large = 'lg';
    case x_large = 'xl';

    public function label(): TranslatableMessage
    {
        return new TranslatableMessage('style_options.modifier.size.'.$this->name, [], 'style_options');
    }
}
