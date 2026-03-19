<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Option\Typography;

use Contao\CoreBundle\Translation\TranslatableLabelInterface;
use Symfony\Component\Translation\TranslatableMessage;

enum Alignment: string implements TranslatableLabelInterface
{
    case start = 'text-start';
    case center = 'text-center';
    case end = 'text-end';

    public function label(): TranslatableMessage
    {
        return new TranslatableMessage('style_options.typography.alignment.'.$this->name, [], 'style_options');
    }
}
