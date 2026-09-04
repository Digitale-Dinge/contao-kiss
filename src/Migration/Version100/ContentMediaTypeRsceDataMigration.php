<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Migration\Version100;

use DigitaleDinge\ContaoKiss\Migration\AbstractJsonColumnMigration;
use Doctrine\DBAL\ArrayParameterType;

class ContentMediaTypeRsceDataMigration extends AbstractJsonColumnMigration
{
    private const array TYPES = ['rsce_media_text', 'rsce_media_text_list'];

    protected function getTables(): array
    {
        return ['tl_content'];
    }

    protected function getColumns(): array
    {
        return ['rsce_data'];
    }

    protected function getKeyRenames(): array
    {
        return ['rsce_data' => ['type' => 'mediaType']];
    }

    protected function getWhere(string $table): string
    {
        return '`type` IN (:types)';
    }

    protected function getParameters(string $table): array
    {
        return ['types' => self::TYPES];
    }

    protected function getParameterTypes(string $table): array
    {
        return ['types' => ArrayParameterType::STRING];
    }

    protected function migrateData(string $column, array $data, bool $topLevel = false): array|null
    {
        if (!$topLevel || !isset($data['type'])) {
            return parent::migrateData($column, $data, $topLevel);
        }

        $changed = '1' !== ($data['addMedia'] ?? null);
        $data['addMedia'] = '1';

        return parent::migrateData($column, $data, $topLevel) ?? ($changed ? $data : null);
    }

    protected function getMigratedKeys(): array
    {
        return [...parent::getMigratedKeys(), 'addMedia'];
    }
}
