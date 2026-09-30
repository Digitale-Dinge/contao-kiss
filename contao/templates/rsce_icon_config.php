<?php

declare(strict_types=1);

use Contao\System;

$configBuilder = System::getContainer()->get('kiss.rsce_config.builder');

return $configBuilder
    ->create('icon', 'media', [
        'types' => ['content'],
        'standardFields' => ['cssID'],
    ])
    ->addIconStyleField()
    ->addIconField()
    ->addRichTextField()
    ->build()
;
