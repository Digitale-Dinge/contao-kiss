<?php

return
[

    'label' =>
    [
        'de' => [
            'Zitatbox',
            'Eine Zitatbox',
        ],
        'en' => [
            'Quotebox',
            'A quote box.',
        ],
    ],
    'types'           => ['content'],
    'contentCategory' => 'texts',
    'standardFields' => ['headline'],
    'fields'          =>
    [
        'text'        => [
            'label'     => [
                'de' => [ 'Zitat-Text', '' ],
                'en' => [ 'Text of the quote', '' ],
            ],
            'inputType' => 'textarea',
            'eval'      => [ 
                'tl_class' => 'w100 clr',
                'rte' => 'tinyMCE'
            ],
        ],
        'singleSRC' =>
        [
            'inputType' => 'standardField',
            'label'     => [
                'de' => [ 'Bild der zitierten Person', '' ],
                'en' => [ 'Image of the quoted person', '' ],
            ],
        ],
        'name' =>
        [
            'label' =>
            [
                'de' => ['Name der zitierten Person'],
                'en' => ['Name of the quoted person']
            ],
            'inputType' => 'text',
            'eval'      => [ 
                'tl_class' => 'w50'
            ],
        ],
        'desc' =>
        [
            'label' =>
            [
                'de' => ['Titel der zitierten Person'],
                'en' => ['title of the quoted person']
            ],
            'inputType' => 'text',
            'eval'      => [ 
                'tl_class' => 'w50'
            ],
        ],
        'elementSettings' => [
            'label' => [
                'de' => [
                    'Element Einstellungen',
                    '',
                ],
                'en' => [
                    'Element settings',
                    '',
                ],
            ],
            'inputType' => 'group'
        ],
        'backgroundColor' => [
            'label'     => [
                    'de' => [ 'Hintergrundfarbe', 'Färbt den Hintergrund des Elements' ],
                    'en' => [ 'Background color', 'Sets the background color of the element' ],
            ],
            'inputType' => 'select',
            'options' => ['transparent', 'color-1', 'color-2', 'color-3', 'color-4'],
            'reference' => &$GLOBALS['TL_LANG']['default']['background-color'],
            'eval'      => [ 'tl_class' => 'w50' ],
        ],
    ]

];
