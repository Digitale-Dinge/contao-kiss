<?php

declare(strict_types=1);

use Contao\CoreBundle\DataContainer\PaletteManipulator;

$GLOBALS['TL_DCA']['tl_form_field']['fields']['gridColumns'] = [
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
    ->addField('gridColumns', 'layout_legend', PaletteManipulator::POSITION_APPEND)
;

foreach (['explanation', 'fieldsetStart', 'fieldsetStop', 'text', 'password', 'textarea', 'select', 'radio', 'checkbox', 'upload', 'range', 'captcha', 'altcha', 'submit', 'countryselect'] as $field) {
    $pm->applyToPalette($field, 'tl_form_field');
}
