<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Option\Typography;

use Contao\CoreBundle\Translation\TranslatableLabelInterface;
use Symfony\Component\Translation\TranslatableMessage;

/**
 * @deprecated No longer offered in any backend dropdown.
 */
enum FontSize: string implements TranslatableLabelInterface
{
    case x_small = 'text-xs';
    case small = 'text-sm';
    case medium = 'text-base';
    case large = 'md:text-lg';
    case x_large = 'text-lg md:text-xl';
    case xx_large = 'text-xl md:text-2xl';
    case xxx_large = 'text-2xl md:text-3xl';

    public function label(): TranslatableMessage
    {
        return new TranslatableMessage('style_options.typography.size.'.$this->name, [], 'style_options');
    }
}
