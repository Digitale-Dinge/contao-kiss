<?php

declare(strict_types=1);

$GLOBALS['TL_DCA']['tl_module']['fields']['headline']['inputType'] = 'collection';
$GLOBALS['TL_DCA']['tl_module']['fields']['headline']['fields'] = [
    'value' => [
        'label'           => [
            &$GLOBALS['TL_LANG']['tl_module']['headline'][0], null
        ],
        'inputType'       => 'text',
        'eval'            => ['maxlength'=>200, 'basicEntities'=>true],
    ],
    'unit' => [
        'label'           => [
            &$GLOBALS['TL_LANG']['tl_module']['headline']['unit'], null
        ],
        'inputType'       => 'select',
        'options'         => ['h1', 'h2', 'h3', 'h4', 'h5', 'h6']
    ],
    'appearance' => [
        'label'           => [
            &$GLOBALS['TL_LANG']['tl_module']['headline']['appearance'], null
        ],
        'inputType'       => 'select',
        'eval'            => ['includeBlankOption' => true]
    ],
];

$GLOBALS['TL_DCA']['tl_module']['fields']['topline'] = [
    'exclude' => true,
    'inputType' => 'text',
    'targetColumn' => 'kiss_styles',
    'eval' => [
        'tl_class' => 'w50',
        'maxlength' => 255,
        'allowHtml' => true
    ],
];
