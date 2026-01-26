<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Options\Margin;

use Contao\CoreBundle\Translation\TranslatableLabelInterface;
use DigitaleDinge\ContaoKiss\Styles\ClassOptionsInterface;
use Symfony\Component\Translation\TranslatableMessage;

enum Top: string implements ClassOptionsInterface, TranslatableLabelInterface
{
    case half = 'mt-[0.5rem]';
    case one = 'mt-[1rem]';
    case two = 'mt-[2rem]';
    case three = 'mt-[3rem]';
    case four = 'mt-[4rem]';
    case five = 'mt-[5rem]';

    public function label(): TranslatableMessage
    {
        return new TranslatableMessage('style_options.spacing_option.' . $this->name, [], 'style_options');
    }
}
