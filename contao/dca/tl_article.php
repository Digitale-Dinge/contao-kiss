<?php

declare(strict_types=1);

$GLOBALS['TL_DCA']['tl_article']['fields']['backgroundColor'] = [
    'exclude' => true,
    'inputType' => 'select',
    'eval' => [
        'tl_class' => 'w25',
        'class' => 'widget-icon icon-background',
        'includeBlankOption' => true,
        'chosen' => true,
    ],
    'sql' => [
        'type' => 'string',
        'length' => 12,
        'default' => ''
    ],
];

$GLOBALS['TL_DCA']['tl_article']['fields']['contentWidth'] = [
    'exclude' => true,
    'inputType' => 'select',
    'eval' => [
        'tl_class' => 'w25 clr',
        'class' => 'widget-icon icon-width',
        'includeBlankOption' => true,
        'chosen' => true,
    ],
    'sql' => [
        'type' => 'string',
        'length' => 32,
        'default' => ''
    ],
];

$GLOBALS['TL_DCA']['tl_article']['fields']['paddingTop'] = [
    'exclude' => true,
    'inputType' => 'select',
    'eval' => [
        'tl_class' => 'clr w25',
        'class' => 'widget-icon icon-pt',
        'includeBlankOption' => true,
        'chosen' => true,
    ],
    'sql' => [
        'type' => 'string',
        'length' => 12,
        'default' => ''
    ],
];

$GLOBALS['TL_DCA']['tl_article']['fields']['paddingBottom'] = [
    'exclude' => true,
    'inputType' => 'select',
    'eval' => [
        'tl_class' => 'w25',
        'class' => 'widget-icon icon-pb',
        'includeBlankOption' => true,
        'chosen' => true,
    ],
    'sql' => [
        'type' => 'string',
        'length' => 12,
        'default' => ''
    ],
];
