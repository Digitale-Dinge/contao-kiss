<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Migration;

use Contao\CoreBundle\Migration\AbstractMigration;
use Contao\CoreBundle\Migration\MigrationResult;
use Doctrine\DBAL\Connection;

class ArticleContentWidthKissStylesMigration extends AbstractMigration
{
    private const string TABLE = 'tl_article';

    private const array CONTENT_WIDTH_MAP = [
        '' => 'base',
        'small' => 'narrower',
    ];

    public function __construct(
        private readonly Connection $connection,
    ) {
    }

    public function shouldRun(): bool
    {
        $schema = $this->connection->createSchemaManager();

        if (!$schema->tablesExist([self::TABLE])) {
            return false;
        }

        if (!isset($schema->listTableColumns(self::TABLE)['kiss_styles'])) {
            return false;
        }

        return array_any($this->getStyleRows(), fn($row) => null !== $this->getNewContentWidth($row));

    }

    public function run(): MigrationResult
    {
        $updates = [];

        foreach ($this->getStyleRows() as $row) {
            if (null === $styles = $this->getNewContentWidth($row)) {
                continue;
            }

            $updates[(int) $row['id']] = ['kiss_styles' => json_encode($styles)];
        }

        if ([] === $updates) {
            return $this->createResult(true, 'No article contentWidth values to migrate.');
        }

        $this->connection->beginTransaction();

        try {
            foreach ($updates as $id => $columns) {
                $this->connection->update(self::TABLE, $columns, ['id' => $id]);
            }

            $this->connection->commit();
        } catch (\Throwable $e) {
            $this->connection->rollback();

            return $this->createResult(false, $e->getMessage());
        }

        return $this->createResult(true, sprintf('Migrated contentWidth for %d articles.', \count($updates)));
    }

    private function getStyleRows(): array
    {
        return $this->connection->fetchAllAssociative(sprintf('SELECT `id`, `kiss_styles` FROM %s', self::TABLE));
    }

    /**
     * Returns the updated styles array or null if the row needs no migration.
     */
    private function getNewContentWidth(array $row): array|null
    {
        $styles = $row['kiss_styles'] ? (json_decode((string) $row['kiss_styles'], true) ?: []) : [];

        $current = (string) ($styles['contentWidth'] ?? '');
        $new = self::CONTENT_WIDTH_MAP[$current] ?? $current;

        if ($new === $current && isset($styles['contentWidth'])) {
            return null;
        }

        $styles['contentWidth'] = $new;

        return $styles;
    }
}
