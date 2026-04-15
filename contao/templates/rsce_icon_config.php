<?php

declare(strict_types=1);

use Contao\System;

$configBuilder = System::getContainer()->get('kiss.rsce_config.builder');

return $configBuilder
    ->create('icon', 'media', [
        'types' => ['content'],
        'standardFields' => ['cssID'],
    ])
    ->addIconField()
    ->addIconPositionField()
    ->addField('text', [
        'label' => true,
        'inputType' => 'textarea',
        'eval' => [
            'tl_class' => 'long',
            'mandatory' => false,
            'allowHtml' => true,
        ],
    ])
    ->build()
;
