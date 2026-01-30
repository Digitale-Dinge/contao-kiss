<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Migration;

use Contao\CoreBundle\Migration\AbstractMigration;
use Contao\CoreBundle\Migration\MigrationResult;
use Contao\StringUtil;
use Doctrine\DBAL\Connection;

class ArticleContentKissStylesMigration extends AbstractMigration
{
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

        if (!isset($contentColumns['contentwidth'], $articleColumns['contentwidth'])) {
            return false;
        }

        $test = $this->connection->fetchOne("SELECT TRUE FROM tl_content WHERE NOT `contentWidth` = ''  LIMIT 1");

        return false !== $test;
    }

    public function run(): MigrationResult
    {
        $this->migrateContentTable();
        $this->migrateArticleTable();

        return $this->createResult(true);
    }

    private function migrateContentTable(): void
    {
        $rows = $this->connection->fetchAllAssociative('SELECT id, kiss_styles, paddingTop, paddingBottom, marginTop, marginBottom, contentWidth FROM tl_content');

        foreach ($rows as $row) {
            // ToDo: Check JSON Fields
            $styles = StringUtil::deserialize($row['kiss_styles'], true);

            $this->updateStyleValue(self::SPACING_MAP, $styles, 'margin_top', $row['marginTop']);
            $this->updateStyleValue(self::SPACING_MAP, $styles, 'margin_bottom', $row['marginBottom']);
            $this->updateStyleValue(self::SPACING_MAP, $styles, 'padding_top', $row['paddingTop']);
            $this->updateStyleValue(self::SPACING_MAP, $styles, 'padding_bottom', $row['paddingBottom']);
            $this->updateStyleValue(self::LAYOUT_COLUMN_MAP, $styles, $row['contentWidth']);

            $this->persist('tl_content', (int) $row['id'], $styles);
        }
    }

    private function migrateArticleTable(): void
    {
        $rows = $this->connection->fetchAllAssociative('SELECT id, kiss_styles, bgColor, paddingTop, paddingBottom, contentWidth FROM tl_article');

        foreach ($rows as $row) {
            $styles = StringUtil::deserialize($row['kiss_styles'], true);

            if (!empty($row['bgColor']) && !isset($styles['backgroundColor'])) {
                $styles['backgroundColor'] = $row['bgColor'];
            }

            $this->updateStyleValue(self::SPACING_MAP, $styles, 'padding_top', $row['paddingTop']);
            $this->updateStyleValue(self::SPACING_MAP, $styles, 'padding_bottom', $row['paddingBottom']);
            $this->updateStyleValue(self::LAYOUT_COLUMN_MAP, $styles, $row['contentWidth']);

            $this->persist('tl_article', (int) $row['id'], $styles);
        }
    }

    private function updateStyleValue(array $map, array &$styles, string $key, string|null $value = null): void
    {
        if (!$value || isset($styles[$key])) {
            return;
        }

        if (isset($map[$value])) {
            $styles[$key] = $map[$value];
        }
    }

    private function persist(string $table, int $id, array $styles): void
    {
        $this->connection->update(
            $table,
            [
                'kiss_styles' => json_encode($styles),
            ],
            [
                'id' => $id,
            ],
        );
    }
}
