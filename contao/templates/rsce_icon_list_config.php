<?php

declare(strict_types=1);

use Contao\System;

$configBuilder = System::getContainer()->get('kiss.rsce_config.builder');

return $configBuilder
    ->create('icon_list', 'media', [
        'types' => ['content'],
        'standardFields' => ['cssID'],
    ])
    ->addIconStyleField()
    ->startList()
        ->addIconField()
        ->addRichTextField()
    ->endList()
    ->build()
;
