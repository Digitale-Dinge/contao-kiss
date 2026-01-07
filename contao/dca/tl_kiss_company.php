<?php

declare(strict_types=1);

use Contao\CoreBundle\EventListener\Widget\HttpUrlListener;
use Contao\DataContainer;
use Contao\DC_Table;
use Contao\System;

System::loadLanguageFile('tl_member');

$GLOBALS['TL_DCA']['tl_kiss_company'] = [
    'config' => [
        'dataContainer' => DC_Table::class,
        'enableVersioning' => true,
        'sql' => [
            'keys' => [
                'id' => 'primary',
            ],
        ],
    ],
    'list' => [
        'sorting' => [
            'mode' => DataContainer::MODE_SORTED,
            'fields' => ['name'],
            'flag' => DataContainer::SORT_INITIAL_LETTER_ASC,
            'panelLayout' => 'filter;search,limit',
        ],
        'label' => [
            'fields' => ['name'],
            'showColumns' => true,
        ],
    ],
    'palettes' => [
        'default' =>
            '{general_legend},name,logo;' .
            '{address_legend},street,postal,city,state,country;' .
            '{times_legend},opening_times,closing_times;' .
            '{contact_legend},phone_numbers,emails,websites,fax_numbers;' .
            '{misc_legend:hide},socials,additional;',
    ],
    'fields' => [
        'id' => [
            'sql' => ['type' => 'integer', 'unsigned' => true, 'autoincrement' => true],
        ],
        'tstamp' => [
            'sql' => ['type' => 'integer', 'unsigned' => true, 'default' => 0],
        ],
        'name' => [
            'label' => &$GLOBALS['TL_LANG']['tl_member']['company'],
            'inputType' => 'text',
            'eval' => [
                'mandatory' => true,
                'tl_class' => 'w50',
                'decodeEntities' => true,
            ],
            'sql' => ['type' => 'string', 'length' => 255, 'default' => ''],
        ],
        'logo' => [
            'inputType' => 'fileTree',
            'eval' => [
                'fieldType' => 'radio',
                'extensions' => '%contao.image.valid_extensions%',
                'filesOnly' => true,
                'tl_class' => 'clr',
            ],
            'sql' => 'binary(16) NULL',
        ],
        'street' => [
            'label' => &$GLOBALS['TL_LANG']['tl_member']['street'],
            'inputType' => 'text',
            'eval' => [
                'tl_class' => 'w50',
            ],
            'sql' => ['type' => 'string', 'length' => 255, 'default' => ''],
        ],
        'postal' => [
            'label' => &$GLOBALS['TL_LANG']['tl_member']['postal'],
            'inputType' => 'text',
            'eval' => [
                'tl_class' => 'w50',
            ],
            'sql' => ['type' => 'string', 'length' => 32, 'default' => ''],
        ],
        'city' => [
            'label' => &$GLOBALS['TL_LANG']['tl_member']['city'],
            'inputType' => 'text',
            'eval' => [
                'tl_class' => 'w50',
            ],
            'sql' => ['type' => 'string', 'length' => 255, 'default' => ''],
        ],
        'state' => [
            'label' => &$GLOBALS['TL_LANG']['tl_member']['state'],
            'inputType' => 'text',
            'eval' => [
                'tl_class' => 'w50',
            ],
            'sql' => ['type' => 'string', 'length' => 255, 'default' => ''],
        ],
        'country' => [
            'label' => &$GLOBALS['TL_LANG']['tl_member']['country'],
            'inputType' => 'select',
            'eval' => [
                'tl_class' => 'w50',
                'includeBlankOption' => true,
                'chosen' => true,
            ],
            'options_callback' => static fn () => System::getContainer()->get('contao.intl.countries')->getCountries(),
            'sql' => ['type' => 'string', 'length' => 2, 'default' => ''],
        ],
        'opening_times' => [
            'inputType' => 'openingTimesTable',
            'eval' => ['tl_class' => 'w50 clr'],
            'sql' => "text NULL",
        ],
        'closing_times' => [
            'inputType' => 'rowWizard',
            'exclude' => true,
            'fields' => [
                'start' => [
                    'inputType' => 'date',
                ],
                'stop' => [
                    'inputType' => 'date',
                ],
            ],
            'eval' => ['tl_class' => 'w50', 'sortable' => false],
            'sql' => "text NULL",
        ],
        'phone_numbers' => [
            'inputType' => 'rowWizard',
            'label' => &$GLOBALS['TL_LANG']['tl_member']['phone'],
            'exclude' => true,
            'fields' => [
                'phone' => [
                    'inputType' => 'text',
                    'eval' => [
                        'maxlength' => 64,
                        'rgxp' => 'phone',
                        'decodeEntities' => true,
                    ],
                ],
            ],
            'eval' => ['tl_class' => 'w100 clr'],
            'sql' => 'text NULL',
        ],
        'emails' => [
            'inputType' => 'rowWizard',
            'label' => &$GLOBALS['TL_LANG']['tl_member']['email'],
            'exclude' => true,
            'fields' => [
                'email' => [
                    'inputType' => 'text',
                    'eval' => [
                        'maxlength' => 255,
                        'rgxp' => 'email',
                        'decodeEntities' => true,
                    ],
                ],
            ],
            'eval' => ['tl_class' => 'w100 clr'],
            'sql' => 'text NULL',
        ],
        'websites' => [
            'inputType' => 'rowWizard',
            'label' => &$GLOBALS['TL_LANG']['tl_member']['website'],
            'exclude' => true,
            'fields' => [
                'website' => [
                    'inputType' => 'text',
                    'eval' => [
                        'rgxp' => HttpUrlListener::RGXP_NAME,
                        'maxlength' => 255,
                        'decodeEntities' => true,
                    ],
                ],
            ],
            'eval' => ['tl_class' => 'w100 clr'],
            'sql' => 'text NULL',
        ],
        'fax_numbers' => [
            'inputType' => 'rowWizard',
            'label' => &$GLOBALS['TL_LANG']['tl_member']['fax'],
            'exclude' => true,
            'fields' => [
                'fax' => [
                    'inputType' => 'text',
                    'eval' => [
                        'maxlength' => 64,
                        'rgxp' => 'phone',
                        'decodeEntities' => true,
                    ],
                ],
            ],
            'eval' => ['tl_class' => 'w100 clr'],
            'sql' => 'text NULL',
        ],
        'socials' => [
            'inputType' => 'rowWizard',
            'exclude' => true,
            'fields' => [
                'social' => [
                    'inputType' => 'select',
                    'eval' => [
                        'includeBlankOption' => true,
                        'chosen' => true,
                        'tl_class' => 'clr',
                    ],
                ],
                'url' => [
                    'inputType' => 'text',
                    'eval' => [
                        'rgxp' => HttpUrlListener::RGXP_NAME,
                        'maxlength' => 255,
                        'decodeEntities' => true,
                    ],
                ],
            ],
            'eval' => ['tl_class' => 'w100 clr', 'sortable' => false],
            'sql' => 'text NULL',
        ],
        'additional' => [
            'inputType' => 'rowWizard',
            'exclude' => true,
            'fields' => [
                'hint' => [
                    'inputType' => 'text',
                    'eval' => [
                        'maxlength' => 255,
                        'decodeEntities' => true,
                    ],
                ],
                'info' => [
                    'inputType' => 'text',
                    'eval' => [
                        'maxlength' => 255,
                        'decodeEntities' => true,
                    ],
                ],
            ],
            'eval' => ['tl_class' => 'w100 clr', 'sortable' => false],
            'sql' => 'text NULL',
        ],
    ],
];
