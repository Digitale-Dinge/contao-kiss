<?php

return [
    'label'           => [
        'de' => [
            'Kacheln',
            'Eine Sammlung von Kacheln, die gemeinsam dargestellt werden.',
        ],
        'en' => [
            'Tiles',
            'A group of tiles that will be displayed together.',
        ],
    ],
    'types'           => [ 'content' ],
    'contentCategory' => 'Tiles',
    'standardFields'  => [ 'headline','text' ],
    'fields'          => [
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
        'tilesLayout'        => [
          'label'     => [
              'de' => [ 'Layout der Kacheln' ],
              'en' => [ 'Tile layout' ],
          ],
          'inputType' => 'select',
          'options' => [
            'single' => 'Einzeln',
            'grouped' => 'Gruppiert',
          ],
          'eval'      => [ 'tl_class' => 'w50 m12' ],
        ],
        'tilesPerRow'        => [
          'label'     => [
              'de' => [ 'Kacheln pro Reihe' ],
              'en' => [ 'Tiles per row' ],
          ],
          'inputType' => 'select',
          'options' => ['cols_2', 'cols_3', 'cols_4', 'cols_5', 'cols_6', 'cols_7', 'cols_8', 'cols_9', 'cols_10', 'cols_11', 'cols_12'],
          'reference' => &$GLOBALS['TL_LANG']['default']['number-of-cols'],
          'eval'      => [ 'tl_class' => 'w50 m12' ],
        ],
        
        'tiles'         => [
            'label'        => [
                'de' => [
                    'Kacheln',
                    'Fügen Sie eine beliebige Anzahl an Kacheln ein.',
                ],
                'en' => [
                    'Tiles',
                    'Add any number of tiles.',
                ],
            ],
            'elementLabel' => [
                'de' => 'Kachel %s',
                'en' => 'Tile %s',
            ],
            'inputType'    => 'list',
            'fields'       => [
                'icon' => [
                    'label'     => [ 'Icon', '' ],
                    'inputType' => 'rocksolid_icon_picker',
                    'eval'      => [
                        'iconFont'  => 'files/theme/dist/fonts/icons/fonts/icons.svg',
                        'mandatory' => true,
                        'tl_class'  => 'long clr',
                    ],
                ],
                'headline' => [
                    'label'     => [
                        'de' => [ 'Überschrift', 'Geben Sie die Überschrift der Kachel ein' ],
                        'en' => [ 'Headline', 'Enter the tile\'s headline.' ],
                    ],
                    'inputType' => 'text',
                    'eval'      => [
                        'tl_class'  => 'w100 clr',
                        'mandatory' => false,
                        'allowHtml' => true
                    ],
                ],
                'text' => [
                    'label'     => [
                        'de' => [ 'Text', 'Geben sie den Kacheltext ein.' ],
                        'en' => [ 'text', 'Enter tile\'s text.' ],
                    ],
                    'inputType' => 'textarea',
                    'eval'      => [
                        'tl_class'  => 'long clr',
                        'mandatory' => false,
                        'allowHtml' => true
                    ],
                ],
                'icontext' => [
                    'label'     => [
                        'de' => [ 'Info-Text', 'Text, der nach Klick auf ein Icon angezeigt wird.' ],
                        'en' => [ 'Info text', 'Text that will be shown after clicking an icon' ],
                    ],
                    'inputType' => 'textarea',
                    'eval'      => [
                        'tl_class'  => 'long clr',
                        'mandatory' => false,
                        'allowHtml' => true,
                        'rte'       => 'tinyMCE'
                    ],
                ],
            ],
        ],
    ],
];
