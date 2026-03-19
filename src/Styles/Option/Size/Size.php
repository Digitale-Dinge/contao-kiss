<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Option\Size;

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
        return new TranslatableMessage('style_options.size_option.'.$this->name, [], 'style_options');
    }
}
