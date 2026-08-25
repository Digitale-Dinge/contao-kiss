<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Option\Typography;

use Contao\CoreBundle\Translation\TranslatableLabelInterface;
use Symfony\Component\Translation\TranslatableMessage;

enum FontSize: string implements TranslatableLabelInterface
{
    case x_small = 'fixed-body5';
    case small = 'fixed-body4';
    case medium = 'fixed-body3';
    case large = 'fixed-body2';
    case x_large = 'fixed-body1';
    case xx_large = 'responsive-headline2';
    case xxx_large = 'responsive-headline1';

    public function label(): TranslatableMessage
    {
        return new TranslatableMessage('style_options.typography.size.'.$this->name, [], 'style_options');
    }
}
