<?php

declare(strict_types=1);

use DigitaleDinge\ContaoKiss\Model;
use DigitaleDinge\ContaoKiss\Widget;

$GLOBALS['BE_MOD']['content']['kiss_company'] = [
    'tables' => [
        'tl_kiss_company',
    ]
];

$GLOBALS['TL_MODELS']['tl_kiss_company'] = Model\KissCompanyModel::class;

$GLOBALS['BE_FFL']['openingTimesTable'] = Widget\OpeningTimesTable::class;
$GLOBALS['BE_FFL']['date'] = Widget\DateField::class;
