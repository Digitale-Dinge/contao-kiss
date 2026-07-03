<?php

declare(strict_types=1);

use Contao\System;

$configBuilder = System::getContainer()->get('kiss.rsce_config.builder');

return $configBuilder
    ->create('media_text', 'media', [
        'types' => ['content'],
        'standardFields' => ['cssID'],
    ])
    ->addGroup('settings')
    ->addDependsOnField('type', ['image', 'icon', 'separated'])
    ->addImageSizeField([], 'type')
    ->addTextAlignmentField()
    ->addShowAsCardField()
    ->addIconField([], 'type')
    ->addGroup('media')
    ->addImageField([], 'type')
    ->addHeadlineField() // Topline is appended automatically outside of lists
    ->addRichTextField()
    ->addCallToActionField()
    ->build()
;
