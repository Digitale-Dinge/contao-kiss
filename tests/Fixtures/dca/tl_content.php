<?php

declare(strict_types=1);

foreach (['ctaType', 'ctaColor', 'ctaSize', 'url', 'target', 'rel'] as $field) {
    // @phpstan-ignore offsetAccess.nonOffsetAccessible, offsetAccess.nonOffsetAccessible, offsetAccess.nonOffsetAccessible ($GLOBALS is untyped)
    $GLOBALS['TL_DCA']['tl_content']['fields'][$field] = [
        'inputType' => 'text',
        'eval' => [
            'tl_class' => 'w50',
        ],
    ];
}
