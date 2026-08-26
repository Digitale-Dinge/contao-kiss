<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Option\Color;

use Contao\CoreBundle\Translation\TranslatableLabelInterface;
use Symfony\Component\Translation\TranslatableMessage;

enum Color: string implements TranslatableLabelInterface
{
    // The values are component modifiers (btn-primary, range-tertiary)
    // ToDo: "accent" was renamed to "tertiary" without a migration. Content from
    // older projects that stored "accent" resolves to nothing and loses its colour.
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
