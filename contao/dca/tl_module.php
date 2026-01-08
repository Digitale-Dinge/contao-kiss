<?php

declare(strict_types=1);

$GLOBALS['TL_DCA']['tl_module']['fields']['topline'] = [
    'exclude' => true,
    'inputType' => 'text',
    'saveTo' => 'kiss_styles',
    'eval' => [
        'tl_class' => 'w50',
        'maxlength' => 255,
        'allowHtml' => true
    ],
];
