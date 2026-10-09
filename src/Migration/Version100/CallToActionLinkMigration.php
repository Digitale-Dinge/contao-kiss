<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Migration\Version100;

use DigitaleDinge\ContaoKiss\Migration\AbstractJsonColumnMigration;

class CallToActionLinkMigration extends AbstractJsonColumnMigration
{
    private const array VARIANT_MAP = [
        'link' => 'text',
    ];

    protected function getTables(): array
    {
        return ['tl_content', 'tl_form_field'];
    }

    protected function getColumns(): array
    {
        return ['kiss_styles', 'rsce_data', 'callToAction'];
    }

    protected function getValueMaps(): array
    {
        return [
            'kiss_styles' => [
                'ctaType' => self::VARIANT_MAP,
                'fieldVariant' => self::VARIANT_MAP,
            ],
            'rsce_data' => [
                'ctaType' => self::VARIANT_MAP,
            ],
            'callToAction' => [
                'ctaType' => self::VARIANT_MAP,
            ],
        ];
    }
}
