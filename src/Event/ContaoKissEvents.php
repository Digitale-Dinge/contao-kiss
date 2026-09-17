<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Event;

use DigitaleDinge\ContaoKiss\Event\Styles\StyleOptionEvent;

final class ContaoKissEvents
{
    /**
     * Generic style event
     *
     * @see StyleOptionEvent
     */
    public const string STYLE_DEFAULT = 'contao_kiss.style_default';

    /**
     * Style event for containers
     *
     * @see StyleOptionEvent
     */
    public const string STYLE_LAYOUT_CONTAINER = 'contao_kiss.style_layout_container';

    /**
     * Style event for columns
     *
     * @see StyleOptionEvent
     */
    public const string STYLE_LAYOUT_COLUMN = 'contao_kiss.style_layout_column';

    /**
     * Style event for gaps
     *
     * @see StyleOptionEvent
     */
    public const string STYLE_LAYOUT_GAP = 'contao_kiss.style_layout_gap';

    /**
     * Style event for colors
     *
     * @see StyleOptionEvent
     */
    public const string STYLE_COLOR = 'contao_kiss.style_color';

    /**
     * Style event for background colors
     *
     * @see StyleOptionEvent
     */
    public const string STYLE_COLOR_BACKGROUND = 'contao_kiss.style_color_background';
}
