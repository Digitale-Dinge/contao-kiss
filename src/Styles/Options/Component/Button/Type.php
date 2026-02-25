<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Options\Component\Button;

use Contao\CoreBundle\Translation\TranslatableLabelInterface;
use DigitaleDinge\ContaoKiss\Styles\ClassOptionsInterface;
use Symfony\Component\Translation\TranslatableMessage;

enum Type: string implements ClassOptionsInterface, TranslatableLabelInterface
{
    case soft = 'btn-soft';
    case outline = 'btn-outline';
    case text = 'btn-text';

    public function label(): TranslatableMessage
    {
        return new TranslatableMessage('style_options.component.button.type.'.$this->name, [], 'style_options');
    }
}
