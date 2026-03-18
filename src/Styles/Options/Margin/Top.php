<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Options\Margin;

use Contao\CoreBundle\Translation\TranslatableLabelInterface;
use DigitaleDinge\ContaoKiss\Styles\ClassOptionsInterface;
use Symfony\Component\Translation\TranslatableMessage;

enum Top: string implements ClassOptionsInterface, TranslatableLabelInterface
{
    case half = 'mt-2';
    case one = 'mt-4';
    case two = 'mt-8';
    case three = 'mt-12';
    case four = 'mt-16';
    case five = 'mt-20';
    case six = 'mt-24';

    public function label(): TranslatableMessage
    {
        return new TranslatableMessage('style_options.spacing_option.'.$this->name, [], 'style_options');
    }
}
