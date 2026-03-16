<?php

declare(strict_types=1);

use Contao\CoreBundle\DataContainer\PaletteManipulator;

$GLOBALS['TL_DCA']['tl_user']['fields']['show_kiss_grid'] = [
    'inputType' => 'checkbox',
    'eval' => ['tl_class' => 'w50'],
    'sql' => ['type' => 'boolean', 'default' => true]
];

PaletteManipulator::create()
    ->addField('show_kiss_grid', 'backend_legend', PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('login', 'tl_user')
    ->applyToPalette('admin', 'tl_user')
    ->applyToPalette('default', 'tl_user')
    ->applyToPalette('group', 'tl_user')
    ->applyToPalette('extend', 'tl_user')
    ->applyToPalette('custom', 'tl_user')
;
