<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Option\Component\Swiper;

use Contao\CoreBundle\Translation\TranslatableLabelInterface;
use Symfony\Component\Translation\TranslatableMessage;

enum Navigation: string implements TranslatableLabelInterface
{
    case overlay = 'overlay';
    case outside = 'outside';

    public function label(): TranslatableMessage
    {
        return new TranslatableMessage('style_options.component.swiper.navigation.'.$this->name, [], 'style_options');
    }
}
