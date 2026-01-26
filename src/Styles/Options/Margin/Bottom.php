<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Options\Margin;

use Contao\CoreBundle\Translation\TranslatableLabelInterface;
use DigitaleDinge\ContaoKiss\Styles\ClassOptionsInterface;
use Symfony\Component\Translation\TranslatableMessage;

enum Bottom: string implements ClassOptionsInterface, TranslatableLabelInterface
{
    case half = 'mb-[0.5rem]';
    case one = 'mb-[1rem]';
    case two = 'mb-[2rem]';
    case three = 'mb-[3rem]';
    case four = 'mb-[4rem]';
    case five = 'mb-[5rem]';

    public function label(): TranslatableMessage
    {
        return new TranslatableMessage('style_options.spacing_option.' . $this->name, [], 'style_options');
    }
}
