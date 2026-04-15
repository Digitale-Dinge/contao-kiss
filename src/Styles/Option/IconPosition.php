<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Option;

use Contao\CoreBundle\Translation\TranslatableLabelInterface;
use Symfony\Component\Translation\TranslatableMessage;

enum IconPosition: string implements TranslatableLabelInterface
{
    private const string BUNDLE_PATH = 'bundles/digitaledingecontaokiss/icons/';

    case left = self::BUNDLE_PATH . 'left';
    //case center = self::BUNDLE_PATH . 'center';
    case right = self::BUNDLE_PATH . 'right';
    //case justify = self::BUNDLE_PATH . 'justify';

    public function label(): TranslatableMessage
    {
        return new TranslatableMessage('style_options.position.'.$this->name, [], 'style_options');
    }
}
