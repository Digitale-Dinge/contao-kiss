<?php

declare(strict_types=1);

return [
    'label' => [
        'de' => [
            'Medium-Text',
            'Ein Medium-Text Element',
        ],
        'en' => [
            'Media-Text',
            'A media-text element',
        ],
    ],
    'types' => ['content'],
    'contentCategory' => 'media',
    'standardFields' => ['headline', 'topline', 'cssID'],
    'fields' => [
        'type' => [
            'label' => [
                'de' => ['Typ', ''],
                'en' => ['Type', ''],
            ],
            'inputType' => 'select',
            'options' => [
                'image' => 'Image',
                'icon' => 'Icon',
                'separated' => 'Separated',
            ],
            'eval' => [
                'includeBlankOption' => true,
                'tl_class' => 'w50 clr',
            ],
        ],
        'item_image' => [
            'dependsOn' => [
                'field' => 'type',
                'value' => 'image',
            ],
            'label' => [
                'de' => ['Bild', 'Please select an image from the files directory.'],
                'en' => ['Image', 'Bitte wählen Sie ein Bild aus der Dateiübersicht.'],
            ],
            'inputType' => 'fileTree',
            'eval' => [
                'filesOnly' => true,
                'fieldType' => 'radio',
                'extensions' => '%contao.image.valid_extensions%',
                'tl_class' => 'long clr',
            ],
        ],
        'item_icon' => [
            'dependsOn' => [
                'field' => 'type',
                'value' => 'icon',
            ],
            'label' => [
                'de' => ['Icon', 'Hier können Sie ein Icon auswählen.'],
                'en' => ['Icon', 'Here you can choose an icon.'],
            ],
            'inputType' => 'svgIconPicker',
            'eval' => [
                'sourceDirectory' => 'public/kiss_icons/svg',
                'metadataDirectory' => 'public/kiss_icons',
                'tl_class' => 'long clr',
            ],
        ],
        'item_headline' => [
            'inputType' => 'collection',
            'label' => [
                'de' => ['Überschrift', ''],
                'en' => ['Headline', ''],
            ],
            'fields' => [
                'value' => [
                    'label' => [
                        &$GLOBALS['TL_LANG']['tl_content']['headline'][0], null,
                    ],
                    'inputType' => 'text',
                    'eval' => [
                        'maxlength' => 200,
                        'basicEntities' => true,
                    ],
                ],
                'unit' => [
                    'label' => [
                        &$GLOBALS['TL_LANG']['tl_content']['headline']['unit'], null,
                    ],
                    'inputType' => 'select',
                    'options' => ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
                ],
                'appearance' => [
                    'label' => [
                        &$GLOBALS['TL_LANG']['tl_content']['headline']['appearance'], null,
                    ],
                    'inputType' => 'select',
                    'eval' => [
                        'includeBlankOption' => true,
                    ],
                ],
            ],
            'eval' => [
                'tl_class' => 'w50 clr hl_collection',
            ],
        ],
        'item_topline' => [
            'label' => [
                'de' => ['Topline', ''],
                'en' => ['Topline', ''],
            ],
            'inputType' => 'text',
            'eval' => [
                'tl_class' => 'w50',
                'maxlength' => 255,
                'allowHtml' => true,
            ],
        ],
        'item_text' => [
            'label' => [
                'de' => ['Text', 'Sie können HTML-Tags verwenden, um den Text zu formatieren.'],
                'en' => ['Text', 'You can use HTML tags to format the text.'],
            ],
            'inputType' => 'textarea',
            'eval' => [
                'mandatory' => true,
                'rte' => 'tinyMCE',
                'helpwizard' => true,
                'tl_class' => 'clr',
            ],
            'explanation' => 'insertTags',
        ],
    ],
];
