<?php

declare(strict_types=1);

use Contao\System;

$configBuilder = System::getContainer()->get('kiss.rsce_config.builder');
$translator = System::getContainer()->get('translator');

return $configBuilder
    ->create(
        [
            'de' => [
                'Icon',
                'Ein einzelnes Icon mit optionalem Beschreibungstext',
            ],
            'en' => [
                'Icon',
                'A single icon with optional description',
            ],
        ],
        'media',
        [
            'types' => ['content'],
            'standardFields' => ['cssID'],
        ]
    )
    ->addIconField()
    ->addIconPositionField()
    ->addField('text', [
        'label' => [
            'de' => ['Beschreibung', 'Ein beschreibender Text für das Icon'],
            'en' => ['Description', 'A describing text for the icon'],
        ],
        'inputType' => 'textarea',
        'eval' => [
            'tl_class' => 'long',
            'mandatory' => false,
            'allowHtml' => true,
        ],
    ])
    ->build()
;
