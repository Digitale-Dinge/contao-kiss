<?php

return [
    'label'          => [
        'de' => [
            'Logo',
            'Zeigt das ein Bild- oder Textlogo',
        ],
        'en' => [
            'Logo',
            'Displays an image or text logo',
        ],
    ],
    'types'          => [ 'module' ],
    'moduleCategory' => 'Header',
    'fields'         => [
        'singleSRC'         => [
            'label'     => [
                'de' => [ 'Logobild', '' ],
                'en' => [ 'Logo image', '' ],
            ],
            'inputType' => 'standardField',
            'eval' => [
                'mandatory' => false,
            ],
        ],
        'text'        => [
            'label'     => [
                'de' => [ 'Logo-Text', '' ],
                'en' => [ 'Logo-Text', '' ],
            ],
            'inputType' => 'text',
            'eval'      => [ 'tl_class' => 'w50 clr' ],
        ],
        'jumpTo'            => [
            'inputType' => 'standardField',
            'eval'      => [ 'tl_class' => 'long clr' ],
        ],
    ],
];
