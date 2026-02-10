<?php

declare(strict_types=1);

return [
    'label' => [
        'de' => [
            'Icon',
            'Ein einzelnes Icon mit optionalem Beschreibungstext',
        ],
        'en' => [
            'Icon',
            'A single icon with optional description',
        ],
    ],
    'types' => ['content'],
    'contentCategory' => 'media',
    'standardFields' => ['cssID'],
    'fields' => [
        'icon' => [
            'label' => [
                'de' => ['Icon', 'Hier können Sie ein Icon auswählen.'],
                'en' => ['Icon', 'Here you can choose an icon.'],
            ],
            'inputType' => 'svgIconPicker',
            'eval' => [
                'sourceDirectory' => 'public/kiss_icons/svg',
                'metadataDirectory' => 'public/kiss_icons',
                'tl_class' => 'w75 clr',
            ],
        ],
        'position' => [
            'default' => 'left',
            'label' => [
                'de' => ['Position', 'Hier können Sie die Position des Icons auswählen.'],
                'en' => ['Position', 'Here you can choose the position of the icon.'],
            ],
            'inputType' => 'select',
            'options' => [
                'left' => 'Left',
                'right' => 'Right',
            ],
            'eval' => [
                'tl_class' => 'w25',
            ],
        ],
        'text' => [
            'label' => [
                'de' => ['Beschreibung', 'Ein beschreibender Text für das Icon'],
                'en' => ['Description', 'A describing text for the icon'],
            ],
            'inputType' => 'textarea',
            'eval' => [
                'tl_class' => 'long clr',
                'mandatory' => false,
                'allowHtml' => true,
            ],
        ],
    ],
];
