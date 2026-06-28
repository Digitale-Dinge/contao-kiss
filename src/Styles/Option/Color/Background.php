<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Option\Color;

use Contao\CoreBundle\Translation\TranslatableLabelInterface;
use Symfony\Component\Translation\TranslatableMessage;

enum Background: string implements TranslatableLabelInterface
{
    case transparent = 'bg-transparent';
    case base_100 = 'bg-base-100';
    case base_200 = 'bg-base-200';
    case base_300 = 'bg-base-300';
    case primary = 'bg-primary-solid';
    case secondary = 'bg-secondary-solid';
    case accent = 'bg-tertiary-solid'; // ToDo: Migrate accent option
    //case quaternary = 'bg-quaternary-solid';
    case success = 'bg-status-success-solid';
    case warning = 'bg-status-warning-solid';
    case error = 'bg-status-error-solid';
    case base_content = 'bg-base-content';

    public function label(): TranslatableMessage
    {
        return new TranslatableMessage('style_options.color.'.$this->name, [], 'style_options');
    }
}
