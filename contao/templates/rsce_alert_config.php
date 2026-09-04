<?php

declare(strict_types=1);

use Contao\System;

$configBuilder = System::getContainer()->get('kiss.rsce_config.builder');

return $configBuilder
    ->create('alert', 'texts', [
        'types' => ['content'],
        'standardFields' => ['cssID'],
    ])
    ->addField('title', [
        'label' => true,
        'inputType' => 'text',
        'eval' => [
            'tl_class' => 'w50',
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
    ->addIconField()
    ->addField('elementColor', ['inputType' => 'standardField'])
    ->addField('elementVariant', ['inputType' => 'standardField'])
    ->build()
;
