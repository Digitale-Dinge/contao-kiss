<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Options\Padding;

use Contao\CoreBundle\Translation\TranslatableLabelInterface;
use DigitaleDinge\ContaoKiss\Styles\ClassOptionsInterface;
use Symfony\Component\Translation\TranslatableMessage;

enum Bottom: string implements ClassOptionsInterface, TranslatableLabelInterface
{
    case half = 'pb-[0.5rem]';
    case one = 'pb-[1rem]';
    case two = 'pb-[2rem]';
    case three = 'pb-[3rem]';
    case four = 'pb-[4rem]';
    case five = 'pb-[5rem]';

    public function label(): TranslatableMessage
    {
        return new TranslatableMessage('style_options.spacing_option.' . $this->name, [], 'style_options');
    }
}
