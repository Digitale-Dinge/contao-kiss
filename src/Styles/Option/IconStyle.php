<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Option;

use Contao\CoreBundle\Translation\TranslatableLabelInterface;
use Symfony\Component\Translation\TranslatableMessage;

enum IconStyle: string implements TranslatableLabelInterface
{
    case default = 'default';
    case badge = 'badge';

    public function label(): TranslatableMessage
    {
        return new TranslatableMessage('style_options.icon_style.'.$this->name, [], 'style_options');
    }
}
