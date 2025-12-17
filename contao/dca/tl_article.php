<?php

declare(strict_types=1);

use DigitaleDinge\ContaoKiss\Styles\Options\Layout;
use DigitaleDinge\ContaoKiss\Styles\Options\Padding;

// Options are added dynamically via AddBackgroundColorsListener
$GLOBALS['TL_DCA']['tl_article']['fields']['backgroundColor'] = [
    'exclude' => true,
    'inputType' => 'select',
    'eval' => [
        'tl_class' => 'w25',
        'class' => 'widget-icon icon-background',
        'includeBlankOption' => true,
        'chosen' => true,
    ],
    'sql' => "varchar(32) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_article']['fields']['contentWidth'] = [
    'exclude' => true,
    'inputType' => 'select',
    'options' => array_column(Layout\ContainerSizes::cases(), 'value'),
    'reference' => &$GLOBALS['TL_LANG']['tl_article']['contentWidthOptions'],
    'eval' => [
        'tl_class' => 'w25 clr',
        'class' => 'widget-icon icon-width',
        'includeBlankOption' => true,
        'chosen' => true,
    ],
    'sql' => "varchar(32) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_article']['fields']['paddingTop'] = [
    'exclude' => true,
    'inputType' => 'select',
    'options' => array_column(Padding\Top::cases(), 'value'),
    'reference' => &$GLOBALS['TL_LANG']['tl_article']['paddingTopOptions'],
    'eval' => [
        'tl_class' => 'clr w25',
        'class' => 'widget-icon icon-pt',
        'includeBlankOption' => true,
        'chosen' => true,
    ],
    'sql' => "varchar(12) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_article']['fields']['paddingBottom'] = [
    'exclude' => true,
    'inputType' => 'select',
    'options' => array_column(Padding\Bottom::cases(), 'value'),
    'reference' => &$GLOBALS['TL_LANG']['tl_article']['paddingBottomOptions'],
    'eval' => [
        'tl_class' => 'w25',
        'class' => 'widget-icon icon-pb',
        'includeBlankOption' => true,
        'chosen' => true,
    ],
    'sql' => "varchar(12) NOT NULL default ''",
];
