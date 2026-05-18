<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Styles\Option\Layout;

use Contao\CoreBundle\Translation\TranslatableLabelInterface;
use Symfony\Component\Translation\TranslatableMessage;

enum ColumnSpan: string implements TranslatableLabelInterface
{
    case one = 'md:col-span-1';
    case two = 'md:col-span-2';
    case three = 'md:col-span-3';
    case four = 'md:col-span-4';
    case five = 'md:col-span-5';
    case six = 'md:col-span-6';
    case seven = 'md:col-span-7';
    case eight = 'md:col-span-8';
    case nine = 'md:col-span-9';
    case ten = 'md:col-span-10';
    case eleven = 'md:col-span-11';

    public function label(): TranslatableMessage
    {
        return new TranslatableMessage('style_options.layout.column_span.'.$this->name, [], 'style_options');
    }
}
