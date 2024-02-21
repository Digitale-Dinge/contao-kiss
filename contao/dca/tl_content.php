<?php


use Contao\CoreBundle\DataContainer\PaletteManipulator;

$GLOBALS["TL_DCA"]["tl_content"]["palettes"]["__selector__"][] =
  "hyperlinkAsButton";

// Hyperlinks
PaletteManipulator::create()
  ->addField("icon", "rel", "after")
  ->addField("iconPosition", "icon", "after")
  ->addField("hyperlinkAsButton", "iconPosition", "after")
  ->applyToPalette("hyperlink", "tl_content");

//Download
PaletteManipulator::create()
  ->addField("hyperlinkAsButton", "download_legend", "append")
  ->applyToPalette("download", "tl_content");

//Downloads
PaletteManipulator::create()
  ->addField("downloadsAsCard", "download_legend", "append")
  ->applyToPalette("downloads", "tl_content");

/**
 * Customize subpalettes
 */

$GLOBALS["TL_DCA"]["tl_content"]["subpalettes"]["hyperlinkAsButton"] =
  "buttonType,buttonColor,buttonSize";

/**
   * Add fields configuration
   */
  
  $GLOBALS["TL_DCA"]["tl_content"]["fields"]["contentWidth"] = [
    "label" => &$GLOBALS["TL_LANG"]["tl_content"]["contentWidth"],
    "exclude" => true,
    "inputType" => "select",
    "options" => ["u-size--regular", "u-size--small", "u-size--smaller", "u-size--nopad", "u-size--full"],
    "reference" => &$GLOBALS["TL_LANG"]["tl_content"],
    "eval" => [
      "tl_class" => "w50",
      "includeBlankOption" => true,
    ],
    "sql" => "varchar(32) NOT NULL default ''",
  ];
  
  $GLOBALS["TL_DCA"]["tl_content"]["fields"]["paddingTop"] = [
    "label" => &$GLOBALS["TL_LANG"]["tl_content"]["paddingTop"],
    "exclude" => true,
    "inputType" => "select",
    "options" => ["half", "1x", "2x", "3x", "4x", "5x"],
    "reference" => &$GLOBALS["TL_LANG"]["tl_content"]["padding"],
    "eval" => [
      "tl_class" => "clr w50",
      "includeBlankOption" => true,
    ],
    "sql" => "varchar(32) NOT NULL default ''",
  ];
  
  $GLOBALS["TL_DCA"]["tl_content"]["fields"]["paddingBottom"] = [
    "label" => &$GLOBALS["TL_LANG"]["tl_content"]["paddingBottom"],
    "exclude" => true,
    "inputType" => "select",
    "options" => ["half", "1x", "2x", "3x", "4x", "5x"],
    "reference" => &$GLOBALS["TL_LANG"]["tl_content"]["padding"],
    "eval" => [
      "tl_class" => "w50",
      "includeBlankOption" => true,
    ],
    "sql" => "varchar(32) NOT NULL default ''",
  ];
  
  $GLOBALS["TL_DCA"]["tl_content"]["fields"]["marginTop"] = [
    "label" => &$GLOBALS["TL_LANG"]["tl_content"]["marginTop"],
    "exclude" => true,
    "inputType" => "select",
    "options" => ["half", "1x", "2x", "3x", "4x", "5x"],
    "reference" => &$GLOBALS["TL_LANG"]["tl_content"]["padding"],
    "eval" => [
      "tl_class" => "clr w50",
      "includeBlankOption" => true,
    ],
    "sql" => "varchar(32) NOT NULL default ''",
  ];
  
  $GLOBALS["TL_DCA"]["tl_content"]["fields"]["marginBottom"] = [
    "label" => &$GLOBALS["TL_LANG"]["tl_content"]["marginBottom"],
    "exclude" => true,
    "inputType" => "select",
    "options" => ["half", "1x", "2x", "3x", "4x", "5x"],
    "reference" => &$GLOBALS["TL_LANG"]["tl_content"]["padding"],
    "eval" => [
      "tl_class" => "w50",
      "includeBlankOption" => true,
    ],
    "sql" => "varchar(32) NOT NULL default ''",
  ];
  
  $GLOBALS['TL_DCA']['tl_content']['fields']['offsetUp'] = [
      'label'     => &$GLOBALS['TL_LANG']['tl_content']['offsetUp'],
      'inputType' => 'select',
      'options'   => [
          '1x',
          '2x',
          '3x',
          '4x',
          '5x',
          '-50p',
      ],
      'reference' => &$GLOBALS['TL_LANG']['tl_content'],
      'eval'      => [
          'tl_class'           => 'w50',
          'includeBlankOption' => true,
      ],
      'sql'       => "varchar(32) NOT NULL default ''"
  ];
  
  $GLOBALS['TL_DCA']['tl_content']['fields']['offsetDown'] = [
      'label'     => &$GLOBALS['TL_LANG']['tl_content']['offsetDown'],
      'inputType' => 'select',
      'options'   => [
          '1x',
          '2x',
          '3x',
          '4x',
          '5x',
          '-50p',
      ],
      'reference' => &$GLOBALS['TL_LANG']['tl_content'],
      'eval'      => [
          'tl_class'           => 'w50',
          'includeBlankOption' => true,
      ],
      'sql'       => "varchar(32) NOT NULL default ''"
  ];

$GLOBALS["TL_DCA"]["tl_content"]["fields"]["icon"] = [
  "label" => &$GLOBALS["TL_LANG"]["tl_content"]["icon"],
  "inputType" => "rocksolid_icon_picker",
  "eval" => [
    "iconFont" => "files/theme/dist/fonts/icons/fonts/icons.svg",
    "tl_class" => "w50 clr",
  ],
  "sql" => "char(4) NOT NULL default ''",
];


$GLOBALS["TL_DCA"]["tl_content"]["fields"]["downloadsAsCard"] = [
  "label" => &$GLOBALS["TL_LANG"]["tl_content"]["downloadsAsCard"],
  "exclude" => true,
  "inputType" => "checkbox",
  "sql" => "char(1) NOT NULL default ''",
  "eval" => [
    "tl_class" => "m12 clr",
  ],
];

$GLOBALS["TL_DCA"]["tl_content"]["fields"]["hyperlinkAsButton"] = [
  "label" => &$GLOBALS["TL_LANG"]["tl_content"]["hyperlinkAsButton"],
  "exclude" => true,
  "inputType" => "checkbox",
  "sql" => "char(1) NOT NULL default ''",
  "eval" => [
    "tl_class" => "m12 clr",
    "submitOnChange" => true,
  ],
];

$GLOBALS["TL_DCA"]["tl_content"]["fields"]["iconPosition"] = [
  "exclude" => true,
  "inputType" => "select",
  "options" => ["left", "right"],
  "reference" => &$GLOBALS["TL_LANG"]["tl_content"],
  "eval" => [
    "tl_class" => "w50",
    "includeBlankOption" => false,
  ],
  "sql" => "varchar(32) NOT NULL default ''",
];

$GLOBALS["TL_DCA"]["tl_content"]["fields"]["buttonType"] = [
  "label" => &$GLOBALS["TL_LANG"]["tl_content"]["buttonType"],
  "exclude" => true,
  "inputType" => "select",
  "options" => ["primary", "secondary", "tertiary"],
  "reference" => &$GLOBALS["TL_LANG"]["tl_content"],
  "eval" => [
    "tl_class" => "w50",
  ],
  "sql" => "varchar(32) NOT NULL default ''",
];

$GLOBALS["TL_DCA"]["tl_content"]["fields"]["buttonColor"] = [
  "label" => &$GLOBALS["TL_LANG"]["tl_content"]["buttonColor"],
  "exclude" => true,
  "inputType" => "select",
  "options" => ["brand", "secondary-1", "secondary-2", "secondary-3"],
  "reference" => &$GLOBALS["TL_LANG"]["tl_content"],
  "eval" => [
    "tl_class" => "w50",
  ],
  "sql" => "varchar(32) NOT NULL default ''",
];

$GLOBALS["TL_DCA"]["tl_content"]["fields"]["buttonSize"] = [
  "label" => &$GLOBALS["TL_LANG"]["tl_content"]["buttonSize"],
  "exclude" => true,
  "inputType" => "select",
  "options" => ["small", "medium", "large"],
  "reference" => &$GLOBALS["TL_LANG"]["tl_content"],
  "eval" => [
    "tl_class" => "w50",
  ],
  "sql" => "varchar(32) NOT NULL default ''",
];


/**
 * Customize existing fields
 */

// text fields clearfix
$GLOBALS["TL_DCA"]["tl_content"]["fields"]["text"]["eval"]["tl_class"] =
  "long clr";
$GLOBALS["TL_DCA"]["tl_content"]["fields"]["optionalText"]["eval"]["tl_class"] =
  "long clr";
$GLOBALS["TL_DCA"]["tl_content"]["fields"]["useImage"]["eval"]["tl_class"] =
  "long clr";

  /* HTML in Überschriften */
  $GLOBALS['TL_DCA']['tl_content']['fields']['headline']['eval']['allowHtml'] = true;
