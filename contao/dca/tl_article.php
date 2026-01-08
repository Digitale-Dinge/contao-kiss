<?php

declare(strict_types=1);

$GLOBALS['TL_DCA']['tl_article']['fields']['backgroundColor'] = [
    'exclude' => true,
    'inputType' => 'select',
    'targetColumn' => 'kiss_styles',
    'eval' => [
        'tl_class' => 'w25',
        'class' => 'widget-icon icon-background',
        'includeBlankOption' => true,
        'chosen' => true,
    ],
];

$GLOBALS['TL_DCA']['tl_article']['fields']['contentWidth'] = [
    'exclude' => true,
    'inputType' => 'select',
    'targetColumn' => 'kiss_styles',
    'eval' => [
        'tl_class' => 'w25 clr',
        'class' => 'widget-icon icon-width',
        'includeBlankOption' => true,
        'chosen' => true,
    ],
];

$GLOBALS['TL_DCA']['tl_article']['fields']['paddingTop'] = [
    'exclude' => true,
    'inputType' => 'select',
    'targetColumn' => 'kiss_styles',
    'eval' => [
        'tl_class' => 'clr w25',
        'class' => 'widget-icon icon-pt',
        'includeBlankOption' => true,
        'chosen' => true,
    ],
];

$GLOBALS['TL_DCA']['tl_article']['fields']['paddingBottom'] = [
    'exclude' => true,
    'inputType' => 'select',
    'targetColumn' => 'kiss_styles',
    'eval' => [
        'tl_class' => 'w25',
        'class' => 'widget-icon icon-pb',
        'includeBlankOption' => true,
        'chosen' => true,
    ],
];
