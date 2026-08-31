<?php

declare(strict_types=1);

use Contao\System;

$configBuilder = System::getContainer()->get('kiss.rsce_config.builder');

return $configBuilder
    ->create('media_text', extra: [
        'types' => ['content'],
        'standardFields' => ['cssID'],
    ])

    ->addGroup('appearance')
    ->addTextAlignmentField(eval: ['tl_class' => 'w25 clr'])
    ->addDependsOnField('addMedia')
    ->addDependsOnField('type', ['image', 'icon', 'video'], ['tl_class' => 'w25'], dependsOn: ['addMedia'])
    ->addElementLayoutField(dependsOn: 'addMedia')
    ->addImageSizeField(dependsOn: 'type')

    ->addGroup('media')
    ->addImageField(dependsOn: 'type')
    ->addImageUrlField(dependsOn: 'type')
    ->addIconField(dependsOn: 'type')
    ->addResponsiveVideoField(dependsOn: 'type')

    ->addHeadlineField() // Topline is appended automatically outside of lists
    ->addRichTextField()
    ->addCallToActionField()

    ->addCardSettings()
    ->build()
;
