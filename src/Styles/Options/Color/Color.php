<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Options\Color;

use Contao\CoreBundle\Translation\TranslatableLabelInterface;
use DigitaleDinge\ContaoKiss\Styles\ClassOptionsInterface;
use Symfony\Component\Translation\TranslatableMessage;

enum Color: string implements ClassOptionsInterface, TranslatableLabelInterface
{
    case primary = 'primary';
    case secondary = 'secondary';
    case accent = 'accent';
    case info = 'info';
    case success = 'success';
    case warning = 'warning';
    case error = 'error';
    /*
    case base_100 = 'base-100';
    case base_200 = 'base-200';
    case base_300 = 'base-300';
    */

    public function label(): TranslatableMessage
    {
        return new TranslatableMessage('style_options.color.'.$this->name, [], 'style_options');
    }
}
