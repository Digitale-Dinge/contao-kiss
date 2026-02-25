<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Options\Component\Button;

use Contao\CoreBundle\Translation\TranslatableLabelInterface;
use DigitaleDinge\ContaoKiss\Styles\ClassOptionsInterface;
use Symfony\Component\Translation\TranslatableMessage;

enum Size: string implements ClassOptionsInterface, TranslatableLabelInterface
{
    case x_small = 'btn-xs';
    case small = 'btn-sm';
    case large = 'btn-lg';
    case x_large = 'btn-xl';

    public function label(): TranslatableMessage
    {
        return new TranslatableMessage('style_options.component.button.size.'.$this->name, [], 'style_options');
    }
}
