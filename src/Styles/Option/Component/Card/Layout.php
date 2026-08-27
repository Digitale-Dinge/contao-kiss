<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Option\Component\Card;

use Contao\CoreBundle\Translation\TranslatableLabelInterface;
use Symfony\Component\Translation\TranslatableMessage;

enum Layout: string implements TranslatableLabelInterface
{
    case reverse = 'card-reverse';
    case side = 'card-side';
    case side_reverse = 'card-side-reverse';
    case media_full = 'card-media-full';

    public function label(): TranslatableMessage
    {
        return new TranslatableMessage('style_options.component.card.layout.'.$this->name, [], 'style_options');
    }
}
