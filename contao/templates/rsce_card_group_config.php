<?php

declare(strict_types=1);

use Contao\System;

$configBuilder = System::getContainer()->get('kiss.rsce_config.builder');

return $configBuilder
    ->create('card_group', 'media', [
        'types' => ['content'],
        'standardFields' => ['cssID'],
    ])
    ->addGroup('settings')
    ->addDependsOnField('type', ['image', 'icon', 'separated'])
    ->addImageSizeField([], 'type')
    ->addTextAlignmentField()
    /** Groups the following fields for the card element - backgroundColor, elementSize, cardLayout, elementVariant */
    ->addCardStyleFields()
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
