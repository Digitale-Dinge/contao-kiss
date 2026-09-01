<?php

declare(strict_types=1);

use Contao\System;

$configBuilder = System::getContainer()->get('kiss.rsce_config.builder');

return $configBuilder
    ->create('info-list', 'texts', [
        'types' => ['content'],
        'standardFields' => ['cssID'],
    ])
    ->addDependsOnField('listLayout', ['mini', 'large'])
    ->startList()
        ->addIconField()
        ->addField('headline', [
            'label' => true,
            'inputType' => 'text',
            'eval' => [
                'tl_class' => 'w100 clr',
                'mandatory' => false,
            ],
        ])
        ->addRichTextField()
    ->endList()
    ->build()
;
