<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Option\Color;

use Contao\CoreBundle\Translation\TranslatableLabelInterface;
use Symfony\Component\Translation\TranslatableMessage;

enum Color: string implements TranslatableLabelInterface
{
    case primary = 'primary';
    case secondary = 'secondary';
    case tertiary = 'tertiary';
    case success = 'success';
    case warning = 'warning';
    case error = 'error';

    public function label(): TranslatableMessage
    {
        return new TranslatableMessage('style_options.color.'.$this->name, [], 'style_options');
    }
}
