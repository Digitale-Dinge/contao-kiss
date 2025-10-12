<?php

\Contao\DcaLoader::loadDataContainer('tl_content');


$GLOBALS['TL_DCA']['tl_article']['fields']['bgColor'] =
[
    'exclude'   => true,
		"label" => &$GLOBALS["TL_LANG"]["tl_article"]["bgColor"],
    'inputType' => 'select',
    'options' => ['transparent', 'background-white', 'background-primary', 'background-secondary', 'background-additional-1', 'background-additional-2'],
		'reference' => &$GLOBALS['TL_LANG']['default']['background-color'],
    'eval'      =>
    [
        'tl_class'           => 'w25',
        'includeBlankOption' => true
    ],
    'sql' => "varchar(32) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_article']['fields']['contentWidth']  = $GLOBALS['TL_DCA']['tl_content']['fields']['contentWidth'];
$GLOBALS['TL_DCA']['tl_article']['fields']['paddingTop']    = $GLOBALS['TL_DCA']['tl_content']['fields']['paddingTop'];
$GLOBALS['TL_DCA']['tl_article']['fields']['paddingBottom'] = $GLOBALS['TL_DCA']['tl_content']['fields']['paddingBottom'];
