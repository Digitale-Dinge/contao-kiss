<?php

declare(strict_types=1);

use Contao\CoreBundle\DataContainer\PaletteManipulator;
use DigitaleDinge\ContaoKiss\Styles\Options\Layout;
use DigitaleDinge\ContaoKiss\Styles\Options\Margin;
use DigitaleDinge\ContaoKiss\Styles\Options\Padding;

$GLOBALS['TL_DCA']['tl_content']['palettes']['__selector__'][] = 'hyperlinkAsButton';

$GLOBALS['TL_DCA']['tl_content']['subpalettes']['hyperlinkAsButton'] = 'buttonType,buttonColor,buttonSize';

$GLOBALS['TL_DCA']['tl_content']['fields']['contentWidth'] = [
    'exclude' => true,
    'inputType' => 'select',
    'options' => array_column(Layout\ContainerSizes::cases(), 'value'),
    'reference' => &$GLOBALS['TL_LANG']['tl_content']['contentWidthOptions'],
    'eval' => [
        'tl_class' => 'w25 clr',
        'class' => 'widget-icon icon-width',
        'chosen' => true,
        'includeBlankOption' => true,
    ],
    'sql' => "varchar(16) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_content']['fields']['paddingTop'] = [
    'exclude' => true,
    'inputType' => 'select',
    'options' => array_column(Padding\Top::cases(), 'value'),
    'reference' => &$GLOBALS['TL_LANG']['tl_content']['paddingTopOptions'],
    'eval' => [
        'tl_class' => 'clr w25',
        'class' => 'widget-icon icon-pt',
        'chosen' => true,
        'includeBlankOption' => true,
    ],
    'sql' => "varchar(12) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_content']['fields']['paddingBottom'] = [
    'exclude' => true,
    'inputType' => 'select',
    'options' => array_column(Padding\Bottom::cases(), 'value'),
    'reference' => &$GLOBALS['TL_LANG']['tl_content']['paddingBottomOptions'],
    'eval' => [
        'tl_class' => 'w25',
        'class' => 'widget-icon icon-pb',
        'chosen' => true,
        'includeBlankOption' => true,
    ],
    'sql' => "varchar(12) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_content']['fields']['marginTop'] = [
    'exclude' => true,
    'inputType' => 'select',
    'options' => array_column(Margin\Top::cases(), 'value'),
    'reference' => &$GLOBALS['TL_LANG']['tl_content']['marginTopOptions'],
    'eval' => [
        'tl_class' => 'w25 clr',
        'class' => 'widget-icon icon-mt',
        'chosen' => true,
        'includeBlankOption' => true,
    ],
    'sql' => "varchar(12) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_content']['fields']['marginBottom'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_content']['marginBottom'],
    'exclude' => true,
    'inputType' => 'select',
    'options' => array_column(Margin\Bottom::cases(), 'value'),
    'reference' => &$GLOBALS['TL_LANG']['tl_content']['marginBottomOptions'],
    'eval' => [
        'tl_class' => 'w25',
        'class' => 'widget-icon icon-mb',
        'chosen' => true,
        'includeBlankOption' => true,
    ],
    'sql' => "varchar(12) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_content']['fields']['icon'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_content']['icon'],
    'inputType' => 'rocksolid_icon_picker',
    'eval' => [
        'iconFont' => 'files/frontend/dist/fonts/icons/fonts/icons.svg',
        'tl_class' => 'w50 clr',
    ],
    'sql' => "char(4) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_content']['fields']['hyperlinkAsButton'] = [
    'exclude' => true,
    'inputType' => 'checkbox',
    'sql' => "char(1) NOT NULL default ''",
    'eval' => [
        'tl_class' => 'm12 clr',
        'submitOnChange' => true,
    ],
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

$GLOBALS['TL_DCA']['tl_content']['fields']['buttonType'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_content']['buttonType'],
    'exclude' => true,
    'inputType' => 'radio',
    'options' => ['primary', 'secondary', 'outline'],
    'reference' => &$GLOBALS['TL_LANG']['tl_content'],
    'eval' => [
        'tl_class' => 'w50 image-grid image-grid--cols-3 image-grid--highdpi',
    ],
    'sql' => "varchar(32) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_content']['fields']['buttonSize'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_content']['buttonSize'],
    'exclude' => true,
    'inputType' => 'radio',
    'options' => ['lg', 'sm'],
    'reference' => &$GLOBALS['TL_LANG']['tl_content'],
    'eval' => [
        'tl_class' => 'w50 image-grid image-grid--highdpi',
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


PaletteManipulator::create()
    // Hyperlinks
    ->addField('lightboxIframe', 'rel', 'after')
    ->addField('icon', 'lightboxIframe', 'after')
    ->addField('iconPosition', 'icon', 'after')
    ->addField('hyperlinkAsButton', 'iconPosition', 'after')
    ->applyToPalette('hyperlink', 'tl_content')
    // Download
    ->addField('hyperlinkAsButton', 'download_legend', 'append')
    ->applyToPalette('download', 'tl_content')
    // Downloads
    ->addField('hyperlinkAsButton', 'download_legend', 'append')
    ->applyToPalette('downloads', 'tl_content')
;
