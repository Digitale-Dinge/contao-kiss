<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Command;

use Contao\StringUtil;
use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Attribute\Option;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'contao_kiss:find-style-values',
    description: 'Lists every record that stores one of the given values under one of the given keys.',
    help: <<<'HELP'
        The <info>%command.name%</info> command decodes the kiss_styles, headline and rsce_data
        columns and reports where a value is stored, including nested lists and serialized blobs.

          <info>php %command.full_name% textAppearance x_small small medium</info>
          <info>php %command.full_name% textAppearance,appearance x_small --table=tl_content</info>
          <info>php %command.full_name% elementSize xs sm --backend-prefix=https://example.org/contao</info>
        HELP,
)]
class FindKissStyleValuesCommand
{
    private const array TABLES = [
        'tl_article' => ['do' => 'article', 'columns' => ['kiss_styles']],
        'tl_content' => ['do' => 'article', 'columns' => ['kiss_styles', 'headline', 'rsce_data']],
        'tl_module' => ['do' => 'themes', 'columns' => ['kiss_styles', 'headline']],
        'tl_form_field' => ['do' => 'form', 'columns' => ['kiss_styles']],
    ];

    private const array CONTEXT_COLUMNS = ['pid', 'type'];

    public function __construct(
        private readonly Connection $connection,
    ) {
    }

    public function __invoke(
        SymfonyStyle $io,
        #[Argument(description: 'The key, or a comma separated list of keys')]
        string $keys,
        #[Argument(description: 'The values to look for')]
        array $values,
        #[Option(description: 'Limit the report to a single table', shortcut: 't')]
        string|null $table = null,
        #[Option(name: 'backend-prefix', description: 'Backend URL to build an edit link from, e.g. https://example.org/contao')]
        string|null $backendPrefix = null,
    ): int {
        $keys = array_values(array_filter(array_map('trim', explode(',', $keys))));
        $tables = self::TABLES;

        if (null !== $table) {
            if (!isset($tables[$table])) {
                $io->error(sprintf('Unknown table "%s". Known tables: %s.', $table, implode(', ', array_keys($tables))));

                return Command::INVALID;
            }

            $tables = [$table => $tables[$table]];
        }

        $rows = [];

        foreach ($tables as $name => $config) {
            $rows = [...$rows, ...$this->findInTable($name, $config, $keys, $values, $backendPrefix)];
        }

        if ([] === $rows) {
            $io->success('No records found.');

            return Command::SUCCESS;
        }

        $headers = ['Table', 'ID', 'PID', 'Type', 'Column', 'Path', 'Value'];

        if (null !== $backendPrefix) {
            $headers[] = 'Edit';
        }

        $io->table($headers, $rows);

        $records = array_unique(array_map(static fn (array $row) => $row[0].':'.$row[1], $rows));

        $io->writeln(sprintf('%d occurrence(s) in %d record(s).', \count($rows), \count($records)));

        return Command::SUCCESS;
    }

    private function findInTable(string $table, array $config, array $keys, array $values, string|null $backendPrefix): array
    {
        $schema = $this->connection->createSchemaManager();

        if (!$schema->tablesExist([$table])) {
            return [];
        }

        $existing = $schema->listTableColumns($table);
        $columns = array_values(array_filter($config['columns'], static fn (string $column) => isset($existing[strtolower($column)])));

        if ([] === $columns) {
            return [];
        }

        $context = array_values(array_filter(self::CONTEXT_COLUMNS, static fn (string $column) => isset($existing[strtolower($column)])));

        $query = sprintf(
            'SELECT %s FROM %s ORDER BY id',
            implode(', ', array_map(static fn (string $column) => "`$column`", ['id', ...$context, ...$columns])),
            $table,
        );

        $rows = [];

        foreach ($this->connection->fetchAllAssociative($query) as $row) {
            foreach ($columns as $column) {
                foreach ($this->findInValue($row[$column] ?? null, $keys, $values) as $path => $value) {
                    $result = [
                        $table,
                        $row['id'],
                        $row['pid'] ?? '',
                        $row['type'] ?? '',
                        $column,
                        $path,
                        $value,
                    ];

                    if (null !== $backendPrefix) {
                        $result[] = $this->getEditUrl($backendPrefix, $config['do'], $table, (int) $row['id']);
                    }

                    $rows[] = $result;
                }
            }
        }

        return $rows;
    }

    private function getEditUrl(string $prefix, string $do, string $table, int $id): string
    {
        return sprintf(
            '%s?%s',
            rtrim($prefix, '/'),
            http_build_query(['do' => $do, 'table' => $table, 'act' => 'edit', 'id' => $id]),
        );
    }

    private function findInValue(mixed $value, array $keys, array $values, string $path = ''): array
    {
        $found = [];

        foreach ($this->decode($value) as $key => $item) {
            $current = '' === $path ? (string) $key : $path.'.'.$key;

            if (\in_array($key, $keys, true) && \is_string($item) && \in_array($item, $values, true)) {
                $found[$current] = $item;

                continue;
            }

            if (\is_array($item) || $this->isEncoded($item)) {
                $found = [...$found, ...$this->findInValue($item, $keys, $values, $current)];
            }
        }

        return $found;
    }

    private function isEncoded(mixed $value): bool
    {
        if (!\is_string($value) || '' === $value) {
            return false;
        }

        return $this->isJson($value) || 1 === preg_match('/^[aOs]:\d+:/', $value);
    }

    private function isJson(mixed $value): bool
    {
        return \is_string($value) && \in_array(substr(ltrim($value), 0, 1), ['{', '['], true);
    }

    private function decode(mixed $value): array
    {
        if (\is_array($value)) {
            return $value;
        }

        if (!\is_string($value) || '' === $value) {
            return [];
        }

        if ($this->isJson($value)) {
            return json_decode($value, true) ?: [];
        }

        $data = StringUtil::deserialize($value);

        return \is_array($data) ? $data : [];
    }
}
