<?php

declare(strict_types=1);

// use Contao\CoreBundle\DataContainer\PaletteManipulator;
use Contao\CoreBundle\DataContainer\PaletteManipulator;
use Doctrine\DBAL\Platforms\MySQLPlatform;

$GLOBALS['TL_DCA']['tl_content']['fields']['headline']['inputType'] = 'collection';
$GLOBALS['TL_DCA']['tl_content']['fields']['headline']['eval']['tl_class'] = 'w50 clr hl_collection';
$GLOBALS['TL_DCA']['tl_content']['fields']['headline']['fields'] = [
    'value' => [
        'label' => [
            &$GLOBALS['TL_LANG']['tl_content']['headline'][0], null,
        ],
        'inputType' => 'text',
        'eval' => [
            'maxlength' => 200,
            'basicEntities' => true,
        ],
    ],
    'unit' => [
        'label' => [
            &$GLOBALS['TL_LANG']['tl_content']['headline']['unit'], null,
        ],
        'inputType' => 'select',
        'options' => ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
    ],
    'appearance' => [
        'label' => [
            &$GLOBALS['TL_LANG']['tl_content']['headline']['appearance'], null,
        ],
        'inputType' => 'select',
        'eval' => [
            'includeBlankOption' => true,
        ],
    ],
];

$GLOBALS['TL_DCA']['tl_content']['fields']['topline'] = [
    'exclude' => true,
    'inputType' => 'text',
    'targetColumn' => 'kiss_styles',
    'eval' => [
        'tl_class' => 'w50',
        'maxlength' => 255,
        'allowHtml' => true,
    ],
];

$GLOBALS['TL_DCA']['tl_content']['fields']['contentWidth'] = [
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

$GLOBALS['TL_DCA']['tl_content']['fields']['paddingTop'] = [
    'exclude' => true,
    'inputType' => 'select',
    'targetColumn' => 'kiss_styles',
    'eval' => [
        'tl_class' => 'clr w25',
        'class' => 'widget-icon icon-pt',
        'chosen' => true,
        'includeBlankOption' => true,
    ],
];

$GLOBALS['TL_DCA']['tl_content']['fields']['paddingBottom'] = [
    'exclude' => true,
    'inputType' => 'select',
    'targetColumn' => 'kiss_styles',
    'eval' => [
        'tl_class' => 'w25',
        'class' => 'widget-icon icon-pb',
        'chosen' => true,
        'includeBlankOption' => true,
    ],
];

$GLOBALS['TL_DCA']['tl_content']['fields']['marginTop'] = [
    'exclude' => true,
    'inputType' => 'select',
    'targetColumn' => 'kiss_styles',
    'eval' => [
        'tl_class' => 'w25 clr',
        'class' => 'widget-icon icon-mt',
        'chosen' => true,
        'includeBlankOption' => true,
    ],
];

$GLOBALS['TL_DCA']['tl_content']['fields']['marginBottom'] = [
    'exclude' => true,
    'inputType' => 'select',
    'targetColumn' => 'kiss_styles',
    'eval' => [
        'tl_class' => 'w25',
        'class' => 'widget-icon icon-mb',
        'chosen' => true,
        'includeBlankOption' => true,
    ],
];

// Call to actions that can be appended to text or custom elements
$GLOBALS['TL_DCA']['tl_content']['fields']['callToAction'] = [
    'exclude' => true,
    'inputType' => 'rowWizard',
    'targetColumn' => 'kiss_styles',
    'fields' => [
        'text' => [
            'label' => &$GLOBALS['TL_LANG']['tl_content']['text'][0],
            'inputType' => 'text',
        ],
        'type' => [
            'label' => &$GLOBALS['TL_LANG']['tl_content']['callToAction']['type'],
            'inputType' => 'select',
            'eval' => [
                'includeBlankOption' => true,
            ],
        ],
        'color' => [
            'label' => &$GLOBALS['TL_LANG']['tl_content']['callToAction']['color'],
            'inputType' => 'select',
            'eval' => [
                'includeBlankOption' => true,
            ],
        ],
        'size' => [
            'label' => &$GLOBALS['TL_LANG']['tl_content']['callToAction']['size'],
            'inputType' => 'select',
            'eval' => [
                'includeBlankOption' => true,
            ],
        ],
        'jumpTo' => array_replace_recursive($GLOBALS['TL_DCA']['tl_content']['fields']['jumpTo'] ?? [], [
            'label' => &$GLOBALS['TL_LANG']['tl_content']['jumpTo'][0],
            'eval' => [
                'mandatory' => false,
            ],
        ]),
        'url' => array_replace_recursive($GLOBALS['TL_DCA']['tl_content']['fields']['url'] ?? [], [
            'label' => &$GLOBALS['TL_LANG']['MSC']['url'][0],
            'eval' => [
                'mandatory' => false,
            ],
        ]),
        'target' => array_replace_recursive($GLOBALS['TL_DCA']['tl_content']['fields']['target'] ?? [], [
            'label' => &$GLOBALS['TL_LANG']['MSC']['target'][0],
            'eval' => [
                'mandatory' => false,
            ],
        ]),
    ],
    'eval' => [
        'tl_class' => 'w100 clr call_to_action_widget',
        'max' => 2,
        'style' => 'max-width: 1000px',
        'sortable' => false,
    ],
    'sql' => ['type' => 'blob', 'length' => MySQLPlatform::LENGTH_LIMIT_BLOB, 'notnull' => false]
];

$GLOBALS['TL_DCA']['tl_content']['fields']['textAppearance'] = [
    'exclude' => true,
    'inputType' => 'select',
    'targetColumn' => 'kiss_styles',
    'eval' => [
        'tl_class' => 'w50 clr',
        'includeBlankOption' => true,
    ],
];

PaletteManipulator::create()
    ->addField('callToAction', 'text_legend', PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('text', 'tl_content')
;

$GLOBALS['TL_DCA']['tl_content']['fields']['icon'] = [
    'inputType' => 'svgIconPicker',
    'eval' => [
        'sourceDirectory' => 'public/kiss_icons/svg',
        'metadataDirectory' => 'public/kiss_icons',
        'tl_class' => 'w50 clr',
    ],
    'sql' => 'blob NULL',
];

$GLOBALS['TL_DCA']['tl_content']['fields']['rel']['eval']['tl_class'] = 'w25';

$GLOBALS['TL_DCA']['tl_content']['fields']['lightboxIframe'] = [
    'exclude' => true,
    'inputType' => 'checkbox',
    'sql' => "char(1) NOT NULL default ''",
    'eval' => [
        'tl_class' => 'w25 m12',
        'submitOnChange' => true,
    ],
];

$GLOBALS['TL_DCA']['tl_content']['fields']['iconPosition'] = [
    'exclude' => true,
    'inputType' => 'radio',
    'options' => ['left', 'right'],
    'reference' => &$GLOBALS['TL_LANG']['tl_content'],
    'eval' => [
        'tl_class' => 'w50 image-grid image-grid--highdpi',
        'includeBlankOption' => false,
    ],
    'sql' => "varchar(32) NOT NULL default ''",
];

/*
 * Customize existing fields
 */

// text fields clearfix
$GLOBALS['TL_DCA']['tl_content']['fields']['text']['eval']['tl_class'] = 'long clr';
$GLOBALS['TL_DCA']['tl_content']['fields']['optionalText']['eval']['tl_class'] = 'long clr';
$GLOBALS['TL_DCA']['tl_content']['fields']['useImage']['eval']['tl_class'] = 'long clr';

/* HTML in Überschriften */
$GLOBALS['TL_DCA']['tl_content']['fields']['headline']['eval']['allowHtml'] = true;

/* HTML in Linktexten */
$GLOBALS['TL_DCA']['tl_content']['fields']['linktext']['eval']['allowHtml'] = true;

/*
 * Hyperlink as Button
 */
$GLOBALS['TL_DCA']['tl_content']['palettes']['__selector__'][] = 'hyperlinkAsButton';
$GLOBALS['TL_DCA']['tl_content']['subpalettes']['hyperlinkAsButton'] = 'buttonStyle,buttonColor,buttonSize,buttonShape';

$GLOBALS['TL_DCA']['tl_content']['fields']['hyperlinkAsButton'] = [
    'exclude' => true,
    'inputType' => 'checkbox',
    'eval' => [
        'tl_class' => 'm12 clr',
        'submitOnChange' => true,
    ],
    'sql' => "char(1) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_content']['fields']['buttonStyle'] = [
    'exclude' => true,
    'inputType' => 'select',
    'eval' => [
        'tl_class' => 'w50',
        'includeBlankOption' => true,
    ],
    'sql' => "varchar(32) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_content']['fields']['buttonColor'] = [
    'exclude' => true,
    'inputType' => 'select',
    'eval' => [
        'tl_class' => 'w50',
        'includeBlankOption' => true,
    ],
    'sql' => "varchar(32) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_content']['fields']['buttonSize'] = [
    'exclude' => true,
    'inputType' => 'select',
    'eval' => [
        'tl_class' => 'w50',
        'includeBlankOption' => true,
    ],
    'sql' => "varchar(32) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_content']['fields']['buttonShape'] = [
    'exclude' => true,
    'inputType' => 'select',
    'eval' => [
        'tl_class' => 'w50',
        'includeBlankOption' => true,
    ],
    'sql' => "varchar(32) NOT NULL default ''",
];

PaletteManipulator::create()
    ->addField('lightboxIframe', 'rel', PaletteManipulator::POSITION_AFTER)
    ->addField('icon', 'lightboxIframe', PaletteManipulator::POSITION_AFTER)
    ->addField('iconPosition', 'icon', PaletteManipulator::POSITION_AFTER)
    ->addField('hyperlinkAsButton', 'iconPosition', PaletteManipulator::POSITION_AFTER)
    ->applyToPalette('hyperlink', 'tl_content')
;

PaletteManipulator::create()
    ->addField('hyperlinkAsButton', 'download_legend', PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('download', 'tl_content')
;

PaletteManipulator::create()
    ->addField('hyperlinkAsButton', 'download_legend', PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('downloads', 'tl_content')
;
