<?php

\Contao\DcaLoader::loadDataContainer('tl_content');

return [
    'label'           => [
        'de' => [
            'Schwebende Cards',
            'Beinhaltet eine Auflistung von schwebenden Cards',
        ],
        'en' => [
            'Floating Cards',
            'Contains a list of floating cards'
        ],
    ],
    'types'           => ['content'],
    'contentCategory' => 'Cards',
    'standardFields'  => ['headline'],
    'fields'          => [
		'text' => [
			'inputType' => 'standardField',
			'eval' =>['mandatory' => false],
		],
        'textAlign' => [
            'label'     => [
                'de' => ['Textausrichtung'],
                'en' => ['Text align'],
            ],
            'inputType' => 'select',
            'options'   => [
                'left'   => 'Linksbündig',
                'center' => 'Zentriert',
                'right' => 'Rechtsbündig'
            ],
            'eval'      => ['tl_class' => 'w50 clr'],
        ],
        'backgroundColor' => [
            'label'     => [
                    'de' => [ 'Hintergrundfarbe', 'Färbt den Hintergrund des gesamten Elements (nicht der Cards)' ],
                    'en' => [ 'Background color', 'Sets the background color of the whole element (not the cards)' ],
            ],
            'inputType' => 'select',
            'options' => ['transparent', 'color-1', 'color-2', 'color-3', 'color-4'],
            'reference' => &$GLOBALS['TL_LANG']['default']['background-color'],
            'eval'      => [ 'tl_class' => 'w50' ],
        ],
        'cardsLayout' => [
            'label'     => [
                'de' => ['Layout der Kacheln'],
                'en' => ['card layout'],
            ],
            'inputType' => 'select',
            'options'   => [
                'grid'   => 'Im Grid',
                'offset' => 'Versetzt',
            ],
            'eval'      => ['tl_class' => 'w50'],
        ],
        'cards'       => [
            'label'        => [
                'de' => ['Cards', 'Fügen Sie eine beliebige Anzahl an Cards ein.'],
                'en' => ['Cards', 'Add any number of cards.'],
            ],
            'elementLabel' => [
                'de' => 'Card %s',
                'en' => 'card %s',
            ],
            'inputType'    => 'list',
            'minItems'     => 1,
            'fields'       => [
                'icon'     => [
                    'label'     => ['Icon', ''],
                    'inputType' => 'rocksolid_icon_picker',
                    'eval'      => [
                        'iconFont'  => 'files/theme/dist/fonts/icons/fonts/icons.svg',
                        'mandatory' => false,
                        'tl_class'  => 'long clr',
                    ],
                ],
                'topline' => array_replace_recursive(
                    $GLOBALS['TL_DCA']['tl_content']['fields']['topline'],
                    ['eval' => ['mandatory' => false]]
                ),
                'headline' => array_replace_recursive(
                    $GLOBALS['TL_DCA']['tl_content']['fields']['headline'],
                    ['eval' => ['mandatory' => false]]
                ),
                'text' => array_replace_recursive(
                    $GLOBALS['TL_DCA']['tl_content']['fields']['text'],
                    ['eval' => ['mandatory' => false]]
                ),

                'url'  => array_replace_recursive(
                    $GLOBALS['TL_DCA']['tl_content']['fields']['url'],
                    ['eval' => ['mandatory' => false]]
                )
            ],
        ],

    ],
];
