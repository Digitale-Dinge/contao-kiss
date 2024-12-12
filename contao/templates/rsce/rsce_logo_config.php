<?php

return [
    'label'          => [
        'de' => [
            'Logo',
            'Zeigt das Logo optional mit Image Replacement',
        ],
        'en' => [
            'Logo',
            'Show logo image optional with image replacments',
        ],
    ],
    'types'          => [ 'module' ],
    'moduleCategory' => 'Header',
    'fields'         => [
        'shortcut'          => [
            'label'     => [
                'de' => [ 'Shortcut', '' ],
                'en' => [ 'Shortcut', '' ],
            ],
            'inputType' => 'group',
        ],
        'has_shortcut'      => [
            'inputType' => 'standardField',
        ],
        'position_shortcut' => [
            'inputType' => 'standardField',
        ],
        'singleSRC'         => [
            'label'     => [
                'de' => [ 'Logobild', '' ],
                'en' => [ 'Logo image', '' ],
            ],
            'inputType' => 'standardField',
        ],
        'imgSize'           => [
            'inputType' => 'standardField',
            'eval'      => [ 'tl_class' => 'w50' ],
        ],
        'text'        => [
            'label'     => [
                'de' => [ 'Logo-Text', '' ],
                'en' => [ 'Logo-Text', '' ],
            ],
            'inputType' => 'text',
            'eval'      => [ 'tl_class' => 'w50 clr' ],
        ],
        'imgReplace'        => [
            'label'     => [
                'de' => [ 'Text ersetzen', 'Den Text durch das Logobild ersetzen.' ],
                'en' => [ 'Image replacement', 'Replace text with logo image.' ],
            ],
            'inputType' => 'checkbox',
            'eval'      => [ 'tl_class' => 'w50 m12' ],
        ],
        'jumpTo'            => [
            'inputType' => 'standardField',
            'eval'      => [ 'tl_class' => 'long clr' ],
        ],
    ],
];
