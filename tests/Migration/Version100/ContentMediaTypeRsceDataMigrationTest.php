<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Tests\Migration\Version100;

use DigitaleDinge\ContaoKiss\Migration\Version100\ContentMediaTypeRsceDataMigration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Schema\AbstractSchemaManager;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;

class ContentMediaTypeRsceDataMigrationTest extends TestCase
{
    public function testDoesNotRunIfTheColumnDoesNotExist(): void
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
            ->willReturn(['id' => true, 'type' => true])
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

        $this->assertFalse(new ContentMediaTypeRsceDataMigration($db)->shouldRun());
    }

    #[DataProvider('getStoredData')]
    public function testShouldRun(string $data, string|null $expected): void
    {
        $db = $this->stubConnection([['id' => 1, 'rsce_data' => $data]]);

        $this->assertSame(null !== $expected, new ContentMediaTypeRsceDataMigration($db)->shouldRun());
    }

    /**
     * @throws Exception
     */
    #[DataProvider('getStoredData')]
    public function testRun(string $data, string|null $expected): void
    {
        $db = $this->mockConnection([['id' => 1, 'rsce_data' => $data]]);

        if (null === $expected) {
            $db
                ->expects($this->never())
                ->method('update')
            ;
        } else {
            $db
                ->expects($this->once())
                ->method('update')
                ->with('tl_content', ['rsce_data' => $expected], ['id' => 1])
            ;
        }

        new ContentMediaTypeRsceDataMigration($db)->run();
    }

    public static function getStoredData(): iterable
    {
        yield 'non-migrated element gets the checkbox and the renamed key' => [
            '{"type":"image","text":"foo"}',
            '{"text":"foo","addMedia":"1","mediaType":"image"}',
        ];

        yield 'migrated element untouched' => [
            '{"mediaType":"image","addMedia":"1"}',
            null,
        ];

        yield 'element without media keeps the checkbox unset' => [
            '{"text":"foo"}',
            null,
        ];

        yield 'empty column untouched' => [
            '',
            null,
        ];

        yield 'checkbox is set even if the target key exists' => [
            '{"type":"image","mediaType":"icon"}',
            '{"type":"image","mediaType":"icon","addMedia":"1"}',
        ];

        yield 'checkbox is not set twice' => [
            '{"type":"image","mediaType":"icon","addMedia":"1"}',
            null,
        ];
    }

    public function testSelectsOnlyTheMediaElements(): void
    {
        $queries = [];
        $parameters = [];

        $db = $this->stubConnection([], $queries, $parameters);

        new ContentMediaTypeRsceDataMigration($db)->run();

        $this->assertSame(['SELECT `id`, `rsce_data` FROM tl_content WHERE `type` IN (:types)'], $queries);
        $this->assertSame([['types' => ['rsce_media_text', 'rsce_media_text_list']]], $parameters);
    }

    public function testReportsTheMigratedKeys(): void
    {
        $db = $this->stubConnection([['id' => 1, 'rsce_data' => '{"type":"image"}']]);

        $result = new ContentMediaTypeRsceDataMigration($db)->run();

        $this->assertTrue($result->isSuccessful());
        $this->assertSame('Migrated mediaType, addMedia for 1 records.', $result->getMessage());
    }

    private function mockConnection(array $rows): Connection&MockObject
    {
        $queries = [];
        $parameters = [];

        return $this->configureConnection($this->createMock(Connection::class), $rows, $queries, $parameters);
    }

    private function stubConnection(array $rows, array &$queries = [], array &$parameters = []): Connection&Stub
    {
        return $this->configureConnection($this->createStub(Connection::class), $rows, $queries, $parameters);
    }

    private function configureConnection(Connection&Stub $db, array $rows, array &$queries, array &$parameters): Connection&Stub
    {
        $schemaManager = $this->createStub(AbstractSchemaManager::class);
        $schemaManager
            ->method('tablesExist')
            ->willReturn(true)
        ;

        $schemaManager
            ->method('listTableColumns')
            ->willReturn(['id' => true, 'type' => true, 'rsce_data' => true])
        ;

        $db
            ->method('createSchemaManager')
            ->willReturn($schemaManager)
        ;

        $db
            ->method('fetchAllAssociative')
            ->willReturnCallback(
                static function (string $query, array $params = []) use ($rows, &$queries, &$parameters) {
                    $queries[] = $query;
                    $parameters[] = $params;

                    return $rows;
                },
            )
        ;

        return $db;
    }
}
