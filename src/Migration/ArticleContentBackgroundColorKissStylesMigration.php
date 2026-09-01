<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Migration;

use Contao\CoreBundle\Migration\AbstractMigration;
use Contao\CoreBundle\Migration\MigrationResult;
use Doctrine\DBAL\Connection;

class ArticleContentBackgroundColorKissStylesMigration extends AbstractMigration
{
    private const array TABLES = ['tl_content', 'tl_article'];

    private const array BACKGROUND_COLOR_MAP = [
        'base_100' => 'neutral_one',
        'base_200' => 'neutral_two',
        'base_300' => 'neutral_three',
        'base_content' => 'neutral_inverse',
    ];

    public function __construct(
        private readonly Connection $connection,
    ) {
    }

    public function shouldRun(): bool
    {
        $schema = $this->connection->createSchemaManager();

        foreach (self::TABLES as $table) {
            if (!$schema->tablesExist([$table])) {
                continue;
            }

            if (!isset($schema->listTableColumns($table)['kiss_styles'])) {
                continue;
            }

            if (array_any($this->getStyleRows($table), fn($row) => null !== $this->getNewBackgroundColor($row))) {
                return true;
            }
        }

        return false;
    }

    public function run(): MigrationResult
    {
        $updates = [];

        foreach (self::TABLES as $table) {
            foreach ($this->getStyleRows($table) as $row) {
                if (null === $styles = $this->getNewBackgroundColor($row)) {
                    continue;
                }

                $updates[$table][(int) $row['id']] = ['kiss_styles' => json_encode($styles)];
            }
        }

        if ([] === $updates) {
            return $this->createResult(true, 'No backgroundColor values to migrate.');
        }

        $this->connection->beginTransaction();

        try {
            foreach ($updates as $table => $rows) {
                foreach ($rows as $id => $columns) {
                    $this->connection->update($table, $columns, ['id' => $id]);
                }
            }

            $this->connection->commit();
        } catch (\Throwable $e) {
            $this->connection->rollback();

            return $this->createResult(false, $e->getMessage());
        }

        return $this->createResult(true, sprintf('Migrated backgroundColor for %d records.', array_sum(array_map('count', $updates))));
    }

    private function getStyleRows(string $table): array
    {
        return $this->connection->fetchAllAssociative(sprintf('SELECT `id`, `kiss_styles` FROM %s', $table));
    }

    /**
     * Returns the updated styles array or null if the row needs no migration.
     */
    private function getNewBackgroundColor(array $row): array|null
    {
        $styles = $row['kiss_styles'] ? (json_decode((string) $row['kiss_styles'], true) ?: []) : [];

        $current = (string) ($styles['backgroundColor'] ?? '');
        $new = self::BACKGROUND_COLOR_MAP[$current] ?? $current;

        if ($new === $current) {
            return null;
        }

        $styles['backgroundColor'] = $new;

        return $styles;
    }
}
