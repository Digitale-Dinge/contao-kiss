<?php

declare(strict_types=1);

use Contao\System;

$configBuilder = System::getContainer()->get('kiss.rsce_config.builder');
$translator = System::getContainer()->get('translator');

return $configBuilder
    ->create(
        [
            $translator->trans('rsce.media_text.label', [], 'rsce'),
            $translator->trans('rsce.media_text.description', [], 'rsce'),
        ],
        'media',
        [
            'types' => ['content'],
            'standardFields' => ['cssID'],
        ]
    )
    ->addGroup('settings', [
        $translator->trans('rsce.group.settings', [], 'rsce'),
    ])
    ->addDependsOnField('type', ['image', 'icon', 'separated'])
    ->addImageSizeField([], 'type')
    ->addIconField([], 'type')
    ->addGroup('media', [
        $translator->trans('rsce.group.media', [], 'rsce')
    ])
    ->addImageField([], 'type')
    ->addHeadlineField() // Topline is appended automatically outside of lists
    ->addRichTextField()
    ->addCallToActionField()
    ->build()
;
