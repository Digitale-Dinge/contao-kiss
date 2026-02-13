<?php

declare(strict_types=1);

use Contao\System;

return [
    'label' => [
        'de' => [
            'Medium-Text',
            'Ein Medium-Text Element',
        ],
        'en' => [
            'Media-Text',
            'A media-text element',
        ],
    ],
    'types' => ['content'],
    'contentCategory' => 'media',
    'standardFields' => ['cssID'],
    'fields' => System::getContainer()->get('kiss.rsce_config.builder')->create('media_text')
];
