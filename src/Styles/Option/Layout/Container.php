<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Option\Layout;

use Contao\CoreBundle\Translation\TranslatableLabelInterface;
use Symfony\Component\Translation\TranslatableMessage;

enum Container: string implements TranslatableLabelInterface
{
    case small = 'container-small';
    case base = 'container-base';
    case narrow = 'container-narrow';
    case full_pad = 'container-full-pad';
    case full = 'container-full';

    public function label(): TranslatableMessage
    {
        return new TranslatableMessage('style_options.layout.container.'.$this->name, [], 'style_options');
    }
}
