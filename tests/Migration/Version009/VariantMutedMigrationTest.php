<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Tests\Migration\Version009;

use DigitaleDinge\ContaoKiss\Migration\Version009\VariantMutedMigration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Schema\AbstractSchemaManager;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;

class VariantMutedMigrationTest extends TestCase
{
    #[DataProvider('getStoredData')]
    public function testShouldRun(string $table, string $column, string $data, string|null $expected): void
    {
        $db = $this->stubConnection($table, $column, [[
            'id' => 1,
            $column => $data,
        ]]);

        $this->assertSame(null !== $expected, new VariantMutedMigration($db)->shouldRun());
    }

    /**
     * @throws Exception
     */
    #[DataProvider('getStoredData')]
    public function testRun(string $table, string $column, string $data, string|null $expected): void
    {
        $db = $this->mockConnection($table, $column, [[
            'id' => 1,
            $column => $data,
        ]]);

        if (null === $expected) {
            $db
                ->expects($this->never())
                ->method('update')
            ;
        }
        else {
            $db
                ->expects($this->once())
                ->method('update')
                ->with(
                    $table,
                    [
                        $column => $expected,
                    ],
                    [
                        'id' => 1,
                    ],
                )
            ;
        }

        new VariantMutedMigration($db)->run();
    }

    public static function getStoredData(): iterable
    {
        yield 'call to action type in kiss_styles' => [
            'tl_content',
            'kiss_styles',
            '{"ctaAsButton":"1","ctaType":"soft","ctaColor":"primary"}',
            '{"ctaAsButton":"1","ctaType":"muted","ctaColor":"primary"}',
        ];

        yield 'card variant in kiss_styles' => [
            'tl_content',
            'kiss_styles',
            '{"showAsCard":"1","elementVariant":"soft"}',
            '{"showAsCard":"1","elementVariant":"muted"}',
        ];

        yield 'form include variants in kiss_styles' => [
            'tl_content',
            'kiss_styles',
            '{"formFieldVariant":"soft","formFieldButtonVariant":"soft"}',
            '{"formFieldVariant":"muted","formFieldButtonVariant":"muted"}',
        ];

        yield 'call to action group column' => [
            'tl_content',
            'callToAction',
            serialize([[
                'text' => 'More',
                'ctaType' => 'soft',
                'ctaColor' => 'primary',
            ]]),
            serialize([[
                'text' => 'More',
                'ctaType' => 'muted',
                'ctaColor' => 'primary',
            ]]),
        ];

        yield 'badge variants nested in a list in rsce_data' => [
            'tl_content',
            'rsce_data',
            '{"list":[{"variant":"soft"},{"variant":"outline"}]}',
            '{"list":[{"variant":"muted"},{"variant":"outline"}]}',
        ];

        yield 'form field variant' => [
            'tl_form_field',
            'kiss_styles',
            '{"fieldVariant":"soft"}',
            '{"fieldVariant":"muted"}',
        ];

        yield 'other variants untouched' => [
            'tl_content',
            'kiss_styles',
            '{"elementVariant":"outline"}',
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

    /**
     * @template T of Connection&Stub
     *
     * @param T $db
     *
     * @return T
     */
    private function configureConnection(Connection&Stub $db, string $table, string $column, array $rows): Connection&Stub
    {
        $schemaManager = $this->createStub(AbstractSchemaManager::class);
        $schemaManager
            ->method('tablesExist')
            ->willReturnCallback(static fn (array $tables): bool => [$table] === $tables)
        ;

        $schemaManager
            ->method('listTableColumns')
            ->willReturn([
                'id' => true,
                strtolower($column) => true,
            ])
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
