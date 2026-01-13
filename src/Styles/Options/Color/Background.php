<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Options\Color;

use Contao\CoreBundle\Translation\TranslatableLabelInterface;
use DigitaleDinge\ContaoKiss\Styles\ClassOptionsInterface;
use Symfony\Component\Translation\TranslatableMessage;

enum Background: string implements ClassOptionsInterface, TranslatableLabelInterface
{
    case transparent = 'transparent';
    case white = 'background-white';
    case primary = 'background-primary';
    case secondary = 'background-secondary';
    case additional_one = 'background-additional-1';
    case additional_two = 'background-additional-2';

    public function label(): TranslatableMessage
    {
        return new TranslatableMessage('style_options.color.background.' . $this->name, [], 'style_options');
    }
}
