<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Option;

use Contao\CoreBundle\Translation\TranslatableLabelInterface;
use Symfony\Component\Translation\TranslatableMessage;

enum IconPosition: string implements TranslatableLabelInterface
{
    case left = self::BUNDLE_PATH.'left';
    case right = self::BUNDLE_PATH.'right';

    private const string BUNDLE_PATH = 'bundles/digitaledingecontaokiss/icons/';

    public function label(): TranslatableMessage
    {
        return new TranslatableMessage('style_options.position.'.$this->name, [], 'style_options');
    }
}
