<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Twig\Runtime;

use Contao\CoreBundle\String\HtmlAttributes;
use DigitaleDinge\ContaoKiss\EventListener\TranslatableEnumTrait;
use DigitaleDinge\ContaoKiss\Styles\Option\Layout\Column;
use DigitaleDinge\ContaoKiss\Twig\Global\StylesVariable;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extension\RuntimeExtensionInterface;

final class BackendStylesRuntime implements RuntimeExtensionInterface
{
    use TranslatableEnumTrait;

    private array $gridColumnLabels;
    private array $cache = [];

    public function __construct(
        private readonly Connection $connection,
        private readonly StylesVariable $stylesVariable,
        private readonly TranslatorInterface $translator,
    ) {
        $this->gridColumnLabels = $this->getTranslatedOptions(Column::class);
    }

    /**
     * @throws Exception
     */
    public function getGridClasses(int $id, string $table): array
    {
        $styles = $this->getKissStyles($id, $table);

        if (empty($styles['gridColumns'])) {
            return [];
        }

        return [
            'kiss_grid',
            $this->getBackendClass($styles, 'columns'),
            //$this->getBackendClass($styles, 'gap'),
        ];
    }

    /**
     * @throws Exception
     */
    public function getGridAttributes(int $id, string $table): HtmlAttributes
    {
        $attributes = new HtmlAttributes();
        $styles = $this->getKissStyles($id, $table);

        $hasGridRatio = !empty($styles['gridRatioActive']) && !empty($styles['gridRatio']);

        if (empty($styles['gridColumns']) && !$hasGridRatio) {
            return $attributes;
        }

        return $attributes
            ->addClass('kiss_grid')
            ->addClass($this->getBackendClass($styles, 'columns'), !$hasGridRatio)
            ->addClass('kiss_grid-ratio', $hasGridRatio)
            ->addStyle('--grid-cols: ' . $styles['gridRatio'], $hasGridRatio)
        ;
    }

    /**
     * @throws Exception
     */
    public function getGridLabel(int $id, string $table): string|null
    {
        $styles = $this->getKissStyles($id, $table);

        if (empty($styles['gridColumns'])) {
            return null;
        }

        return $this->gridColumnLabels[$styles['gridColumns']] ?? null;
    }

    /**
     * @throws Exception
     */
    private function getKissStyles(int $id, string $table): array
    {
        return $this->cache[$table][$id] ??= $this->loadKissStyles($id, $table);
    }

    /**
     * @throws Exception
     */
    private function loadKissStyles(int $id, string $table): array
    {
        $schemaManager = $this->connection->createSchemaManager();

        $columns = array_keys($schemaManager->listTableColumns($table));

        if (!\in_array('kiss_styles', $columns, true)) {
            return [];
        }

        $data = $this->connection->fetchAssociative('SELECT kiss_styles, jsonData FROM ' . $table .  ' WHERE id = :id', ['id' => $id]);

        if (false === $data || null === $data['kiss_styles']) {
            return [];
        }

        $styles = json_decode($data['kiss_styles'], true) ?? [];
        $jsonData = json_decode($data['jsonData'] ?? '', true) ?? [];

        $gridRatioData = [
            'gridRatio' => $jsonData['gridRatio'] ?? null,
            'gridRatioActive' => $jsonData['gridRatioActive'] ?? null,
        ];

        return [...$styles, ...$gridRatioData];
    }

    private function getBackendClass(array $styles, string $type): string|null
    {
        $prefix = 'kiss_';

        return match ($type) {
            'columns' => $prefix . $this->stylesVariable->getColumn($styles['gridColumns'] ?? ''),
            'gap' => $prefix . $this->stylesVariable->getGap($styles['gridGap'] ?? ''),
        };
    }
}
