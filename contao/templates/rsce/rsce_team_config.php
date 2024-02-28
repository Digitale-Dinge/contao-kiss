<?php

return [
	'label' => [
		'de' => [
			'Mitarbeitende',
			'Eine Liste von Personen mit Foto, Name, E-Mail und Social-Media-Links',
		],
		'en' => [
			'Staff',
			'A list of employees with picture, name email and social media links',
		],
	],
	'types' => ['content'],
	'contentCategory' => 'texts',
	'standardFields' => ['headline', 'cssID'],
	'fields' => [
		'members' => [
			'label' => [
				'de' => [
					'Personen',
					'Fügen Sie eine beliebige Anzahl an Mitarbeitenden ein.',
				],
				'en' => [
					'Members',
					'Add any number of employees.',
				],
			],
			'elementLabel' => [
				'de' => 'Mitarbeiter %s',
				'en' => 'Employee %s',
			],
			'inputType' => 'list',
			'fields' => [
				'image' => [
					'label' => [
						'de' => ['Profilbild', ''],
						'en' => ['Profile picture', ''],
					],
					'inputType' => 'fileTree',
					'eval' => [
						'fieldType' => 'radio',
						'filesOnly' => true,
						'extensions' => 'jpg,jpeg,png,gif,svg',
					],
				],
				'firstname' => [
					'label' => [
						'de' => ['(Titel) Vorname', 'Vorname des Mitarbeitenden'],
						'en' => ['(Title) First name', 'First name of employee'],
					],
					'inputType' => 'text',
					'eval' => ['tl_class' => 'w50'],
				],
				'lastname' => [
					'label' => [
						'de' => ['Nachname', 'Nachname des Mitarbeitenden'],
						'en' => ['Last name', 'Last name of employee'],
					],
					'inputType' => 'text',
					'eval' => ['tl_class' => 'w50'],
				],
				'occupation' => [
					'label' => [
						'de' => ['Position', 'Position des Mitarbeitenden'],
						'en' => ['Occupation', 'Occupation of employee'],
					],
					'inputType' => 'text',
					'eval' => [
						'tl_class' => 'w100 clr',
						'allowHtml' => true,
					],
				],
				'email' => [
					'label' => [
						'de' => ['E-Mail', 'E-Mail-Addresse des Mitarbeitenden'],
						'en' => ['Email', 'Email address of employee'],
					],
					'inputType' => 'text',
					'eval' => ['tl_class' => 'w50'],
				],
				'phone' => [
					'label' => [
						'de' => ['Telefon', 'Telefonnummer des Mitarbeitenden'],
						'en' => ['Email', 'Email address of employee'],
					],
					'inputType' => 'text',
					'eval' => ['tl_class' => 'w50'],
				],
			],
		],
	],
];
