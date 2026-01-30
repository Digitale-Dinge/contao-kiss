<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Options\Padding;

use Contao\CoreBundle\Translation\TranslatableLabelInterface;
use DigitaleDinge\ContaoKiss\Styles\ClassOptionsInterface;
use Symfony\Component\Translation\TranslatableMessage;

enum Top: string implements ClassOptionsInterface, TranslatableLabelInterface
{
    case half = 'pt-2';
    case one = 'pt-4';
    case two = 'pt-8';
    case three = 'pt-12';
    case four = 'pt-16';
    case five = 'pt-20';

    public function label(): TranslatableMessage
    {
        return new TranslatableMessage('style_options.spacing_option.'.$this->name, [], 'style_options');
    }
}
