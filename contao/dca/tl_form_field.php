<?php

declare(strict_types=1);

use Contao\CoreBundle\DataContainer\PaletteManipulator;
use Contao\CoreBundle\DataContainer\PaletteNotFoundException;

$GLOBALS['TL_DCA']['tl_form_field']['fields']['gridSpan'] = [
    'exclude' => true,
    'inputType' => 'select',
    'targetColumn' => 'kiss_styles',
    'eval' => [
        'tl_class' => 'w25 clr',
        'class' => 'widget-icon icon-width',
        'chosen' => true,
        'includeBlankOption' => true,
    ],
];

$pm = PaletteManipulator::create()
    ->addLegend('layout_legend', 'template_legend', PaletteManipulator::POSITION_BEFORE)
    ->addField('gridSpan', 'layout_legend', PaletteManipulator::POSITION_APPEND)
;

foreach (array_keys($GLOBALS['TL_FFL'] ?? []) as $field) {
    if ('fieldsetStop' === $field) {
        continue;
    }

    try {
        $pm->applyToPalette($field, 'tl_form_field');
    }
    catch (PaletteNotFoundException) {
        // Noop
    }
}
