<?php

declare(strict_types=1);

use Contao\CoreBundle\DataContainer\PaletteManipulator;
use Contao\CoreBundle\DataContainer\PaletteNotFoundException;

// ToDo: Use an event listener to dispatch events for other plugins to register their fields
const FIELDS_APPLY_COLOR = [
    'text', // input
    'textdigit', // input
    'textcustom', // input
    'password', // input
    'passwordcustom', // input
    'textarea', // textarea
    'textareacustom', // textarea
    'select', // select
    'radio', // radio
    'checkbox', // checkbox
    'range', // range
    'captcha', // input
    'altcha', // input
    'submit', // button,
    'upload', // upload
];

const FIELDS_APPLY_VARIANT = [
    'text', // input
    'textdigit', // input
    'textcustom', // input
    'password', // input
    'passwordcustom', // input
    'textarea', // textarea
    'textareacustom', // textarea
    'select', // select
    'captcha', // input
    'altcha', // input
    'submit', // button
];

const FIELDS_APPLY_SIZE = [
    'text', // input
    'textdigit', // input
    'textcustom', // input
    'password', // input
    'passwordcustom', // input
    'textarea', // textarea
    'textareacustom', // textarea
    'select', // select
    'radio', // radio
    'checkbox', // checkbox
    'range', // range
    'captcha', // input
    'altcha', // input
    'submit', // button
];

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

$GLOBALS['TL_DCA']['tl_form_field']['fields']['fieldColor'] = [
    'exclude' => true,
    'inputType' => 'select',
    'targetColumn' => 'kiss_styles',
    'eval' => [
        'tl_class' => 'w25',
        'includeBlankOption' => true,
    ],
];

$GLOBALS['TL_DCA']['tl_form_field']['fields']['fieldSize'] = [
    'exclude' => true,
    'inputType' => 'select',
    'targetColumn' => 'kiss_styles',
    'eval' => [
        'tl_class' => 'w25',
        'includeBlankOption' => true,
    ],
];

$GLOBALS['TL_DCA']['tl_form_field']['fields']['fieldVariant'] = [
    'exclude' => true,
    'inputType' => 'select',
    'targetColumn' => 'kiss_styles',
    'eval' => [
        'tl_class' => 'w25',
        'includeBlankOption' => true,
    ],
];

$GLOBALS['TL_DCA']['tl_form_field']['fields']['fieldShape'] = [
    'exclude' => true,
    'inputType' => 'select',
    'targetColumn' => 'kiss_styles',
    'eval' => [
        'tl_class' => 'w25',
        'includeBlankOption' => true,
    ],
];

$GLOBALS['TL_DCA']['tl_form_field']['fields']['textAlignment'] = [
    'exclude' => true,
    'inputType' => 'select',
    'targetColumn' => 'kiss_styles',
    'eval' => [
        'tl_class' => 'w25 clr',
        'includeBlankOption' => true,
    ],
];

$GLOBALS['TL_DCA']['tl_form_field']['fields']['textAppearance'] = [
    'exclude' => true,
    'inputType' => 'select',
    'targetColumn' => 'kiss_styles',
    'eval' => [
        'tl_class' => 'w25',
        'includeBlankOption' => true,
    ],
];


$pmFieldColor = PaletteManipulator::create()
    ->addLegend('appearance_legend', 'layout_legend', PaletteManipulator::POSITION_AFTER)
    ->addField('fieldColor', 'appearance_legend', PaletteManipulator::POSITION_APPEND)
;

$pmFieldSize = PaletteManipulator::create()
    ->addLegend('appearance_legend', 'layout_legend', PaletteManipulator::POSITION_AFTER)
    ->addField('fieldSize', 'appearance_legend', PaletteManipulator::POSITION_APPEND)
;

$pmFieldVariant = PaletteManipulator::create()
    ->addLegend('appearance_legend', 'layout_legend', PaletteManipulator::POSITION_AFTER)
    ->addField('fieldVariant', 'appearance_legend', PaletteManipulator::POSITION_APPEND)
;

$applyPalette = static function(PaletteManipulator $pm, array $fields): void {
    foreach ($fields as $field) {
        try { $pm->applyToPalette($field, 'tl_form_field'); }
        catch (PaletteNotFoundException) {}
    }
};

$applyPalette($pmFieldColor, FIELDS_APPLY_COLOR);
$applyPalette($pmFieldSize, FIELDS_APPLY_SIZE);
$applyPalette($pmFieldVariant, FIELDS_APPLY_VARIANT);


PaletteManipulator::create()
    ->addLegend('appearance_legend', 'layout_legend', PaletteManipulator::POSITION_AFTER)
    ->addField(['fieldShape', 'textAlignment'], 'appearance_legend', PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('submit', 'tl_form_field')
;

PaletteManipulator::create()
    ->addLegend('appearance_legend', 'layout_legend', PaletteManipulator::POSITION_AFTER)
    ->addField(['textAlignment', 'textAppearance'], 'appearance_legend', PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('explanation', 'tl_form_field')
;
