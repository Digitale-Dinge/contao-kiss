<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Options\Typography;

use Contao\CoreBundle\Translation\TranslatableLabelInterface;
use DigitaleDinge\ContaoKiss\Styles\ClassOptionsInterface;
use Symfony\Component\Translation\TranslatableMessage;

enum Size: string implements ClassOptionsInterface, TranslatableLabelInterface
{
    case x_small = 'text-xs';
    case small = 'text-sm';
    case medium = 'text-base';
    case large = 'text-lg';
    case x_large = 'text-xl';
    case xx_large = 'text-2xl';
    case xxx_large = 'text-3xl';

    public function label(): TranslatableMessage
    {
        return new TranslatableMessage('style_options.typography_size_options.' . $this->name, [], 'style_options');
    }
}
