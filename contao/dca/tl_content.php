<?php

declare(strict_types=1);

use Contao\CoreBundle\DataContainer\PaletteManipulator;
use DigitaleDinge\ContaoKiss\Twig\Options\ContainerSizes;

$GLOBALS['TL_DCA']['tl_content']['palettes']['__selector__'][] = 'hyperlinkAsButton';

// Hyperlinks
PaletteManipulator::create()
    ->addField('lightboxIframe', 'rel', 'after')
    ->addField('icon', 'lightboxIframe', 'after')
    ->addField('iconPosition', 'icon', 'after')
    ->addField('hyperlinkAsButton', 'iconPosition', 'after')
    ->applyToPalette('hyperlink', 'tl_content')
;

// Download
PaletteManipulator::create()
    ->addField('hyperlinkAsButton', 'download_legend', 'append')
    ->applyToPalette('download', 'tl_content')
;

// Downloads
PaletteManipulator::create()
    ->addField('hyperlinkAsButton', 'download_legend', 'append')
    ->applyToPalette('downloads', 'tl_content')
;

/*
 * Customize subpalettes
 */
$GLOBALS['TL_DCA']['tl_content']['subpalettes']['hyperlinkAsButton'] = 'buttonType,buttonColor,buttonSize';

/*
 * Add fields configuration
 */
$GLOBALS['TL_DCA']['tl_content']['fields']['contentWidth'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_content']['contentWidth'],
    'exclude' => true,
    'inputType' => 'select',
    'options' => array_column(ContainerSizes::cases(), 'value'),
    'reference' => &$GLOBALS['TL_LANG']['tl_content'],
    'eval' => [
        'tl_class' => 'w25 clr',
        'includeBlankOption' => true,
    ],
    'sql' => "varchar(32) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_content']['fields']['paddingTop'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_content']['paddingTop'],
    'exclude' => true,
    'inputType' => 'select',
    'options' => ['pt-line-1/2', 'pt-line-1', 'pt-line-2', 'pt-line-3', 'pt-line-4', 'pt-line-5'],
    'reference' => &$GLOBALS['TL_LANG']['tl_content']['padding'],
    'eval' => [
        'tl_class' => 'clr w25',
        'includeBlankOption' => true,
    ],
    'sql' => "varchar(32) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_content']['fields']['paddingBottom'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_content']['paddingBottom'],
    'exclude' => true,
    'inputType' => 'select',
    'options' => ['pb-line-1/2', 'pb-line-1', 'pb-line-2', 'pb-line-3', 'pb-line-4', 'pb-line-5'],
    'reference' => &$GLOBALS['TL_LANG']['tl_content']['padding'],
    'eval' => [
        'tl_class' => 'w25',
        'includeBlankOption' => true,
    ],
    'sql' => "varchar(32) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_content']['fields']['marginTop'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_content']['marginTop'],
    'exclude' => true,
    'inputType' => 'select',
    'options' => ['mt-line-1/2', 'mt-line-1', 'mt-line-2', 'mt-line-3', 'mt-line-4', 'mt-line-5'],
    'reference' => &$GLOBALS['TL_LANG']['tl_content']['padding'],
    'eval' => [
        'tl_class' => 'w25',
        'includeBlankOption' => true,
    ],
    'sql' => "varchar(32) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_content']['fields']['marginBottom'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_content']['marginBottom'],
    'exclude' => true,
    'inputType' => 'select',
    'options' => ['mb-line-1/2', 'mb-line-1', 'mb-line-2', 'mb-line-3', 'mb-line-4', 'mb-line-5'],
    'reference' => &$GLOBALS['TL_LANG']['tl_content']['padding'],
    'eval' => [
        'tl_class' => 'w25',
        'includeBlankOption' => true,
    ],
    'sql' => "varchar(32) NOT NULL default ''",
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

$GLOBALS['TL_DCA']['tl_content']['fields']['downloadsAsCard'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_content']['downloadsAsCard'],
    'exclude' => true,
    'inputType' => 'checkbox',
    'sql' => "char(1) NOT NULL default ''",
    'eval' => [
        'tl_class' => 'm12 clr',
    ],
];

$GLOBALS['TL_DCA']['tl_content']['fields']['hyperlinkAsButton'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_content']['hyperlinkAsButton'],
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
    'label' => &$GLOBALS['TL_LANG']['tl_content']['lightboxIframe'],
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
