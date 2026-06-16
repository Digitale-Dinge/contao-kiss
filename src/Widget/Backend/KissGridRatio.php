<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Widget\Backend;

use Contao\System;
use Contao\Widget;

/**
 * Reactive column-ratio ruler for element groups.
 *
 * Renders a hidden input holding the ratio (fr integers joined by "-", e.g.
 * "3-7") plus a bar the editor drags. The actual handles are built by the
 * vanilla JS in public/dist/backend/contao-kiss.js, which reads the sibling
 * gridColumns select live (2 cols => 1 handle, 3 cols => 2 handles, else even).
 *
 * No assets are loaded here on purpose: the BackendAssetsListener already
 * ships dist/backend/contao-kiss.{css,js} on every backend page.
 */
class KissGridRatio extends Widget
{
    protected $blnSubmitInput = true;
    protected $blnForAttribute = true;
    protected $strTemplate = 'be_widget';

    public function generate(): string
    {
        $twig = System::getContainer()->get('twig');

        return $twig->render('@Contao/backend/widget/kiss_grid_ratio.html.twig', [
            'id' => $this->strId,
            'name' => $this->strName,
            'value' => (string) $this->value,
            // The gridColumns select rendered in the same palette; the JS reads it.
            'colsField' => 'ctrl_gridColumns',
        ]);
    }
}
