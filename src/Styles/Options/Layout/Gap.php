<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Options\Layout;

use Contao\CoreBundle\Translation\TranslatableLabelInterface;
use DigitaleDinge\ContaoKiss\Styles\ClassOptionsInterface;
use Symfony\Component\Translation\TranslatableMessage;

enum Gap: string implements ClassOptionsInterface, TranslatableLabelInterface
{
    case x_small = 'gap-2';
    case small = 'gap-4';
    case medium = 'gap-6';
    case large = 'gap-8';
    case x_large = 'gap-10';
    case xx_large = 'gap-20';

    public function label(): TranslatableMessage
    {
        return new TranslatableMessage('style_options.layout.gap.'.$this->name, [], 'style_options');
    }
}
