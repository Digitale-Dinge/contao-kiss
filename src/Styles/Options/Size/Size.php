<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Options\Size;

use Contao\CoreBundle\Translation\TranslatableLabelInterface;
use DigitaleDinge\ContaoKiss\Styles\ClassOptionsInterface;
use Symfony\Component\Translation\TranslatableMessage;

enum Size: string implements ClassOptionsInterface, TranslatableLabelInterface
{
    case x_small = 'xs';
    case small = 'sm';
    case large = 'lg';
    case x_large = 'xl';

    public function label(): TranslatableMessage
    {
        return new TranslatableMessage('style_options.size_option.'.$this->name, [], 'style_options');
    }
}
