<?php

declare(strict_types=1);

use Contao\System;

$configBuilder = System::getContainer()->get('kiss.rsce_config.builder');

return $configBuilder
    ->create('hyperlink_list', 'links', [
        'types' => ['content'],
        'standardFields' => ['cssID'],
    ])
    ->addIconPositionField()
    ->addCtaAsButtonField()
    ->startList()
        ->addIconField()
        ->addLinkFields()
    ->endList()
    ->build()
;
