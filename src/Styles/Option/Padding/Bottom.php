<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Option\Padding;

use Contao\CoreBundle\Translation\TranslatableLabelInterface;
use Symfony\Component\Translation\TranslatableMessage;

enum Bottom: string implements TranslatableLabelInterface
{
    case half = 'pb-2';
    case one = 'pb-4';
    case two = 'pb-8';
    case three = 'pb-12';
    case four = 'pb-16';
    case five = 'pb-20';
    case six = 'pb-24';

    public function label(): TranslatableMessage
    {
        return new TranslatableMessage('style_options.spacing_option.'.$this->name, [], 'style_options');
    }
}
