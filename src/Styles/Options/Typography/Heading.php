<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Options\Typography;

use Contao\CoreBundle\Translation\TranslatableLabelInterface;
use DigitaleDinge\ContaoKiss\Styles\ClassOptionsInterface;
use Symfony\Component\Translation\TranslatableMessage;

enum Heading: string implements ClassOptionsInterface, TranslatableLabelInterface
{
    case h1 = 'h1';
    case h2 = 'h2';
    case h3 = 'h3';
    case h4 = 'h4';
    case h5 = 'h5';
    case h6 = 'h6';

    public function label(): TranslatableMessage
    {
        return new TranslatableMessage('style_options.typography.heading.' . $this->name, [], 'style_options');
    }
}
