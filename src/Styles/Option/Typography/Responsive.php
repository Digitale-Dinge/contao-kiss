<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Option\Typography;

use Contao\CoreBundle\Translation\TranslatableLabelInterface;
use Symfony\Component\Translation\TranslatableMessage;

enum Responsive: string implements TranslatableLabelInterface
{
    case display_one = 'responsive-display1';
    case display_two = 'responsive-display2';
    case display_three = 'responsive-display3';
    case headline_one = 'responsive-headline1';
    case headline_two = 'responsive-headline2';
    case headline_three = 'responsive-headline3';
    case body_one = 'responsive-body1';
    case body_two = 'responsive-body2';
    case body_three = 'responsive-body3';

    public function label(): TranslatableMessage
    {
        return new TranslatableMessage('style_options.typography.responsive.'.$this->name, [], 'style_options');
    }
}
