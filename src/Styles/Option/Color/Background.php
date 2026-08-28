<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Option\Color;

use Contao\CoreBundle\Translation\TranslatableLabelInterface;
use Symfony\Component\Translation\TranslatableMessage;

enum Background: string implements TranslatableLabelInterface
{
    case transparent = 'bg-transparent';
    case neutral_one = 'bg-neutral-surface-1';
    case neutral_two = 'bg-neutral-surface-2';
    case neutral_three = 'bg-neutral-surface-3';
    case primary = 'bg-primary-solid';
    case secondary = 'bg-secondary-solid';
    case accent = 'bg-tertiary-solid'; // ToDo: Migrate accent option
    //case quaternary = 'bg-quaternary-solid';
    case success = 'bg-status-success-solid';
    case warning = 'bg-status-warning-solid';
    case error = 'bg-status-error-solid';
    case base_content = 'bg-neutral-inverse';

    public function label(): TranslatableMessage
    {
        return new TranslatableMessage('style_options.color.'.$this->name, [], 'style_options');
    }
}
