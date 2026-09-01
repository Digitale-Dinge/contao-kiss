<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Tests\Migration;

use DigitaleDinge\ContaoKiss\Migration\AbstractJsonColumnMigration;
use Doctrine\DBAL\ArrayParameterType;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\AbstractSchemaManager;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;

class AbstractJsonColumnMigrationTest extends TestCase
{
    public function testDoesNotRunIfTableDoesNotExist(): void
    {
        $schemaManager = $this->createMock(AbstractSchemaManager::class);
        $schemaManager
            ->expects($this->once())
            ->method('tablesExist')
            ->with(['tl_content'])
            ->willReturn(false)
        ;

        $schemaManager
            ->expects($this->never())
            ->method('listTableColumns')
        ;

        $db = $this->createMock(Connection::class);
        $db
            ->method('createSchemaManager')
            ->willReturn($schemaManager)
        ;

        $db
            ->expects($this->never())
            ->method('fetchAllAssociative')
        ;

        $migration = $this->getMigration($db, ['kiss_styles'], [], ['kiss_styles' => ['contentWidth' => ['small' => 'narrower']]]);

        $this->assertFalse($migration->shouldRun());
    }

    public function testDoesNotRunIfColumnsDoNotExist(): void
    {
        $schemaManager = $this->createMock(AbstractSchemaManager::class);
        $schemaManager
            ->expects($this->once())
            ->method('tablesExist')
            ->with(['tl_content'])
            ->willReturn(true)
        ;

        $schemaManager
            ->expects($this->once())
            ->method('listTableColumns')
            ->with('tl_content')
            ->willReturn(['id' => true, 'headline' => true])
        ;

        $db = $this->createMock(Connection::class);
        $db
            ->method('createSchemaManager')
            ->willReturn($schemaManager)
        ;

        $db
            ->expects($this->never())
            ->method('fetchAllAssociative')
        ;

        $migration = $this->getMigration($db, ['kiss_styles'], [], ['kiss_styles' => ['contentWidth' => ['small' => 'narrower']]]);

        $this->assertFalse($migration->shouldRun());
    }

    #[DataProvider('getShouldRunRows')]
    public function testShouldRun(array $rows, bool $expected): void
    {
        $db = $this->stubConnection(['tl_content' => ['id', 'kiss_styles']], $rows);

        $migration = $this->getMigration($db, ['kiss_styles'], [], ['kiss_styles' => ['contentWidth' => ['small' => 'narrower']]]);

        $this->assertSame($expected, $migration->shouldRun());
    }

    public static function getShouldRunRows(): iterable
    {
        yield 'no rows' => [
            [],
            false,
        ];

        yield 'empty column' => [
            [['id' => 1, 'kiss_styles' => '']],
            false,
        ];

        yield 'value already migrated' => [
            [['id' => 1, 'kiss_styles' => '{"contentWidth":"narrower"}']],
            false,
        ];

        yield 'legacy value' => [
            [['id' => 1, 'kiss_styles' => '{"contentWidth":"small"}']],
            true,
        ];

        yield 'legacy value in one of several rows' => [
            [
                ['id' => 1, 'kiss_styles' => '{"contentWidth":"narrower"}'],
                ['id' => 2, 'kiss_styles' => '{"contentWidth":"small"}'],
            ],
            true,
        ];
    }

    #[DataProvider('getStoredData')]
    public function testRun(array $columns, array $renames, array $maps, array $row, array|null $expected): void
    {
        $db = $this->mockConnection(['tl_content' => array_keys($row)], [$row]);

        if (null === $expected) {
            $db
                ->expects($this->never())
                ->method('update')
            ;
        } else {
            $db
                ->expects($this->once())
                ->method('update')
                ->with('tl_content', $expected, ['id' => $row['id']])
            ;
        }

        $migration = $this->getMigration($db, $columns, $renames, $maps);
        $migration->run();
    }

    public static function getStoredData(): iterable
    {
        yield 'renames a key' => [
            ['rsce_data'],
            ['rsce_data' => ['type' => 'mediaType']],
            [],
            ['id' => 1, 'rsce_data' => '{"type":"image","text":"foo"}'],
            ['rsce_data' => '{"text":"foo","mediaType":"image"}'],
        ];

        yield 'keeps existing target key' => [
            ['rsce_data'],
            ['rsce_data' => ['type' => 'mediaType']],
            [],
            ['id' => 1, 'rsce_data' => '{"type":"image","mediaType":"icon"}'],
            null,
        ];

        yield 'replaces mapped value' => [
            ['kiss_styles'],
            [],
            ['kiss_styles' => ['backgroundColor' => ['base_100' => 'neutral_one']]],
            ['id' => 1, 'kiss_styles' => '{"backgroundColor":"base_100","textAlignment":"text-end"}'],
            ['kiss_styles' => '{"backgroundColor":"neutral_one","textAlignment":"text-end"}'],
        ];

        yield 'ignores unmapped value' => [
            ['kiss_styles'],
            [],
            ['kiss_styles' => ['backgroundColor' => ['base_100' => 'neutral_one']]],
            ['id' => 1, 'kiss_styles' => '{"backgroundColor":"primary"}'],
            null,
        ];

        yield 'fills empty column' => [
            ['kiss_styles'],
            [],
            ['kiss_styles' => ['contentWidth' => ['' => 'base']]],
            ['id' => 1, 'kiss_styles' => ''],
            ['kiss_styles' => '{"contentWidth":"base"}'],
        ];

        yield 'skips already migrated value' => [
            ['rsce_data'],
            [],
            ['rsce_data' => ['contentWidth' => ['' => 'base']]],
            ['id' => 1, 'rsce_data' => '{"contentWidth":"base","list":[{"text":"foo"}]}'],
            null,
        ];

        yield 'replaces value in a nested list' => [
            ['rsce_data'],
            [],
            ['rsce_data' => ['textAppearance' => ['x_small' => 'small']]],
            ['id' => 1, 'rsce_data' => '{"list":[{"textAppearance":"x_small"}]}'],
            ['rsce_data' => '{"list":[{"textAppearance":"small"}]}'],
        ];

        yield 'replaces value in a serialized array inside json' => [
            ['rsce_data'],
            [],
            ['rsce_data' => ['appearance' => ['x_small' => 'small']]],
            ['id' => 1, 'rsce_data' => json_encode(['headline' => serialize(['value' => 'Foo', 'appearance' => 'x_small'])])],
            ['rsce_data' => json_encode(['headline' => serialize(['value' => 'Foo', 'appearance' => 'small'])])],
        ];

        yield 'replaces value in a serialized column' => [
            ['headline'],
            [],
            ['headline' => ['appearance' => ['x_small' => 'small']]],
            ['id' => 1, 'headline' => serialize(['value' => 'Foo', 'appearance' => 'x_small'])],
            ['headline' => serialize(['value' => 'Foo', 'appearance' => 'small'])],
        ];

        yield 'ignores plain string column' => [
            ['headline'],
            [],
            ['headline' => ['appearance' => ['x_small' => 'small']]],
            ['id' => 1, 'headline' => 'Foobar'],
            null,
        ];

        yield 'migrates only the configured column' => [
            ['kiss_styles', 'rsce_data'],
            [],
            ['kiss_styles' => ['textAppearance' => ['x_small' => 'small']]],
            ['id' => 1, 'kiss_styles' => '{"textAppearance":"x_small"}', 'rsce_data' => '{"textAppearance":"x_small"}'],
            ['kiss_styles' => '{"textAppearance":"small"}'],
        ];

        yield 'migrates every configured column' => [
            ['kiss_styles', 'rsce_data'],
            [],
            [
                'kiss_styles' => ['textAppearance' => ['x_small' => 'small']],
                'rsce_data' => ['textAppearance' => ['x_small' => 'small']],
            ],
            ['id' => 1, 'kiss_styles' => '{"textAppearance":"x_small"}', 'rsce_data' => '{"textAppearance":"x_small"}'],
            [
                'kiss_styles' => '{"textAppearance":"small"}',
                'rsce_data' => '{"textAppearance":"small"}',
            ],
        ];
    }

    public function testRunUpdatesEveryTable(): void
    {
        $db = $this->mockConnection(
            [
                'tl_content' => ['id', 'kiss_styles'],
                'tl_article' => ['id', 'kiss_styles'],
            ],
            [['id' => 1, 'kiss_styles' => '{"backgroundColor":"base_100"}']],
        );

        $db
            ->expects($this->exactly(2))
            ->method('update')
        ;

        $db
            ->expects($this->once())
            ->method('commit')
        ;

        $migration = $this->getMigration(
            $db,
            ['kiss_styles'],
            [],
            ['kiss_styles' => ['backgroundColor' => ['base_100' => 'neutral_one']]],
            ['tl_content', 'tl_article'],
        );

        $result = $migration->run();

        $this->assertTrue($result->isSuccessful());
        $this->assertSame('Migrated backgroundColor for 2 records.', $result->getMessage());
    }

    public function testRunSelectsTheExistingColumnsAndAppliesTheWhere(): void
    {
        $queries = [];

        $db = $this->stubConnection(['tl_content' => ['id', 'rsce_data']], [], $queries);

        $migration = $this->getMigration(
            $db,
            ['kiss_styles', 'rsce_data'],
            ['rsce_data' => ['type' => 'mediaType']],
            [],
            ['tl_content'],
            '`type` IN (:types)',
        );

        $migration->run();

        $this->assertSame(['SELECT `id`, `rsce_data` FROM tl_content WHERE `type` IN (:types)'], $queries);
    }

    public function testRunReportsTheMigratedKeys(): void
    {
        $db = $this->stubConnection(
            ['tl_content' => ['id', 'rsce_data']],
            [['id' => 1, 'rsce_data' => '{"type":"image"}']],
        );

        $migration = $this->getMigration($db, ['rsce_data'], ['rsce_data' => ['type' => 'mediaType']]);

        $this->assertSame('Migrated mediaType for 1 records.', $migration->run()->getMessage());
    }

    public function testRunRollsBackOnException(): void
    {
        $db = $this->mockConnection(
            ['tl_content' => ['id', 'rsce_data']],
            [['id' => 1, 'rsce_data' => '{"type":"image"}']],
        );

        $db
            ->method('update')
            ->willThrowException(new \RuntimeException('Boom'))
        ;

        $db
            ->expects($this->once())
            ->method('rollback')
        ;

        $db
            ->expects($this->never())
            ->method('commit')
        ;

        $migration = $this->getMigration($db, ['rsce_data'], ['rsce_data' => ['type' => 'mediaType']]);

        $result = $migration->run();

        $this->assertFalse($result->isSuccessful());
        $this->assertSame('Boom', $result->getMessage());
    }

    private function mockConnection(array $schema, array $rows, array &$queries = []): Connection&MockObject
    {
        return $this->configureConnection($this->createMock(Connection::class), $schema, $rows, $queries);
    }

    private function stubConnection(array $schema, array $rows, array &$queries = []): Connection&Stub
    {
        return $this->configureConnection($this->createStub(Connection::class), $schema, $rows, $queries);
    }

    private function configureConnection(Connection&Stub $db, array $schema, array $rows, array &$queries): Connection&Stub
    {
        $schemaManager = $this->createStub(AbstractSchemaManager::class);
        $schemaManager
            ->method('tablesExist')
            ->willReturnCallback(static fn (array $tables) => [] === array_diff($tables, array_keys($schema)))
        ;

        $schemaManager
            ->method('listTableColumns')
            ->willReturnCallback(static fn (string $table) => array_fill_keys($schema[$table] ?? [], true))
        ;

        $db
            ->method('createSchemaManager')
            ->willReturn($schemaManager)
        ;

        $db
            ->method('fetchAllAssociative')
            ->willReturnCallback(
                static function (string $query) use ($rows, &$queries) {
                    $queries[] = $query;

                    return $rows;
                },
            )
        ;

        return $db;
    }

    private function getMigration(
        Connection $db,
        array $columns,
        array $renames = [],
        array $maps = [],
        array $tables = ['tl_content'],
        string $where = '',
    ): AbstractJsonColumnMigration {
        return new class($db, $tables, $columns, $renames, $maps, $where) extends AbstractJsonColumnMigration {
            public function __construct(
                Connection $connection,
                private readonly array $tables,
                private readonly array $columns,
                private readonly array $renames,
                private readonly array $maps,
                private readonly string $where,
            ) {
                parent::__construct($connection);
            }

            protected function getTables(): array
            {
                return $this->tables;
            }

            protected function getColumns(): array
            {
                return $this->columns;
            }

            protected function getKeyRenames(): array
            {
                return $this->renames;
            }

            protected function getValueMaps(): array
            {
                return $this->maps;
            }

            protected function getWhere(string $table): string
            {
                return $this->where;
            }

            protected function getParameterTypes(string $table): array
            {
                return '' === $this->where ? [] : ['types' => ArrayParameterType::STRING];
            }
        };
    }
}
