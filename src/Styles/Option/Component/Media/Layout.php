<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Option\Component\Media;

use Contao\CoreBundle\Translation\TranslatableLabelInterface;
use Symfony\Component\Translation\TranslatableMessage;

enum Layout: string implements TranslatableLabelInterface
{
    case default = '';
    case reverse = 'reverse';
    case side = 'side';
    case side_reverse = 'side-reverse';
    case media_background = 'media-background';

    public function label(): TranslatableMessage
    {
        return new TranslatableMessage('style_options.component.media.layout.'.$this->name, [], 'style_options');
    }
}
