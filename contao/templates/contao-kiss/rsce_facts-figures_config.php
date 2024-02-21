<?php

return [
    'label'           => [
        'de' => [
            'Fakten & Zahlen',
            'Eine animierte Zahl mit Hintergrundbild',
        ],
        'en' => [
            'Facts and figures',
            'An animated number with background image',
        ],
    ],
    'types'           => [ 'content' ],
    'contentCategory' => 'slick',
    'fields'          => [
        'number'  => [
            'label'     => [
                'de' => [ 'Attribut', '' ],
                'en' => [ 'Attribute', '' ],
            ],
            'inputType' => 'text',
            'eval'      => [
                'rte'       => false,
                'mandatory' => true,
                'allowHtml' => true,
                'tl_class'  => 'w50'
            ],
        ],
        'unit'    => [
            'label'     => [
                'de' => [ 'Einheit', '' ],
                'en' => [ 'Unit', '' ],
            ],
            'inputType' => 'text',
            'eval'      => [
                'rte'       => false,
                'mandatory' => false,
                'allowHtml' => true,
                'tl_class'  => 'w50'
            ],
        ],
        'subline' => [
            'label'     => [
                'de' => [ 'Subline', 'Die Subline ergänzt die Überschrift.' ],
                'en' => [ 'Subline', 'The subline completes the headline.' ],
            ],
            'inputType' => 'text',
            'eval'      => [
                'rte'       => false,
                'mandatory' => true,
                'allowHtml' => true,
                'tl_class'  => 'clr long'
            ],
        ],
    ],
];
