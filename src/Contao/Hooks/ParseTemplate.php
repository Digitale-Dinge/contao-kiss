<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Contao\Hooks;

use Contao\CoreBundle\DataContainer\PaletteManipulator;
use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Contao\DataContainer;
use Contao\Template;

class ParseTemplate
{

    #[AsCallback('tl_content', 'config.onload', priority: -1)]
    #[AsCallback('tl_article', 'config.onload', priority: -1)]
    public function parseFieldsToContentDataContainer(DataContainer $dc = null): void
    {
        if ( null === $dc )
        {
            return;
        }

        foreach ($GLOBALS['TL_DCA'][$dc->table]['palettes'] as $key => $palette)
        {
            if ( $key !== '__selector__')
            {
                PaletteManipulator::create()
                    ->addLegend('layout_legend', 'template_legend', PaletteManipulator::POSITION_BEFORE)
                    ->addField(['contentWidth', 'bgColor', 'paddingTop', 'paddingBottom', 'marginTop', 'marginBottom'], 'layout_legend', 'append')
                    ->applyToPalette($key, $dc->table)
                ;
            }
        }
    }

}
