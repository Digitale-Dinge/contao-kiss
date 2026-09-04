<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Option\Typography;

use Contao\CoreBundle\Translation\TranslatableLabelInterface;
use Symfony\Component\Translation\TranslatableMessage;

enum Responsive: string implements TranslatableLabelInterface
{
    case display_one = 'responsive-display-lg';
    case display_two = 'responsive-display-md';
    case display_three = 'responsive-display-sm';
    case headline_one = 'responsive-headline-lg';
    case headline_two = 'responsive-headline-md';
    case headline_three = 'responsive-headline-sm';
    case body_one = 'responsive-body-lg';
    case body_two = 'responsive-body-md';
    case body_three = 'responsive-body-sm';

    public function label(): TranslatableMessage
    {
        return new TranslatableMessage('style_options.typography.responsive.'.$this->name, [], 'style_options');
    }
}
