<?php

declare(strict_types=1);

foreach (['ctaType', 'ctaColor', 'ctaSize', 'url', 'target', 'rel'] as $field) {
    $GLOBALS['TL_DCA']['tl_content']['fields'][$field] = [
        'inputType' => 'text',
        'eval' => ['tl_class' => 'w50'],
    ];
}
