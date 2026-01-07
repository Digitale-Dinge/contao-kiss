<?php

declare(strict_types=1);

use Contao\CoreBundle\DataContainer\PaletteManipulator;

$GLOBALS['TL_DCA']['tl_page']['fields']['kiss_company'] = [
    'inputType' => 'select',
    'exclude' => true,
    'foreignKey' => 'tl_kiss_company.name',
    'eval' => ['includeBlankOption' => true, 'tl_class' => 'w50 clr'],
    'sql' => ['type' => 'integer', 'unsigned' => true, 'default' => 0],
    'relation' => ['type' => 'hasOne', 'load' => 'lazy'],
];

PaletteManipulator::create()
    ->addLegend('kiss_company_legend', 'metadata_legend', PaletteManipulator::POSITION_APPEND)
    ->addField('kiss_company', 'kiss_company_legend', PaletteManipulator::POSITION_APPEND)
    ->applyToPalette(array_key_exists('rootfallback', $GLOBALS['TL_DCA']['tl_page']['palettes']) ? 'rootfallback' : 'root', 'tl_page')
;
