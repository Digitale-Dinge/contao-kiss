<?php

declare(strict_types=1);

use Contao\System;
use DigitaleDinge\ContaoKiss\Styles\Option\Color\Color;
use DigitaleDinge\ContaoKiss\Styles\Option\Modifier\Size;

$configBuilder = System::getContainer()->get('kiss.rsce_config.builder');

return $configBuilder
    ->create('badge', 'texts', [
        'types' => ['content'],
        'standardFields' => ['cssID'],
    ])

    ->addStyleOptionsField('badgeSize', Size::class)
    ->addSelectField('badgeShape', ['', 'pill', 'square'], ['tl_class' => 'w25'])

    ->startList()
        ->addStyleOptionsField('color', Color::class)
        ->addSelectField('variant', ['', 'soft', 'outline', 'dashed'], ['tl_class' => 'w25'])
        ->addIconField()
        ->addField('text', [
            'label' => true,
            'inputType' => 'text',
            'eval' => [
                'tl_class' => 'w50',
                'maxlength' => 50,
            ],
        ])
    ->endList()

    ->build()
;
