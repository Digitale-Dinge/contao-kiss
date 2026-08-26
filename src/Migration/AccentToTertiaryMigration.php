<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Migration;

use Contao\CoreBundle\Migration\AbstractMigration;
use Contao\CoreBundle\Migration\MigrationResult;
use Contao\StringUtil;
use Doctrine\DBAL\Connection;

/**
 * Renames the colour variant "accent" to "tertiary".
 *
 * The design system has no accent role any more — the modifiers already resolved
 * to tertiary tokens, only the naming lagged behind. Two things are stored in the
 * database and therefore need migrating:
 *
 *  1. class names that ended up in content (.btn-accent → .btn-tertiary):
 *     CSS class fields, rich text and RSCE element data
 *  2. style option values, which store the enum case name ("accent" → "tertiary"):
 *     kiss_styles.backgroundColor / .ctaColor / .fieldColor and the callToAction group
 */
class AccentToTertiaryMigration extends AbstractMigration
{
    private const COMPONENTS = 'alert|badge|breadcrumb|btn|card|checkbox|headline|input|link|radio|range|select|switch|textarea|upload';

    /** Style option keys whose value is a Color/Background enum case name. */
    private const OPTION_KEYS = ['backgroundColor', 'ctaColor', 'fieldColor', 'color'];

    private const PLAIN = 'plain';
    private const SERIALIZED = 'serialized';
    private const JSON = 'json';

    /**
     * table => column => storage format.
     */
    private const TARGETS = [
        'tl_content' => [
            'cssID' => self::SERIALIZED,
            'text' => self::PLAIN,
            'rsce_data' => self::JSON,
            'kiss_styles' => self::JSON,
            'callToAction' => self::SERIALIZED,
        ],
        'tl_article' => ['cssID' => self::SERIALIZED, 'kiss_styles' => self::JSON],
        'tl_module' => ['cssID' => self::SERIALIZED, 'rsce_data' => self::JSON, 'kiss_styles' => self::JSON],
        'tl_form_field' => [
            'class' => self::PLAIN,
            'text' => self::PLAIN,
            'rsce_data' => self::JSON,
            'kiss_styles' => self::JSON,
        ],
        'tl_page' => ['cssClass' => self::PLAIN],
        'tl_layout' => ['cssClass' => self::PLAIN],
        'tl_news' => ['cssClass' => self::PLAIN],
        'tl_calendar_events' => ['cssClass' => self::PLAIN],
    ];

    public function __construct(private readonly Connection $connection)
    {
    }

    public function shouldRun(): bool
    {
        foreach ($this->existingTargets() as $table => $columns) {
            foreach (array_keys($columns) as $column) {
                $found = $this->connection->fetchOne(
                    \sprintf('SELECT TRUE FROM %s WHERE %s LIKE :needle LIMIT 1', $table, $column),
                    ['needle' => '%accent%'],
                );

                if (false !== $found) {
                    return true;
                }
            }
        }

        return false;
    }

    public function run(): MigrationResult
    {
        $count = 0;

        foreach ($this->existingTargets() as $table => $columns) {
            $count += $this->migrateTable($table, $columns);
        }

        return $this->createResult(true, \sprintf('Renamed accent to tertiary in %d row(s).', $count));
    }

    /**
     * @return array<string, array<string, string>> only tables and columns that exist
     */
    private function existingTargets(): array
    {
        $schema = $this->connection->createSchemaManager();
        $targets = [];

        foreach (self::TARGETS as $table => $columns) {
            if (!$schema->tablesExist([$table])) {
                continue;
            }

            $existing = $schema->listTableColumns($table);
            $columns = array_filter($columns, static fn ($column) => isset($existing[strtolower($column)]), ARRAY_FILTER_USE_KEY);

            if ([] !== $columns) {
                $targets[$table] = $columns;
            }
        }

        return $targets;
    }

    /**
     * @param array<string, string> $columns
     */
    private function migrateTable(string $table, array $columns): int
    {
        $names = array_keys($columns);
        $condition = implode(' OR ', array_map(static fn (string $c) => \sprintf('%s LIKE :needle', $c), $names));

        $rows = $this->connection->fetchAllAssociative(
            \sprintf('SELECT id, %s FROM %s WHERE %s', implode(', ', $names), $table, $condition),
            ['needle' => '%accent%'],
        );

        $updates = [];

        foreach ($rows as $row) {
            $changed = [];

            foreach ($columns as $column => $format) {
                $value = $row[$column] ?? null;

                if (null === $value || '' === $value) {
                    continue;
                }

                $new = $this->convert((string) $value, $format);

                if ($new !== $value) {
                    $changed[$column] = $new;
                }
            }

            if ([] !== $changed) {
                $updates[(int) $row['id']] = $changed;
            }
        }

        if ([] === $updates) {
            return 0;
        }

        $this->connection->beginTransaction();

        try {
            foreach ($updates as $id => $values) {
                $this->connection->update($table, $values, ['id' => $id]);
            }

            $this->connection->commit();
        } catch (\Throwable $e) {
            $this->connection->rollback();

            throw $e;
        }

        return \count($updates);
    }

    private function convert(string $value, string $format): string
    {
        // A serialised array must be rebuilt: the class name gets longer, so the
        // string length prefixes would no longer match after a plain replace.
        if (self::SERIALIZED === $format) {
            $data = StringUtil::deserialize($value);

            return \is_array($data) ? serialize($this->walk($data)) : $this->replace($value);
        }

        if (self::JSON === $format) {
            $data = json_decode($value, true);

            if (!\is_array($data)) {
                return $this->replace($value);
            }

            return json_encode($this->walk($data)) ?: $value;
        }

        return $this->replace($value);
    }

    /**
     * @param array<mixed> $data
     *
     * @return array<mixed>
     */
    private function walk(array $data, string|int|null $parentKey = null): array
    {
        foreach ($data as $key => $value) {
            if (\is_array($value)) {
                $data[$key] = $this->walk($value, $key);
                continue;
            }

            if (!\is_string($value)) {
                continue;
            }

            // A style option stores the enum case name on its own
            $optionKey = \is_string($key) ? $key : $parentKey;

            if ('accent' === $value && \in_array($optionKey, self::OPTION_KEYS, true)) {
                $data[$key] = 'tertiary';
                continue;
            }

            $data[$key] = $this->replace($value);
        }

        return $data;
    }

    private function replace(string $value): string
    {
        return preg_replace('/(?<![-\w])('.self::COMPONENTS.')-accent(?![-\w])/', '$1-tertiary', $value) ?? $value;
    }
}
