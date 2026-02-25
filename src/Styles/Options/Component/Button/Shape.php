<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Options\Component\Button;

use Contao\CoreBundle\Translation\TranslatableLabelInterface;
use DigitaleDinge\ContaoKiss\Styles\ClassOptionsInterface;
use Symfony\Component\Translation\TranslatableMessage;

enum Shape: string implements ClassOptionsInterface, TranslatableLabelInterface
{
    case wide = 'btn-wide';
    case block = 'btn-block';

    public function label(): TranslatableMessage
    {
        return new TranslatableMessage('style_options.component.button.shape.'.$this->name, [], 'style_options');
    }
}
