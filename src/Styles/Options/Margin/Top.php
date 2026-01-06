<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Options\Margin;

use Contao\CoreBundle\Translation\TranslatableLabelInterface;
use DigitaleDinge\ContaoKiss\Styles\ClassOptionsInterface;
use Symfony\Component\Translation\TranslatableMessage;

enum Top: string implements ClassOptionsInterface, TranslatableLabelInterface
{
    case half = 'mt-line-1/2';
    case one = 'mt-line-1';
    case two = 'mt-line-2';
    case three = 'mt-line-3';
    case four = 'mt-line-4';
    case five = 'mt-line-5';

    public function label(): TranslatableMessage
    {
        return new TranslatableMessage('style_options.spacing_options.' . $this->name, [], 'style_options');
    }
}
