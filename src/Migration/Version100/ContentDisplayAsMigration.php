<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Migration\Version100;

use DigitaleDinge\ContaoKiss\Migration\AbstractJsonColumnMigration;
use Doctrine\DBAL\ArrayParameterType;

class ContentDisplayAsMigration extends AbstractJsonColumnMigration
{
    private const array TYPES = ['download', 'downloads'];

    protected function getTables(): array
    {
        return ['tl_content'];
    }

    protected function getColumns(): array
    {
        return ['kiss_styles'];
    }

    protected function getKeyRenames(): array
    {
        return ['kiss_styles' => ['ctaAsButton' => 'displayAs']];
    }

    protected function getValueMaps(): array
    {
        return ['kiss_styles' => ['displayAs' => ['1' => 'button', '' => 'text']]];
    }

    protected function getWhere(string $table): string
    {
        return '`type` IN (:types) AND `kiss_styles` LIKE :key';
    }

    protected function getParameters(string $table): array
    {
        return ['types' => self::TYPES, 'key' => '%"ctaAsButton"%'];
    }

    protected function getParameterTypes(string $table): array
    {
        return ['types' => ArrayParameterType::STRING];
    }
}
