<?php

declare(strict_types=1);

use Contao\CoreBundle\DataContainer\PaletteManipulator;

$GLOBALS['TL_DCA']['tl_module']['fields']['headline']['inputType'] = 'collection';
$GLOBALS['TL_DCA']['tl_module']['fields']['headline']['eval']['tl_class'] = 'w50 clr hl_collection';
$GLOBALS['TL_DCA']['tl_module']['fields']['headline']['fields'] = [
    'value' => [
        'label' => [
            &$GLOBALS['TL_LANG']['tl_module']['headline'][0], null,
        ],
        'inputType' => 'text',
        'eval' => [
            'maxlength' => 200,
            'basicEntities' => true,
        ],
    ],
    'unit' => [
        'label' => [
            &$GLOBALS['TL_LANG']['tl_module']['headline']['unit'], null,
        ],
        'inputType' => 'select',
        'options' => ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
    ],
    'appearance' => [
        'label' => [
            &$GLOBALS['TL_LANG']['tl_module']['headline']['appearance'], null,
        ],
        'inputType' => 'select',
        'eval' => [
            'includeBlankOption' => true,
        ],
    ],
];

$GLOBALS['TL_DCA']['tl_module']['fields']['topline'] = [
    'exclude' => true,
    'inputType' => 'text',
    'targetColumn' => 'kiss_styles',
    'eval' => [
        'tl_class' => 'w50',
        'maxlength' => 255,
        'allowHtml' => true,
    ],
];

$GLOBALS['TL_DCA']['tl_module']['fields']['gridColumns'] = [
    'exclude' => true,
    'inputType' => 'select',
    'targetColumn' => 'kiss_styles',
    'eval' => [
        'tl_class' => 'w25 clr',
        //'class' => 'widget-icon icon-width',
        'chosen' => true,
        'includeBlankOption' => true,
    ],
];

$GLOBALS['TL_DCA']['tl_module']['fields']['gridGap'] = [
    'exclude' => true,
    'inputType' => 'select',
    'targetColumn' => 'kiss_styles',
    'eval' => [
        'tl_class' => 'w25',
        //'class' => 'widget-icon icon-width',
        'chosen' => true,
        'includeBlankOption' => true,
    ],
];

PaletteManipulator::create()
    ->addLegend('layout_legend', ['template_legend', 'protected_legend'], PaletteManipulator::POSITION_BEFORE)
    ->addField(['gridColumns', 'gridGap'], 'layout_legend', PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('newslist', 'tl_module')
    ->applyToPalette('eventlist', 'tl_module')
    ->applyToPalette('faqlist', 'tl_module')
    ->applyToPalette('newsletterlist', 'tl_module')
    ->applyToPalette('newsletterlist', 'tl_module')
;
