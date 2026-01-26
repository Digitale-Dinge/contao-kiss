<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Options\Padding;

use Contao\CoreBundle\Translation\TranslatableLabelInterface;
use DigitaleDinge\ContaoKiss\Styles\ClassOptionsInterface;
use Symfony\Component\Translation\TranslatableMessage;

enum Top: string implements ClassOptionsInterface, TranslatableLabelInterface
{
    case half = 'pt-[0.5rem]';
    case one = 'pt-[1rem]';
    case two = 'pt-[2rem]';
    case three = 'pt-[3rem]';
    case four = 'pt-[4rem]';
    case five = 'pt-[5rem]';

    public function label(): TranslatableMessage
    {
        return new TranslatableMessage('style_options.spacing_option.' . $this->name, [], 'style_options');
    }
}
