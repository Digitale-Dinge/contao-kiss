<?php

declare(strict_types=1);

use Contao\System;

$configBuilder = System::getContainer()->get('kiss.rsce_config.builder');

return $configBuilder
    ->create('media_text_list', 'media', [
        'types' => ['content'],
        'standardFields' => ['headline', 'topline', 'cssID'],
    ])
    ->addGroup('settings')
    ->addDependsOnField('type', ['image', 'icon', 'separated'])
    ->addImageSizeField([], 'type')
    ->startList()
        ->addIconField([], '../type')
        ->addImageField([], '../type')
        ->addHeadlineField()
        ->addToplineField()
        ->addRichTextField()
    ->endList()
    ->build()
;
