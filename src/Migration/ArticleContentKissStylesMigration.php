<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Migration;

use Contao\CoreBundle\Migration\AbstractMigration;
use Contao\CoreBundle\Migration\MigrationResult;
use Contao\DcaExtractor;
use Doctrine\DBAL\Connection;

class ArticleContentKissStylesMigration extends AbstractMigration
{
    private static array $kissContentColumns = [];
    private static array $kissArticleColumns = [];

    private const array SPACING_MAP = [
        'half' => 'half',
        '1x' => 'one',
        '2x' => 'two',
        '3x' => 'three',
        '4x' => 'four',
        '5x' => 'five',
    ];

    private const array LAYOUT_COLUMN_MAP = [
        'u-size--small' => 'small',
        'u-size--regular' => 'base',
        'u-size--smaller' => 'narrow',
        'u-size--nopad' => 'full_pad',
        'u-size--full' => 'full',
    ];

    private const array TABLE_MAP = [
        'tl_content' => [
            ['topline' => []],
            ['marginTop' => self::SPACING_MAP],
            ['marginBottom' => self::SPACING_MAP],
            ['paddingTop' => self::SPACING_MAP],
            ['paddingBottom' => self::SPACING_MAP],
            ['contentWidth' => self::LAYOUT_COLUMN_MAP],
            ['icon' => []],
            ['iconPosition' => []],
        ],
        'tl_article' => [
            ['paddingTop'=> self::SPACING_MAP],
            ['paddingBottom' => self::SPACING_MAP],
            ['contentWidth' => self::LAYOUT_COLUMN_MAP],
        ],
    ];

    public function __construct(
        private readonly Connection $connection,
    ) {
    }

    public function shouldRun(): bool
    {
        return false;

        $schema = $this->connection->createSchemaManager();

        if (!$schema->tablesExist(['tl_content', 'tl_article'])) {
            return false;
        }

        $contentColumns = $schema->listTableColumns('tl_content');
        $articleColumns = $schema->listTableColumns('tl_article');

        if (!isset($contentColumns['kiss_styles'], $articleColumns['kiss_styles'])) {
            return false;
        }

        self::$kissContentColumns = array_filter($this->getDefaultKissStyles('tl_content'), fn($col) => isset($contentColumns[strtolower($col)]));
        self::$kissArticleColumns = array_filter($this->getDefaultKissStyles('tl_article'), fn($col) => isset($articleColumns[strtolower($col)]));

        return
            $this->tableHasRemainingStyleMigrations('tl_content', self::$kissContentColumns)
            || $this->tableHasRemainingStyleMigrations('tl_article', self::$kissArticleColumns);
    }

    public function run(): MigrationResult
    {
        $this->migrateTable('tl_content');
        $this->migrateTable('tl_article');

        return $this->createResult(true);
    }

    private function tableHasRemainingStyleMigrations(string $table, array $cols): bool
    {
        if ($cols === []) {
            return false;
        }

        $condition = array_map(static fn (string $col) => sprintf('`%s` <> :empty', $col), $cols);

        $query = sprintf(
            'SELECT TRUE FROM %s WHERE %s LIMIT 1',
            $table,
            implode(' OR ', $condition)
        );

        return $this->connection->fetchOne($query, ['empty' => '']) !== false;
    }

    private function getDefaultKissStyles(string $table): array
    {
        $extractor = DcaExtractor::getInstance($table);
        $fields = $extractor->getVirtualFields();

        return array_keys($fields, 'kiss_styles');
    }

    private function getOldColumnsForTable(string $table): array
    {
        return match($table) {
            'tl_article' => self::$kissArticleColumns,
            'tl_content' => self::$kissContentColumns,
            default => [],
        };
    }

    private function getStyleRowsForTable(string $table): array
    {
        $styleCols = $this->getOldColumnsForTable($table);

        if (empty($styleCols)) {
            return [];
        }

        $columns = array_merge(['id', 'kiss_styles'], $styleCols);

        $query = sprintf('SELECT %s FROM %s', implode(', ', array_map(static fn ($c) => "`$c`", $columns)), $table);

        return $this->connection->fetchAllAssociative($query);
    }

    private function updateStyleValue(array $map, array &$styles, string $key, string|null $value = null): void
    {
        // Do not overwrite existing values
        if (isset($styles[$key])) {
            return;
        }

        if (isset($map[$value])) {
            $styles[$key] = $map[$value];
        } else {
            $styles[$key] = $value;
        }
    }

    private function migrateTable(string $table): void
    {
        if ([] === ($rows = $this->getStyleRowsForTable($table))) {
            return;
        }

        $updates = [];

        // Reset the old value
        $resetCols = array_fill_keys($this->getOldColumnsForTable($table), '');

        foreach ($rows as $row) {
            $styles = $row['kiss_styles'] ? json_decode($row['kiss_styles'], true) : [];

            // Migrate the backgroundColor
            if (
                $table === 'tl_article'
                && !empty($row['bgColor'])
                && !isset($styles['backgroundColor'])
            ) {
                $styles['backgroundColor'] = $row['bgColor'];
            }

            foreach (self::TABLE_MAP[$table] ?? [] as $mapping) {
                foreach ($mapping as $key => $map) {
                    $this->updateStyleValue($map, $styles, $key, $row[$key] ?? null);
                }
            }

            // Do not update if we have no styles or values
            if (empty($styles) || empty(array_filter($styles))) {
                continue;
            }

            $updates[(int) $row['id']] = [...$resetCols, ...['kiss_styles' => json_encode($styles)]];
        }

        if ([] === $updates) {
            return;
        }

        $this->connection->beginTransaction();

        try {
            foreach ($updates as $id => $columns) {
                $this->connection->update(
                    $table,
                    $columns,
                    ['id' => $id]
                );
            }

            $this->connection->commit();
        } catch (\Throwable) {
            $this->connection->rollback();
        }
    }
}
