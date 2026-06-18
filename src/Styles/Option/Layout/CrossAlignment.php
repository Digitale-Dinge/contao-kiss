<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Option\Layout;

use Contao\CoreBundle\Translation\TranslatableLabelInterface;
use Symfony\Component\Translation\TranslatableMessage;

enum CrossAlignment: string implements TranslatableLabelInterface
{
    case start = 'items-start';
    case center = 'items-center';
    case end = 'items-end';

    public function label(): TranslatableMessage
    {
        return new TranslatableMessage('style_options.layout.alignment.y.'.$this->name, [], 'style_options');
    }
}
