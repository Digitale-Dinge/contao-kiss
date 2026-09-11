<?php

declare(strict_types=1);

use Contao\System;
use DigitaleDinge\ContaoKiss\Styles\Option\Color\Color;
use DigitaleDinge\ContaoKiss\Styles\Option\Modifier\Size;
use DigitaleDinge\ContaoKiss\Styles\Option\Modifier\Variant;

$container = System::getContainer();
$configBuilder = $container->get('kiss.rsce_config.builder');
$translator = $container->get('translator');

$colorOptions = [];

foreach (Color::cases() as $case) {
    $colorOptions[$case->name] = $case->label()->trans($translator);
}

$sizeOptions = [];

foreach (Size::cases() as $case) {
    $sizeOptions[$case->name] = $case->label()->trans($translator);
}

// Badge styles per _badge.scss: the shared soft/outline, extended with a badge-only dashed
$variantOptions = [
    Variant::soft->name => Variant::soft->label()->trans($translator),
    Variant::outline->name => Variant::outline->label()->trans($translator),
    'dashed' => 'Gestrichelt',
];

// Badge shapes per _badge.scss, blank keeps the default corner radius
$shapeOptions = [
    'pill' => 'Pill',
    'square' => 'Eckig',
];

return $configBuilder
    ->create(['Badge', 'Eine beliebige Anzahl an Badges mit Farbe, Stil, Icon und Text'], extra: [
        'types' => ['content'],
        'standardFields' => ['cssID'],
    ])

    ->addField('badgeSize', [
        'label' => &$GLOBALS['TL_LANG']['tl_content']['elementSize'],
        'inputType' => 'select',
        'options' => $sizeOptions,
        'eval' => [
            'tl_class' => 'w25',
            'includeBlankOption' => true,
            'blankOptionLabel' => &$GLOBALS['TL_LANG']['tl_content']['elementSize'][2], // Normal size is default
        ],
    ])
    ->addField('badgeShape', [
        'label' => ['Form', 'Bestimmen Sie die Form aller Badges'],
        'inputType' => 'select',
        'options' => $shapeOptions,
        'eval' => [
            'tl_class' => 'w25',
            'includeBlankOption' => true,
        ],
    ])

    ->startList()
        ->addField('color', [
            'label' => &$GLOBALS['TL_LANG']['tl_content']['color'],
            'inputType' => 'select',
            'options' => $colorOptions,
            'eval' => [
                'tl_class' => 'w25',
                'includeBlankOption' => true,
            ],
        ])
        ->addField('variant', [
            'label' => &$GLOBALS['TL_LANG']['tl_content']['elementVariant'],
            'inputType' => 'select',
            'options' => $variantOptions,
            'eval' => [
                'tl_class' => 'w25',
                'includeBlankOption' => true,
            ],
        ])
        ->addIconField()
        ->addField('text', [
            'label' => &$GLOBALS['TL_LANG']['tl_content']['ctaText'],
            'inputType' => 'text',
            'eval' => [
                'tl_class' => 'w50',
                'maxlength' => 50,
            ],
        ])
    ->endList()

    ->build()
;
