<?php

declare(strict_types=1);

use Contao\CoreBundle\DataContainer\PaletteManipulator;
use Doctrine\DBAL\Platforms\MySQLPlatform;

$GLOBALS['TL_DCA']['tl_content']['palettes']['__selector__'][] = 'ctaAsButton';
$GLOBALS['TL_DCA']['tl_content']['subpalettes']['ctaAsButton'] = 'ctaType,ctaColor,ctaSize,ctaShape';

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

$GLOBALS['TL_DCA']['tl_content']['fields']['paddingTop'] = [
    'exclude' => true,
    'inputType' => 'select',
    'targetColumn' => 'kiss_styles',
    'eval' => [
        'tl_class' => 'w25',
        'class' => 'widget-icon icon-pt',
        'chosen' => true,
        'includeBlankOption' => true,
    ],
];

$GLOBALS['TL_DCA']['tl_content']['fields']['marginBottom'] = [
    'exclude' => true,
    'inputType' => 'select',
    'targetColumn' => 'kiss_styles',
    'eval' => [
        'tl_class' => 'w25 clr',
        'class' => 'widget-icon icon-mb',
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

$GLOBALS['TL_DCA']['tl_content']['fields']['gridColumns'] = [
    'exclude' => true,
    'inputType' => 'select',
    'targetColumn' => 'kiss_styles',
    'eval' => [
        'tl_class' => 'w50 clr',
        'chosen' => true,
        'includeBlankOption' => true,
    ],
];

$GLOBALS['TL_DCA']['tl_content']['fields']['gridGap'] = [
    'exclude' => true,
    'inputType' => 'select',
    'targetColumn' => 'kiss_styles',
    'eval' => [
        'tl_class' => 'w50',
        'chosen' => true,
        'includeBlankOption' => true,
    ],
];

$GLOBALS['TL_DCA']['tl_content']['fields']['backgroundColor'] = [
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

$GLOBALS['TL_DCA']['tl_content']['fields']['textAlignment'] = [
    'exclude' => true,
    'inputType' => 'select',
    'targetColumn' => 'kiss_styles',
    'eval' => [
        'tl_class' => 'w25',
        'includeBlankOption' => true,
    ],
];

$GLOBALS['TL_DCA']['tl_content']['fields']['ctaAsButton'] = [
    'exclude' => true,
    'inputType' => 'checkbox',
    'targetColumn' => 'kiss_styles',
    'eval' => [
        'tl_class' => 'm12 clr',
        'submitOnChange' => true,
    ],
];

$GLOBALS['TL_DCA']['tl_content']['fields']['ctaType'] = [
    'exclude' => true,
    'inputType' => 'select',
    'targetColumn' => 'kiss_styles',
    'eval' => [
        'tl_class' => 'w50',
        'includeBlankOption' => true,
    ],
];

$GLOBALS['TL_DCA']['tl_content']['fields']['ctaColor'] = [
    'exclude' => true,
    'inputType' => 'select',
    'targetColumn' => 'kiss_styles',
    'eval' => [
        'tl_class' => 'w50',
        'includeBlankOption' => true,
    ],
];

$GLOBALS['TL_DCA']['tl_content']['fields']['ctaSize'] = [
    'exclude' => true,
    'inputType' => 'select',
    'targetColumn' => 'kiss_styles',
    'eval' => [
        'tl_class' => 'w50',
        'includeBlankOption' => true,
    ],
];

$GLOBALS['TL_DCA']['tl_content']['fields']['ctaShape'] = [
    'exclude' => true,
    'inputType' => 'select',
    'targetColumn' => 'kiss_styles',
    'eval' => [
        'tl_class' => 'w50',
        'includeBlankOption' => true,
    ],
];

// Call to actions that can be appended to text or custom elements
$GLOBALS['TL_DCA']['tl_content']['fields']['callToAction'] = [
    'exclude' => true,
    'inputType' => 'group',
    'palette' => ['text', 'ctaType', 'ctaColor', 'ctaSize', 'url', 'target'],
    'fields' => [
        'text' => [
            'label' => &$GLOBALS['TL_LANG']['tl_content']['ctaText'],
            'inputType' => 'text',
            'eval' => [
                'tl_class' => 'w25',
            ]
        ],
        '&ctaType' => [
            'eval' => [
                'tl_class' => 'w25',
            ],
        ],
        '&ctaColor' => [
            'eval' => [
                'tl_class' => 'w25',
            ],
        ],
        '&ctaSize' => [
            'eval' => [
                'tl_class' => 'w25',
            ],
        ],
        '&url' => [
            'eval' => [
                'mandatory' => false,
            ],
        ],
        '&target' => [
            'eval' => [
                'mandatory' => false,
            ],
        ],
    ],
    'max' => 2,
    'eval' => [
        'tl_class' => 'w100 clr call_to_action_widget',
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

//$GLOBALS['TL_DCA']['tl_content']['fields']['rel']['eval']['tl_class'] = 'w25';

$GLOBALS['TL_DCA']['tl_content']['fields']['lightboxIframe'] = [
    'exclude' => true,
    'inputType' => 'checkbox',
    'sql' => "char(1) NOT NULL default ''",
    'eval' => [
        'tl_class' => 'w25 m12',
        'submitOnChange' => true,
    ],
];

$GLOBALS['TL_DCA']['tl_content']['fields']['icon'] = [
    'inputType' => 'svgIconPicker',
    'eval' => [
        'sourceDirectory' => 'public/kiss_icons/svg',
        'metadataDirectory' => 'public/kiss_icons',
        'tl_class' => 'w50 clr',
    ],
    'sql' => 'blob NULL',
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
//$GLOBALS['TL_DCA']['tl_content']['fields']['linktext']['eval']['allowHtml'] = true;


PaletteManipulator::create()
    ->addField('callToAction', 'text_legend', PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('text', 'tl_content')

    ->addField(['textAlignment', 'lightboxIframe', 'icon', 'iconPosition', 'ctaAsButton'], 'rel', PaletteManipulator::POSITION_AFTER)
    ->applyToPalette('hyperlink', 'tl_content')

    ->addField(['textAlignment', 'ctaAsButton'], 'download_legend', PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('download', 'tl_content')

    ->addField('ctaAsButton', 'download_legend', PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('downloads', 'tl_content')

    ->addLegend('grid_legend', ['layout_legend', 'template_legend', 'protected_legend'], PaletteManipulator::POSITION_BEFORE)
    ->addField(['gridColumns', 'gridGap'], 'grid_legend', PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('element_group', 'tl_content')
;
