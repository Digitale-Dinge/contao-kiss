<?php

\Contao\DcaLoader::loadDataContainer('tl_content');

return
[
    'label' =>
    [
        'de' => [
            'Hero-Teaser',
            '',
        ],
        'en' => [
            'Hero-Teaser',
            '',
        ],
    ],
    'types'           => ['content'],
	'standardFields'  => ['headline'],
    'contentCategory' => 'contentTeaser',
    'fields' =>
    [
        'teaserLayout' =>
        [
            'label' =>
            [
                'de' => ['Art des Elements'],
                'en' => ['Type of element'],
            ],
            'inputType' => 'select',
            'options'   =>
            [
                'homepage' => 'Startseite',
                'subpage'  => 'Unterseite',
            ],
            'eval' => ['tl_class' => 'w50'],
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
			'eval'      => ['tl_class' => 'w50'],
		],
		'text' => [
			'label' =>
			[
				'de' => ['Text'],
				'en' => ['Text'],
			],
			'inputType' => 'textarea',
			'eval' => [
				'tl_class' => 'w100 clr',
				'rte' => false
			],
		],
		'mediaType' => [
			'label'     => [
				'de' => ['Medienart'],
				'en' => ['Type of media'],
			],
			'inputType' => 'select',
			'options'   => [
				'image'   => 'Bild',
				'video' => 'Video',
				'color' => 'Farbe'
			],
			'eval'      => ['tl_class' => 'w50'],
		],
		'colorOverlay' => [
			'label'     => [
					'de' => [ 'Farbüberlagerung', 'Überlagert den Hintergrund mit einer halb-transparenten Farbe um den Kontrast zum Inhalt zu erhöhen' ],
					'en' => [ 'Color overlay', 'Overlays the background with a semi-transparent color to increase the contrast to the content' ],
			],
			'inputType' => 'select',
			'options' => ['transparent', 'color-1', 'color-2', 'color-3', 'color-4'],
			'reference' => &$GLOBALS['TL_LANG']['default']['background-color'],
			'eval'      => [ 'tl_class' => 'w50 clr' ],
			'dependsOn' => [
				'field' => 'mediaType',
				'value' => ['image', 'video']
			],
		],
		'overlayType' => [
			'label'     => [
					'de' => [ 'Art der Farbüberlagerung', 'In welchem Modus soll die Farbe über den Inhalt gelegt werden?' ],
					'en' => [ 'Type of color overlay', 'In which mode should the color be placed over the content?' ],
			],
			'inputType' => 'select',
			'options' => ['normal', 'darken', 'multiply', 'lighten'],
			'reference' => &$GLOBALS['TL_LANG']['default']['overlay-type'],
			'eval'      => [ 'tl_class' => 'w50' ],
			'dependsOn' => [
				'field' => 'mediaType',
				'value' => ['image', 'video']
			],
		],
		'singleSRC' => [
			'inputType' => 'standardField',
			'label' => [
				'de' => ['Hintergrundbild', 'Definieren Sie ein Hintergrundbild für den Teaser'],
				'en' => ['Background image', 'Select a background image for the teaser'],
			],
			'eval'	=> [
				'tl_class' => 'w100 clr',

			],
			'dependsOn' => [
				'field' => 'mediaType',
				'value' => 'image',
			],
		],
		'backgroundColor' => [
			'label'     => [
					'de' => [ 'Hintergrundfarbe', 'Färbt den Hintergrund des gesamten Elements (nicht der Cards)' ],
					'en' => [ 'Background color', 'Sets the background color of the whole element (not the cards)' ],
			],
			'inputType' => 'select',
			'options' => ['transparent', 'color-1', 'color-2', 'color-3', 'color-4'],
			'reference' => &$GLOBALS['TL_LANG']['default']['background-color'],
			'eval'      => [ 'tl_class' => 'w50 clr' ],
			'dependsOn' => [
				'field' => 'mediaType',
				'value' => 'color',
			],
		],
		'video' => [
			'inputType' => 'fileTree',
			'label' => [
				'de' => ['Hintergrundvideo', 'Definieren Sie ein Hintergrundvideo für den Teaser'],
				'en' => ['Background video', 'Select a background video for the teaser'],
			],
			'eval'	=> [
				'tl_class' => 'w100 clr',
				'fieldType' => 'radio',
				'filesOnly' => true,
				'extensions' => 'mp4,webm',
			],
			'dependsOn' => [
				'field' => 'mediaType',
				'value' => 'video',
			],
		],	
		
        'buttons'  => [
            'label'        => [
                'de' => [
                    'Call-to-Actions',
                    'Fügen Sie eine beliebige Anzahl an CTAs ein.',
                ],
                'en' => [
                    'CTAs',
                    'Add any number of CTAs.',
                ],
            ],
            'elementLabel' => [
                'de' => 'CTA %s',
                'en' => 'CTA %s',
            ],
            'inputType'    => 'list',
            'fields'       =>
            [
                'linktext' => array_replace_recursive(
                    $GLOBALS['TL_DCA']['tl_content']['fields']['linkTitle'],
                    ['eval' => ['mandatory' => true]]
                ),
                'url'      => $GLOBALS['TL_DCA']['tl_content']['fields']['url'],
                'type'     => [
                    'label'     => [
                        'de' => ['Button-Typ'],
                        'en' => ['Type of button']
                    ],
                    'inputType' => 'select',
                    'options'   => [
                        'primary'   => 'Primär',
                        'secondary' => 'Sekundär',
                        'tertiary'  => 'Tertiär'
                    ],
                    'eval'      => [
                        'tl_class'  => 'w50',
                        'mandatory' => true
                    ],
                ],
            ],
        ],
    ],
];
