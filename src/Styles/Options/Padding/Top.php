<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Options\Padding;

use Contao\CoreBundle\Translation\TranslatableLabelInterface;
use DigitaleDinge\ContaoKiss\Styles\ClassOptionsInterface;
use Symfony\Component\Translation\TranslatableMessage;

enum Top: string implements ClassOptionsInterface, TranslatableLabelInterface
{
    case half = 'pt-line-1/2';
    case one = 'pt-line-1';
    case two = 'pt-line-2';
    case three = 'pt-line-3';
    case four = 'pt-line-4';
    case five = 'pt-line-5';

    public function label(): TranslatableMessage
    {
        return new TranslatableMessage('style_options.spacing_options.' . $this->name, [], 'style_options');
    }
}
