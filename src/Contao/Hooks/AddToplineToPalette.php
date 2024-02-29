<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Contao\Hooks;

use Contao\CoreBundle\DataContainer\PaletteManipulator;
use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Contao\DataContainer;


class AddToplineToPalette
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
                ->addField('topline', 'headline', PaletteManipulator::POSITION_AFTER, fn()=>null)
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

        $GLOBALS['TL_DCA']['tl_content']['fields']['topline'] =
        [
            'exclude'   => true,
            'inputType' => 'text',
            'eval'      =>
            [
                'tl_class'  => 'w50',
                'maxlength' => 255,
                'allowHtml' => true
            ],
            'sql' =>
            [
                'type'    => 'string',
                'length'  => 255,
                'default' => ''
            ]
        ];
    }

}
