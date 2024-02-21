<?php

return[
	'label' =>[
		'de' =>[
			'Card Schnellkontakt',
			'Eine Card mit Kontaktinformationen und einem Formular für Schnellkontakt',
		],
		'en' =>[
			'Card Quick contact',
			'A card with contact information and a form for quick contact',
		],
	],
	'types' =>['content'],
	'contentCategory' => 'Cards',
	'standardFields' => ['headline'],
	'fields' =>[
		'image' =>[
			'label' =>[
				'de' =>['Profilbild', ''],
				'en' =>['Profile picture', ''],
			],
			'inputType' => 'fileTree',
			'eval' =>[
				'fieldType' => 'radio',
				'filesOnly' => true,
				'extensions' => 'jpg,jpeg,png,gif,svg',
				'tl_class' => 'clr',
			],
		],
		'firstname' =>[
			'label' =>[
				'de' =>['(Titel) Vorname', 'Vorname des Mitarbeitenden'],
				'en' =>['(Title) First name', 'First name of employee'],
			],
			'inputType' => 'text',
			'eval' =>['tl_class' => 'w50'],
		],
		'lastname' =>[
			'label' =>[
				'de' =>['Nachname', 'Nachname des Mitarbeitenden'],
				'en' =>['Last name', 'Last name of employee'],
			],
			'inputType' => 'text',
			'eval' =>['tl_class' => 'w50'],
		],
		'occupation' =>[
			'label' =>[
				'de' =>['Position', 'Position des Mitarbeitenden'],
				'en' =>['Occupation', 'Occupation of employee'],
			],
			'inputType' => 'text',
			'eval' =>['tl_class' => 'w100 clr'],
		],
		'email' =>[
			'label' =>[
				'de' =>['E-Mail', 'E-Mail-Addresse des Mitarbeitenden'],
				'en' =>['Email', 'Email address of employee'],
			],
			'inputType' => 'text',
			'eval' =>['tl_class' => 'w50'],
		],
		'phone' =>[
			'label' =>[
				'de' =>['Telefon', 'Telefonnummer des Mitarbeitenden'],
				'en' =>['Email', 'Email address of employee'],
			],
			'inputType' => 'text',
			'eval' =>['tl_class' => 'w50'],
		],
	],
];
