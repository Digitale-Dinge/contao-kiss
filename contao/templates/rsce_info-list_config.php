<?php

return [
    'label'           => [
        'de' => [
            'Info-Liste mit Icons',
            'Eine Liste von Infos oder Kontaktdaten, jeweils mit Icon, Titel und Text.',
        ],
        'en' => [
            'Info list with icons',
            'A list of info or contact items, each with an icon, title and text.',
        ],
    ],
    'types'           => [ 'content' ],
    'contentCategory' => 'texts',
    'standardFields'  => [ 'cssID' ],
    'fields'          => [
        'listLayout' => [
            'label'     => [
                'de' => [ 'Layout', 'Wählen Sie zwischen einer kompakten (Mini) und einer großen Darstellung.' ],
                'en' => [ 'Layout', 'Choose between a compact (mini) and a large display.' ],
            ],
            'inputType' => 'select',
            'options'   => [
                'mini'  => 'Mini',
                'large' => 'Groß',
            ],
            'eval'      => [ 'tl_class' => 'w50' ],
        ],
        'items'      => [
            'label'        => [
                'de' => [
                    'Einträge',
                    'Fügen Sie eine beliebige Anzahl an Einträgen ein.',
                ],
                'en' => [
                    'Items',
                    'Add any number of items.',
                ],
            ],
            'elementLabel' => [
                'de' => 'Eintrag %s',
                'en' => 'Item %s',
            ],
            'inputType'    => 'list',
            'minItems'     => 1,
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
                        'de' => [ 'Titel', 'Geben Sie den Titel des Eintrags ein, z. B. "Telefon".' ],
                        'en' => [ 'Title', 'Enter the item\'s title, e.g. "Phone".' ],
                    ],
                    'inputType' => 'text',
                    'eval'      => [
                        'tl_class'  => 'w100 clr',
                        'mandatory' => true,
                    ],
                ],
                'text' => [
                    'label'     => [
                        'de' => [ 'Text', 'Geben Sie den Text des Eintrags ein, z. B. Adresse, Telefonnummer oder Öffnungszeiten.' ],
                        'en' => [ 'Text', 'Enter the item\'s text, e.g. address, phone number or opening hours.' ],
                    ],
                    'inputType' => 'textarea',
                    'eval'      => [
                        'tl_class'  => 'long clr',
                        'mandatory' => false,
                        'allowHtml' => true,
                        'rte'       => 'tinyMCE',
                    ],
                ],
            ],
        ],
    ],
];
