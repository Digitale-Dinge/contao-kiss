<?php

declare(strict_types=1);

$GLOBALS['TL_DCA']['tl_module']['fields']['topline'] = [
    'exclude'   => true,
    'inputType' => 'text',
    'eval'      => [
        'tl_class'  => 'w50',
        'maxlength' => 255,
        'allowHtml' => true
    ],
    'sql' => [
        'type'    => 'string',
        'length'  => 255,
        'default' => ''
    ]
];
