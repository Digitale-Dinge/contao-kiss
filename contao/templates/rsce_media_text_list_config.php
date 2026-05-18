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
    ->addTextAlignmentField()
    ->addField('mediaMargin', [
        'label' => [
            $translator->trans('rsce.media_text_list.mediaMargin.label', [], 'rsce'),
            $translator->trans('rsce.media_text_list.mediaMargin.description', [], 'rsce'),
        ],
        'inputType' => 'select',
        'options' => array_reduce(\DigitaleDinge\ContaoKiss\Styles\Option\Margin\Bottom::cases(), static function ($carry, $case) use ($translator) {
            $carry[$case->value] = $translator->trans($case->label()->getMessage(), [], $case->label()->getDomain());
            return $carry;
        }, []),
        'eval' => [
            'includeBlankOption' => true,
            'tl_class' => 'w50',
            'dependsOn' => [
                'field' => 'type',
                'value' => ['image', 'icon'],
            ],
        ],
    ])
    ->startList()
        ->addIconField([], '../type')
        ->addImageField([], '../type')
        ->addHeadlineField()
        ->addToplineField()
        ->addRichTextField()
        ->addTextAppearanceField()
    ->endList()
    ->build()
;
