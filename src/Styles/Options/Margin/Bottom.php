<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Options\Margin;

use Contao\CoreBundle\Translation\TranslatableLabelInterface;
use DigitaleDinge\ContaoKiss\Styles\ClassOptionsInterface;
use Symfony\Component\Translation\TranslatableMessage;

enum Bottom: string implements ClassOptionsInterface, TranslatableLabelInterface
{
    case half = 'mb-2';
    case one = 'mb-4';
    case two = 'mb-8';
    case three = 'mb-12';
    case four = 'mb-16';
    case five = 'mb-20';

    public function label(): TranslatableMessage
    {
        return new TranslatableMessage('style_options.spacing_option.' . $this->name, [], 'style_options');
    }
}
