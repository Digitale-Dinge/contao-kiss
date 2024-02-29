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

    
    #[AsHook('parseTemplate')]
    public function parseCssToTemplates(Template $template): void
    {
        if ($template->contentWidth)
        {
            $template->layoutCss .= 'u-size '.$template->contentWidth;
        }
        else
        {
            $template->layoutCss .= 'u-size u-size--regular';
        }

        if ( $template->bgColor || $template->offsetUp || $template->offsetDown )
        {
            $template->layoutCss .= ' u-background';

            if ( $template->bgColor )
            {
                $template->layoutCss .= ' u-background--'.$template->bgColor;
            }
        }

        if ( $template->paddingTop )
        {
            $template->layoutCss .= ' u-padding-vertical-top u-padding-vertical-top--'.$template->paddingTop;
        }

        if ( $template->paddingBottom )
        {
            $template->layoutCss .= ' u-padding-vertical-bottom u-padding-vertical-bottom--'.$template->paddingBottom;
        }
		
		if ( $template->marginTop )
		{
			$template->layoutCss .= ' u-margin-vertical-top u-margin-vertical-top--'.$template->marginTop;
		}
		
		if ( $template->marginBottom )
		{
			$template->layoutCss .= ' u-margin-vertical-bottom u-margin-vertical-bottom--'.$template->marginBottom;
		}
		
    }

}
