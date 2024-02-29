<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Contao\Hooks;

use Contao\CoreBundle\DataContainer\PaletteManipulator;
use Contao\CoreBundle\ServiceAnnotation\Callback;
use Contao\CoreBundle\ServiceAnnotation\Hook;
use Contao\DataContainer;


class AddTextStyleToPalette
{

    #[AsCallback('tl_content', 'config.onload', priority: -1000)]
    public function __invoke(DataContainer $dc = null): void
    {
        if ( null === $dc )
        {
            return;
        }

        foreach ($GLOBALS['TL_DCA'][$dc->table]['palettes'] as $key => $palette)
        {
            if ( '__selector__' === $key )
            {
                continue;
            }

            PaletteManipulator::create()
                // empty closure the prevent the fallback
                ->addField(['multiColumns', 'textStyle'], 'text', PaletteManipulator::POSITION_BEFORE, fn()=>null)
                ->applyToPalette($key, $dc->table)
            ;
        }
    }

    #[AsHook('loadDataContainer')]
    public function loadDataContainer(string $table): void
    {
        if ( 'tl_content' !== $table )
        {
            return;
        }

        $GLOBALS['TL_DCA']['tl_content']['fields']['textStyle'] =
        [
            'exclude'   => true,
            'inputType' => 'select',
            'options'   => [
                'intro'
            ],
            'reference' => &$GLOBALS['TL_LANG']['tl_content'],
            'eval' =>
            [
                'tl_class'           => 'w50',
                'includeBlankOption' => true
            ],
            'sql' => "varchar(32) NOT NULL default ''"
        ];
    }

}
