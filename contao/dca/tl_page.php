<?php

declare(strict_types=1);

use Contao\CoreBundle\DataContainer\PaletteManipulator;

$GLOBALS['TL_DCA']['tl_page']['fields']['opening_times'] = [
    'inputType' => 'openingTimesTable',
    'eval' => ['tl_class' => 'w50 clr'],
    'sql' => "text NULL",
];

PaletteManipulator::create()
    ->addLegend('company_data_legend', 'metadata_legend', PaletteManipulator::POSITION_APPEND)
    ->addField('opening_times', 'company_data_legend', PaletteManipulator::POSITION_APPEND)
    ->applyToPalette(array_key_exists('rootfallback', $GLOBALS['TL_DCA']['tl_page']['palettes']) ? 'rootfallback' : 'root', 'tl_page')
;
