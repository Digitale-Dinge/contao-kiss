<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Options\Padding;

use Contao\CoreBundle\Translation\TranslatableLabelInterface;
use DigitaleDinge\ContaoKiss\Styles\ClassOptionsInterface;
use Symfony\Component\Translation\TranslatableMessage;

enum Bottom: string implements ClassOptionsInterface, TranslatableLabelInterface
{
    case half = 'pb-2';
    case one = 'pb-4';
    case two = 'pb-8';
    case three = 'pb-12';
    case four = 'pb-16';
    case five = 'pb-20';

    public function label(): TranslatableMessage
    {
        return new TranslatableMessage('style_options.spacing_option.'.$this->name, [], 'style_options');
    }
}
