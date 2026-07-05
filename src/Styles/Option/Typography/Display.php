<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Option\Typography;

use Contao\CoreBundle\Translation\TranslatableLabelInterface;
use Symfony\Component\Translation\TranslatableMessage;

enum Display: string implements TranslatableLabelInterface
{
    case display_one = 'responsive-display1';
    case display_two = 'responsive-display2';
    case display_three = 'responsive-display3';

    public function label(): TranslatableMessage
    {
        return new TranslatableMessage('style_options.typography.display.'.$this->name, [], 'style_options');
    }
}
