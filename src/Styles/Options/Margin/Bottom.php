<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Options\Margin;

use Contao\CoreBundle\Translation\TranslatableLabelInterface;
use DigitaleDinge\ContaoKiss\Styles\ClassOptionsInterface;
use Symfony\Component\Translation\TranslatableMessage;

enum Bottom: string implements ClassOptionsInterface, TranslatableLabelInterface
{
    case half = 'mb-line-1/2';
    case one = 'mb-line-1';
    case two = 'mb-line-2';
    case three = 'mb-line-3';
    case four = 'mb-line-4';
    case five = 'mb-line-5';

    public function label(): TranslatableMessage
    {
        return new TranslatableMessage('style_options.spacing_options.' . $this->name, [], 'style_options');
    }
}
