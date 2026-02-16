<?php

declare(strict_types=1);

use Contao\System;

$configBuilder = System::getContainer()->get('kiss.rsce_config.builder');
$translator = System::getContainer()->get('translator');

return $configBuilder
    ->create(
        [
            $translator->trans('rsce.media_text_list.label', [], 'rsce'),
            $translator->trans('rsce.media_text_list.description', [], 'rsce'),
        ],
        'media',
        [
            'types' => ['content'],
            'standardFields' => ['headline', 'topline', 'cssID'],
        ]
    )
    ->addGroup('settings', [$translator->trans('rsce.group.settings', [], 'rsce')])
    ->addDependsOnField('type', ['image', 'icon', 'separated'])
    ->addImageSizeField('type')
    ->addIconField('type')
    ->startList('list', ['de' => 'Elemente', 'en' => 'Elemente',])
        ->addIconField('../type')
        ->addImageField('../type')
        ->addHeadlineField()
        ->addToplineField()
        ->addTextField()
    ->endList()
    ->build()
;
