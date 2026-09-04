<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Migration\Version100;

use DigitaleDinge\ContaoKiss\Migration\AbstractJsonColumnMigration;

class ArticleContentBackgroundColorKissStylesMigration extends AbstractJsonColumnMigration
{
    private const array BACKGROUND_COLOR_MAP = [
        'base_100' => 'neutral_one',
        'base_200' => 'neutral_two',
        'base_300' => 'neutral_three',
        'base_content' => 'neutral_inverse',
    ];

    protected function getTables(): array
    {
        return ['tl_content', 'tl_article'];
    }

    protected function getColumns(): array
    {
        return ['kiss_styles'];
    }

    protected function getValueMaps(): array
    {
        return ['kiss_styles' => ['backgroundColor' => self::BACKGROUND_COLOR_MAP]];
    }
}
