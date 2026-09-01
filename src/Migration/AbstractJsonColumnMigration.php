<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Migration;

use Contao\CoreBundle\Migration\AbstractMigration;
use Contao\CoreBundle\Migration\MigrationResult;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

abstract class AbstractJsonColumnMigration extends AbstractMigration
{
    public function __construct(protected readonly Connection $connection)
    {}

    abstract protected function getTables(): array;

    abstract protected function getColumns(): array;

    public function shouldRun(): bool
    {
        return array_any(
            $this->getMigratableTables(),
            fn($table) => array_any($this->getRows($table), fn(array $row) => [] !== $this->getUpdatedColumns($row))
        );
    }

    /**
     * @throws Exception
     */
    public function run(): MigrationResult
    {
        $updates = [];

        foreach ($this->getMigratableTables() as $table) {
            foreach ($this->getRows($table) as $row) {
                if ([] === $columns = $this->getUpdatedColumns($row)) {
                    continue;
                }

                $updates[$table][(int) $row['id']] = $columns;
            }
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

        return $this->createResult(true, $this->getSuccessMessage(array_sum(array_map('count', $updates))));
    }

    protected function getKeyRenames(): array
    {
        return [];
    }

    protected function getValueMaps(): array
    {
        return [];
    }

    protected function getUpdatedColumns(array $row): array
    {
        $columns = [];

        foreach ($row as $column => $value) {
            if ('id' === $column) {
                continue;
            }

            if (null !== $updated = $this->getUpdatedValue($column, $value)) {
                $columns[$column] = $updated;
            }
        }

        return $columns;
    }

    protected function getUpdatedValue(string $column, mixed $value): string|null
    {
        if (null === $data = $this->migrateData($column, $this->decode($value), true)) {
            return null;
        }

        return $this->encode($value, $data);
    }

    protected function migrateData(string $column, array $data, bool $topLevel = false): array|null
    {
        $changed = false;

        foreach ($this->getKeyRenames()[$column] ?? [] as $old => $new) {
            if (!isset($data[$old]) || isset($data[$new])) {
                continue;
            }

            $data[$new] = $data[$old];
            unset($data[$old]);
            $changed = true;
        }

        foreach ($this->getValueMaps()[$column] ?? [] as $key => $map) {
            if (!array_key_exists($key, $data) && !($topLevel && isset($map['']))) {
                continue;
            }

            $current = (string) ($data[$key] ?? '');

            if ($current === ($new = $map[$current] ?? $current)) {
                continue;
            }

            $data[$key] = $new;
            $changed = true;
        }

        foreach ($data as $key => $value) {
            if (is_array($value) && null !== $nested = $this->migrateData($column, $value)) {
                $data[$key] = $nested;
                $changed = true;

                continue;
            }

            if (!is_string($value) || !$this->isEncoded($value)) {
                continue;
            }

            if (null !== $nested = $this->migrateData($column, $this->decode($value))) {
                $data[$key] = $this->encode($value, $nested);
                $changed = true;
            }
        }

        return $changed ? $data : null;
    }

    protected function isEncoded(mixed $value): bool
    {
        if (!is_string($value) || '' === $value) {
            return false;
        }

        return $this->isJson($value) || 1 === preg_match('/^[aOs]:\d+:/', $value);
    }

    protected function isJson(mixed $value): bool
    {
        return is_string($value) && in_array(substr(ltrim($value), 0, 1), ['{', '['], true);
    }

    protected function decode(mixed $value): array
    {
        if (!is_string($value) || '' === $value) {
            return [];
        }

        if ($this->isJson($value)) {
            return json_decode($value, true) ?: [];
        }

        $data = @unserialize($value, ['allowed_classes' => false]);

        return is_array($data) ? $data : [];
    }

    protected function encode(mixed $original, array $data): string
    {
        return $this->isJson($original) || !$this->isEncoded($original)
            ? json_encode($data)
            : serialize($data);
    }

    protected function getMigratableTables(): array
    {
        $tables = [];

        foreach ($this->getTables() as $table) {
            if ([] !== $this->getMigratableColumns($table)) {
                $tables[] = $table;
            }
        }

        return $tables;
    }

    /**
     * @throws Exception
     */
    protected function getMigratableColumns(string $table): array
    {
        $schema = $this->connection->createSchemaManager();

        if (!$schema->tablesExist([$table])) {
            return [];
        }

        $existing = $schema->listTableColumns($table);

        return array_values(array_filter(
            $this->getColumns(),
            static fn (string $column) => isset($existing[strtolower($column)]),
        ));
    }

    protected function getWhere(string $table): string
    {
        return '';
    }

    protected function getParameters(string $table): array
    {
        return [];
    }

    protected function getParameterTypes(string $table): array
    {
        return [];
    }

    /**
     * @throws Exception
     */
    protected function getRows(string $table): array
    {
        $columns = ['id', ...$this->getMigratableColumns($table)];
        $where = $this->getWhere($table);

        $query = sprintf(
            'SELECT %s FROM %s%s',
            implode(', ', array_map(static fn (string $column) => "`$column`", $columns)),
            $table,
            '' === $where ? '' : ' WHERE '.$where,
        );

        return $this->connection->fetchAllAssociative($query, $this->getParameters($table), $this->getParameterTypes($table));
    }

    protected function getMigratedKeys(): array
    {
        $keys = [];

        foreach ($this->getValueMaps() as $maps) {
            $keys = [...$keys, ...array_keys($maps)];
        }

        foreach ($this->getKeyRenames() as $renames) {
            $keys = [...$keys, ...array_values($renames)];
        }

        return array_values(array_unique($keys));
    }

    protected function getKeyLabel(): string
    {
        return implode(', ', $this->getMigratedKeys()) ?: implode(', ', $this->getColumns());
    }

    protected function getSuccessMessage(int $count): string
    {
        return sprintf('Migrated %s for %d records.', $this->getKeyLabel(), $count);
    }
}
