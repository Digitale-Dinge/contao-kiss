<?php

\Contao\DcaLoader::loadDataContainer('tl_content');


$GLOBALS['TL_DCA']['tl_article']['fields']['bgColor'] =
[
    'exclude'   => true,
    'inputType' => 'select',
    'options' => ['transparent', 'color-1', 'color-2', 'color-3', 'color-4'],
	'reference' => &$GLOBALS['TL_LANG']['default']['background-color'],
    'eval'      =>
    [
        'tl_class'           => 'w50',
        'includeBlankOption' => true
    ],
    'sql' => "varchar(32) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_article']['fields']['contentWidth']  = $GLOBALS['TL_DCA']['tl_content']['fields']['contentWidth'];
$GLOBALS['TL_DCA']['tl_article']['fields']['paddingTop']    = $GLOBALS['TL_DCA']['tl_content']['fields']['paddingTop'];
$GLOBALS['TL_DCA']['tl_article']['fields']['paddingBottom'] = $GLOBALS['TL_DCA']['tl_content']['fields']['paddingBottom'];
