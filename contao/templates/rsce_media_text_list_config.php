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
    ->addElementLayoutField(dependsOn: 'addMedia')
    ->addDependsOnField('mediaType', ['image', 'icon'], dependsOn: ['addMedia'])
    ->addImageSizeField(dependsOn: 'mediaType')

    ->startList()
        ->addImageField(dependsOn: '../mediaType')
        ->addImageUrlField(dependsOn: '../mediaType')
        ->addIconField(dependsOn: '../mediaType')
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
