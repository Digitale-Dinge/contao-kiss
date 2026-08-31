<?php

declare(strict_types=1);

use Contao\System;

$configBuilder = System::getContainer()->get('kiss.rsce_config.builder');

return $configBuilder
    ->create('media_text_list', extra: [
        'types' => ['content'],
        'standardFields' => ['headline', 'topline', 'cssID'],
    ])

    ->addGroup('appearance')
    ->addTextAlignmentField(eval: ['tl_class' => 'w25 clr'])
    ->addDependsOnField('addMedia')
    ->addDependsOnField('type', ['image', 'icon'], dependsOn: ['addMedia'])
    ->addElementLayoutField(dependsOn: 'addMedia')
    ->addDependsOnField('type', ['image', 'icon'], dependsOn: ['addMedia'])
    ->addImageSizeField(dependsOn: 'type')

    ->startList()
        ->addImageField(dependsOn: '../type')
        ->addImageUrlField(dependsOn: 'type')
        ->addIconField(dependsOn: '../type')
        ->addHeadlineField()
        ->addToplineField()
        ->addRichTextField()
        ->addTextAppearanceField()
        ->addCallToActionField()
    ->endList()

    ->addCardSettings()
    ->addGridGroup()

    ->build()
;
