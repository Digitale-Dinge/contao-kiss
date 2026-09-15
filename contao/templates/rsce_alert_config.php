<?php

declare(strict_types=1);

use Contao\System;
use DigitaleDinge\ContaoKiss\Styles\Option\Color\Color;
use DigitaleDinge\ContaoKiss\Styles\Option\Modifier\Size;

$configBuilder = System::getContainer()->get('kiss.rsce_config.builder');

return $configBuilder
    ->create('alert', 'texts', [
        'types' => ['content'],
        'standardFields' => ['cssID'],
    ])

    ->addStyleOptionsField('color', Color::class)
    // Alert styles per _alert.scss: the shared soft/outline, extended with an alert-only dashed
    ->addDependsOnField('variant', ['', 'soft', 'outline', 'dashed'], ['tl_class' => 'w25'])
    ->addStyleOptionsField('alertSize', Size::class)
    ->addIconField()
    ->addField('title', [
        'label' => true,
        'inputType' => 'text',
        'eval' => [
            'tl_class' => 'w50 clr',
            'mandatory' => false,
        ],
    ])
    ->addField('text', [
        'label' => true,
        'inputType' => 'textarea',
        'eval' => [
            'tl_class' => 'long clr',
            'mandatory' => false,
            'allowHtml' => true,
        ],
    ])

    ->build()
;
