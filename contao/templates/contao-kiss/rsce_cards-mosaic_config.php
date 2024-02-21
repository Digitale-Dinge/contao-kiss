<?php

\Contao\DcaLoader::loadDataContainer('tl_content');

return [
    'label'           => [
        'de' => [
            'Cards Mosaik',
            'Beinhaltet eine im Mosaik kombinierte Sammlung von Cards',
        ],
        'en' => [
            'Cards Mosaic',
            'Contains a collection of cards combined in a mosaic'
        ],
    ],
    'types'           => ['content'],
    'contentCategory' => 'Cards',
    'standardFields'  => ['headline'],
    'fields' => [
		'text' => [
			'inputType' => 'standardField',
			'eval' =>['mandatory' => false],
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
		'readMore' => [
			'label'     => [
				'de' => ['Abschluss Card einfügen?'],
				'en' => ['Add end Card?'],
			],
			'inputType' => 'checkbox',
			'eval'      => ['tl_class' => 'w50 clr'],
		],
		'readMoreIcon'     => [
			'label'     => ['Icon', ''],
			'inputType' => 'rocksolid_icon_picker',
			'dependsOn' => [
				'field' => 'readMore',
				'value' => true
			],
			'eval'      => [
				'iconFont'  => 'files/theme/dist/fonts/icons/fonts/icons.svg',
				'mandatory' => false,
				'tl_class'  => 'long clr',
			],
		],
		'readMoreUrl'  => array_replace_recursive(
			$GLOBALS['TL_DCA']['tl_content']['fields']['url'],
			['label'     => [
				'de' => ['Linkziel','Die Zielseite der letzten Card'],
				'en' => ['URL','The target page for the end card'],
			]],
			['dependsOn' => [
				'field' => 'readMore',
				'value' => [true]
			]],
			['eval' => [
				'mandatory' => false,
				'tl_class' => 'w50 clr'
			]
			]
		),
		'readMoreText' => [
			'label' =>[
				'de' =>['Card-Text', 'Der Text der Abschluss Card'],
				'en' =>['Card text', 'The text of the end card'],
			],
			'inputType' => 'text',
			'dependsOn' => [
				'field' => 'readMore',
				'value' => true
			],
			'eval' =>['tl_class' => 'w50'],
		],
		'cardSize' => [
			'label'     => [
				'de' => ['Card-Größe'],
				'en' => ['Card size'],
			],
			'inputType' => 'select',
			'options'   => [
				'size-25'   => '25%',
				'size-50' => '50%',
				'size-100' => '100%'
			],
			'dependsOn' => [
				'field' => 'readMore',
				'value' => true
			],
			'eval'      => ['tl_class' => 'w50'],
		],
		'cardHeight' => [
			'label'     => [
				'de' => ['Card-Höhe'],
				'en' => ['Card height'],
			],
			'inputType' => 'select',
			'options'   => [
				'single'   => 'Single',
				'double' => 'Double'
			],
			'dependsOn' => [
				'field' => 'readMore',
				'value' => true
			],
			'eval'      => ['tl_class' => 'w50'],
		],
    	'cards'       => [
        	'label'        => [
            	'de' => ['Cards', 'Fügen Sie eine beliebige Anzahl an Cards ein.'],
            	'en' => ['Cards', 'Add any number of cards.'],
        	],
        	'elementLabel' => [
            	'de' => 'Card %s',
            	'en' => 'card %s',
        	],
        	'inputType'    => 'list',
        	'minItems'     => 1,
        	'fields'       => [
				'cardSize' => [
					'label'     => [
						'de' => ['Card-Größe'],
						'en' => ['Card size'],
					],
					'inputType' => 'select',
					'options'   => [
						'size-25'   => '25%',
						'size-50' => '50%',
						'size-100' => '100%'
					],
					'eval'      => ['tl_class' => 'w50'],
				],
				'cardHeight' => [
					'label'     => [
						'de' => ['Card-Höhe'],
						'en' => ['Card height'],
					],
					'inputType' => 'select',
					'options'   => [
						'single'   => 'Single',
						'double' => 'Double'
					],
					'eval'      => ['tl_class' => 'w50'],
				],
            	'topline' => array_replace_recursive(
                	$GLOBALS['TL_DCA']['tl_content']['fields']['topline'],
                	['eval' => ['mandatory' => false]]
            	),
            	'headline' => array_replace_recursive(
                	$GLOBALS['TL_DCA']['tl_content']['fields']['headline'],
                	['eval' => ['mandatory' => false]]
            	),
				'image' =>[
					'label' =>[
						'de' =>['Hintergrundbild', ''],
						'en' =>['Background image', ''],
					],
					'inputType' => 'fileTree',
					'eval' =>[
						'fieldType' => 'radio',
						'filesOnly' => true,
						'extensions' => 'jpg,jpeg,png,gif,svg',
						'tl_class' => 'clr',
					],
				],
            	'url'  => array_replace_recursive(
                	$GLOBALS['TL_DCA']['tl_content']['fields']['url'],
                	['eval' => ['mandatory' => false]]
            	)
        	],
    	],
    ],
];
