<?php

\Contao\DcaLoader::loadDataContainer('tl_content');


return
[
    'label' =>
    [
        'de' =>
        [
            'Card Text/Bild',
            'Beinhaltet eine Card mit Text/Bild-Kombination',
        ],
        'en' =>
        [
            'Card text and image',
            'Contains a card with a combination of text and image',
        ],
    ],
    'types'           => [ 'content' ],
    'contentCategory' => 'Cards',
    'standardFields' => ['headline'],
    'fields'          =>
    [
        'icon' => [
            'label'     => [ 'Icon', '' ],
            'inputType' => 'rocksolid_icon_picker',
            'eval'      => [
                'iconFont'  => 'files/theme/dist/fonts/icons/fonts/icons.svg',
                'mandatory' => false,
                'tl_class'  => 'long clr',
            ],
        ],
        'text'     => [
            'inputType' => 'text',
            'label' => [
                'de' => [
                    'Text',
                    'Fügen Sie einen beliebigen Text ein.',
                ],
                'en' => [
                    'Text',
                    'Add any amount of text.',
                ],
            ],
            'eval'      => [
                'mandatory' => false,
                'rte' => 'tinyMCE'
            ]
        ],
        'image'    => $GLOBALS['TL_DCA']['tl_content']['fields']['singleSRC'],
        'links' => [
            'label' => [
                'de' => [
                    'Optionale Links',
                    '',
                ],
                'en' => [
                    'Optional links',
                    '',
                ],
            ],
            'inputType' => 'group'
        ],
        'url' => [
            'label' => [
                'de' => [
                    'Link 1: Adresse',
                    'Geben Sie die URL der Adresse an. Kann mit https:// oder auch mailto: oder tel: beginnen.',
                ],
                'en' => [
                    'Link 1: Address',
                    'Enter the address of the link. Can begin with https:// or also mailto: or tel:.',
                ],
            ],
            'inputType' => 'url',
            'eval'      => [
                'mandatory' => false,
                'tl_class'  => 'w50 clr',
            ],
        ],
        'linkText'     => [
            'label' => [
                'de' => [
                    'Link 1: Text',
                    'Geben Sie den Text des Links an.',
                ],
                'en' => [
                    'Link 1: Text',
                    'Enter the text of the link.',
                ],
            ],
            'inputType' => 'text',
            'eval'      => [
                'tl_class'  => 'w50',
                'mandatory' => false
            ]
        ],
        'url2' => [
            'label' => [
                'de' => [
                    'Link 2: Adresse',
                    'Geben Sie die URL der Adresse an. Kann mit https:// oder auch mailto: oder tel: beginnen.',
                ],
                'en' => [
                    'Link 2: Address',
                    'Enter the address of the link. Can begin with https:// or also mailto: or tel:.',
                ],
            ],
            'inputType' => 'url',
            'eval'      => [
                'mandatory' => false,
                'tl_class'  => 'w50 clr',
            ],
        ],
        'linkText2'     => [
            'label' => [
                'de' => [
                    'Link 2: Text',
                    'Geben Sie den Text des Links an.',
                ],
                'en' => [
                    'Link 2: Text',
                    'Enter the text of the link.',
                ],
            ],
            'inputType' => 'text',
            'eval'      => [
                'tl_class'  => 'w50',
                'mandatory' => false
            ]
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
        'contentAlign' => [
            'label' => [
                'de' => [
                    'Inhaltsausrichtung',
                    'Definieren Sie in welcher Reihenfolge Text und Bild erscheinen sollen.',
                ],
                'en' => [
                    'Content align',
                    'Define the order in which the text and image should appear.',
                ],
            ],
            'inputType' => 'select',
            'options'   => [
                'left'   => 'Bild links, Text rechts',
                'right' => 'Text links, Bild rechts'
            ],
            'eval'      => ['tl_class' => 'w50 clr'],
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
        'cardLayout' => [
            'label'     => [
                    'de' => [ 'Card Layout', 'Ändert das Layout der Card' ],
                    'en' => [ 'Card layout', 'Changes the layout of the card' ],
            ],
            'inputType' => 'select',
            'options' => [
                'layout-1' => 'Layout 1',
                'layout-2' => 'Layout 2',
            ],
            'eval'      => [ 'tl_class' => 'w50' ],
        ],

    ]
];
