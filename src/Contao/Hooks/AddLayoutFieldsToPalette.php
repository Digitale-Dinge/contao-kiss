<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Contao\Hooks;

use Contao\Config;
use Contao\CoreBundle\DataContainer\PaletteManipulator;
use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Contao\DataContainer;
use Contao\StringUtil;

final class AddLayoutFieldsToPalette
{

    #[AsHook('loadDataContainer')]
    public function addBlacklistFieldToSettings(string $table): void
    {
        if ($table !== 'tl_settings') {
            return;
        }

        $GLOBALS['TL_DCA'][$table]['fields']['kiss_dontShowFieldsOnContentElement'] = [
            'inputType' => 'checkbox',
            'options_callback' => static function (): array {
                $options = [];
                // remove the categories from the content element list
                foreach ($GLOBALS['TL_CTE'] as $cte) {
                    foreach ($cte as $key => $class) {
                        $options[$key] = ($GLOBALS['TL_LANG']['CTE'][$key][0] ?? $key) . ' <span style="color:var(--gray)">[' . $key . ']</span>';
                    }
                }

                return $options;
            },
            'eval' => [
                'multiple' => true,
                'tl_class' => 'm12 w50 clr',
            ]
        ];

        PaletteManipulator::create()
            ->addField('kiss_dontShowFieldsOnContentElement', 'backend_legend', PaletteManipulator::POSITION_APPEND)
            ->applyToPalette('default', $table);
    }

    #[AsCallback('tl_content', 'config.onpalette')]
    #[AsCallback('tl_article', 'config.onpalette')]
    public function createPalettes(string $palette, DataContainer $dc): string
    {
        if (null === $currentRecord = $dc->getCurrentRecord()) {
            return $palette;
        }

        $blacklist = StringUtil::deserialize(Config::get('kiss_dontShowFieldsOnContentElement'), true);

        // skip if the content type is in the blacklist
        if (in_array($currentRecord['type'], $blacklist, true)) {
            return $palette;
        }

        return PaletteManipulator::create()
            ->addLegend('layout_legend', 'template_legend', PaletteManipulator::POSITION_BEFORE)
            ->addField(['contentWidth', 'bgColor', 'paddingTop', 'paddingBottom', 'marginTop', 'marginBottom'], 'layout_legend', 'append')
            ->applyToString($palette);
    }

}
