<?php

declare(strict_types=1);

use Contao\System;

$configBuilder = System::getContainer()->get('kiss.rsce_config.builder');
$translator = System::getContainer()->get('translator');

return $configBuilder
    ->create('media_text_list', 'media', [
        'types' => ['content'],
        'standardFields' => ['headline', 'topline', 'cssID'],
    ])
    ->addGroup('settings')
    ->addDependsOnField('type', ['image', 'icon', 'separated'])
    ->addImageSizeField([], 'type')
    ->addTextAlignmentField()
    ->addShowAsCardField()
    ->startList()
        ->addIconField([], '../type')
        ->addImageField([], '../type')
        ->addHeadlineField()
        ->addToplineField()
        ->addRichTextField()
        ->addTextAppearanceField()
        ->addCallToActionField()
    ->endList()
    ->addGridGroup()
    ->build()
;
