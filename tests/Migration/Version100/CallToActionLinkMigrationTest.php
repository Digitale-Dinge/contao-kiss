<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Tests\Migration\Version100;

use DigitaleDinge\ContaoKiss\Migration\Version100\CallToActionLinkMigration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Schema\AbstractSchemaManager;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;

class CallToActionLinkMigrationTest extends TestCase
{
    #[DataProvider('getStoredData')]
    public function testShouldRun(string $table, string $column, string $data, string|null $expected): void
    {
        $db = $this->stubConnection($table, $column, [['id' => 1, $column => $data]]);

        $this->assertSame(null !== $expected, new CallToActionLinkMigration($db)->shouldRun());
    }

    /**
     * @throws Exception
     */
    #[DataProvider('getStoredData')]
    public function testRun(string $table, string $column, string $data, string|null $expected): void
    {
        $db = $this->mockConnection($table, $column, [['id' => 1, $column => $data]]);

        if (null === $expected) {
            $db
                ->expects($this->never())
                ->method('update')
            ;
        } else {
            $db
                ->expects($this->once())
                ->method('update')
                ->with($table, [$column => $expected], ['id' => 1])
            ;
        }

        new CallToActionLinkMigration($db)->run();
    }

    public static function getStoredData(): iterable
    {
        yield 'hyperlink or download in kiss_styles' => [
            'tl_content',
            'kiss_styles',
            '{"ctaAsButton":"1","ctaType":"link","ctaColor":"primary"}',
            '{"ctaAsButton":"1","ctaType":"text","ctaColor":"primary"}',
        ];

        yield 'call to action group column' => [
            'tl_content',
            'callToAction',
            serialize([['text' => 'More', 'ctaType' => 'link', 'ctaColor' => 'primary']]),
            serialize([['text' => 'More', 'ctaType' => 'text', 'ctaColor' => 'primary']]),
        ];

        yield 'call to action nested in a list in rsce_data' => [
            'tl_content',
            'rsce_data',
            '{"list":[{"callToAction":[{"ctaType":"link"}]},{"callToAction":[{"ctaType":"outline"}]}]}',
            '{"list":[{"callToAction":[{"ctaType":"text"}]},{"callToAction":[{"ctaType":"outline"}]}]}',
        ];

        yield 'submit field variant' => [
            'tl_form_field',
            'kiss_styles',
            '{"fieldVariant":"link"}',
            '{"fieldVariant":"text"}',
        ];

        yield 'other variants untouched' => [
            'tl_content',
            'kiss_styles',
            '{"ctaType":"outline"}',
            null,
        ];

        yield 'empty column untouched' => [
            'tl_content',
            'kiss_styles',
            '',
            null,
        ];
    }

    private function mockConnection(string $table, string $column, array $rows): Connection&MockObject
    {
        return $this->configureConnection($this->createMock(Connection::class), $table, $column, $rows);
    }

    private function stubConnection(string $table, string $column, array $rows): Connection&Stub
    {
        return $this->configureConnection($this->createStub(Connection::class), $table, $column, $rows);
    }

    private function configureConnection(Connection&Stub $db, string $table, string $column, array $rows): Connection&Stub
    {
        $schemaManager = $this->createStub(AbstractSchemaManager::class);
        $schemaManager
            ->method('tablesExist')
            ->willReturnCallback(static fn (array $tables): bool => [$table] === $tables)
        ;

        $schemaManager
            ->method('listTableColumns')
            ->willReturn(['id' => true, strtolower($column) => true])
        ;

        $db
            ->method('createSchemaManager')
            ->willReturn($schemaManager)
        ;

        $db
            ->method('fetchAllAssociative')
            ->willReturn($rows)
        ;

        return $db;
    }
}
