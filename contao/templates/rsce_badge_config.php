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

    ->addEnumField('badgeSize', Size::class)
    // Badge shapes per _badge.scss, the blank option keeps the default corner radius
    ->addDependsOnField('badgeShape', ['', 'pill', 'square'], ['tl_class' => 'w25'])

    ->startList()
        ->addEnumField('color', Color::class)
        // Badge styles per _badge.scss: the shared soft/outline, extended with a badge-only dashed
        ->addDependsOnField('variant', ['', 'soft', 'outline', 'dashed'], ['tl_class' => 'w25'])
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
