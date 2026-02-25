<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Options\Component\Button;

use Contao\CoreBundle\Translation\TranslatableLabelInterface;
use DigitaleDinge\ContaoKiss\Styles\ClassOptionsInterface;
use Symfony\Component\Translation\TranslatableMessage;

enum Color: string implements ClassOptionsInterface, TranslatableLabelInterface
{
    case primary = 'btn-primary';
    case secondary = 'btn-secondary';
    case accent = 'btn-accent';
    case info = 'btn-info';
    case success = 'btn-success';
    case warning = 'btn-warning';
    case error = 'btn-error';

    public function label(): TranslatableMessage
    {
        return new TranslatableMessage('style_options.component.button.color.'.$this->name, [], 'style_options');
    }
}
