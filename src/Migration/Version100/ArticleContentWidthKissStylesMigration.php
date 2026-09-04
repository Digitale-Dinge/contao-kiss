<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Migration\Version100;

use DigitaleDinge\ContaoKiss\Migration\AbstractJsonColumnMigration;

class ArticleContentWidthKissStylesMigration extends AbstractJsonColumnMigration
{
    private const array CONTENT_WIDTH_MAP = [
        '' => 'base',
        'small' => 'narrower',
    ];

    protected function getTables(): array
    {
        return ['tl_article'];
    }

    protected function getColumns(): array
    {
        return ['kiss_styles'];
    }

    protected function getValueMaps(): array
    {
        return ['kiss_styles' => ['contentWidth' => self::CONTENT_WIDTH_MAP]];
    }
}
