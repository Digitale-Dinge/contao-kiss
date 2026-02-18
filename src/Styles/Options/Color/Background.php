<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Options\Color;

use Contao\CoreBundle\Translation\TranslatableLabelInterface;
use DigitaleDinge\ContaoKiss\Styles\ClassOptionsInterface;
use Symfony\Component\Translation\TranslatableMessage;

enum Background: string implements ClassOptionsInterface, TranslatableLabelInterface
{
    case transparent = 'bg-transparent';
    case base_100 = 'bg-base-100';
    case base_200 = 'bg-base-200';
    case base_300 = 'bg-base-300';
    case primary = 'bg-primary';
    case secondary = 'bg-secondary';
    case accent = 'bg-accent';
    case info = 'bg-info';
    case success = 'bg-success';
    case warning = 'bg-warning';
    case error = 'bg-error';

    public function label(): TranslatableMessage
    {
        return new TranslatableMessage('style_options.color.'.$this->name, [], 'style_options');
    }
}
