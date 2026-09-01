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
    ->addDependsOnField('mediaType', ['image', 'icon', 'video'], ['tl_class' => 'w25'], dependsOn: ['addMedia'])
    ->addElementLayoutField(dependsOn: 'addMedia')
    ->addImageSizeField(dependsOn: 'mediaType')

    ->addGroup('media')
    ->addImageField(dependsOn: 'mediaType')
    ->addImageUrlField(dependsOn: 'mediaType')
    ->addIconField(dependsOn: 'mediaType')
    ->addResponsiveVideoField(dependsOn: 'mediaType')

    ->addHeadlineField() // Topline is appended automatically outside of lists
    ->addRichTextField()
    ->addCallToActionField()

    ->addCardSettings()
    ->build()
;
