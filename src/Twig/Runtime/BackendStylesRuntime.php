<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Twig\Runtime;

use Contao\CoreBundle\String\HtmlAttributes;
use DigitaleDinge\ContaoKiss\EventListener\TranslatableEnumTrait;
use DigitaleDinge\ContaoKiss\Styles\Option\Layout\Column;
use DigitaleDinge\ContaoKiss\Styles\StyleOptionRegistry;
use DigitaleDinge\ContaoKiss\Twig\Global\StylesVariable;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extension\RuntimeExtensionInterface;

final class BackendStylesRuntime implements RuntimeExtensionInterface
{
    use TranslatableEnumTrait;

    /**
     * @var array<string, int|string>
     */
    private array $gridColumnLabels;

    /**
     * @var array<string, array<string, array<int, array<mixed>>>>
     */
    private array $cache = [];

    public function __construct(
        private readonly Connection $connection,
        private readonly StylesVariable $stylesVariable,
        private readonly TranslatorInterface $translator,
        StyleOptionRegistry $registry,
    ) {
        $this->gridColumnLabels = $this->getTranslatedOptions($registry->getEnum(Column::class));
    }

    /**
     * @return list<string>
     *
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
            // $this->getBackendClass($styles, 'gap'),
        ];
    }

    /**
     * @throws Exception
     */
    public function getGridAttributes(int|null $id, string $table): HtmlAttributes
    {
        $attributes = new HtmlAttributes();

        if (null === $id) {
            return $attributes;
        }

        $styles = $this->getParentKissStyles($id, $table);

        $gridRatio = $styles['gridRatio'] ?? null;
        $hasGridRatio = !empty($styles['gridRatioActive']) && !empty($gridRatio) && \is_scalar($gridRatio);

        if (empty($styles['gridColumns']) && !$hasGridRatio) {
            return $attributes;
        }

        return $attributes
            ->addClass('kiss_grid')
            ->addClass($this->getBackendClass($styles, 'columns'), !$hasGridRatio)
            ->addClass('kiss_grid-ratio', $hasGridRatio)
            ->addStyle('--grid-cols: '.($hasGridRatio ? $gridRatio : ''), $hasGridRatio)
        ;
    }

    /**
     * @throws Exception
     */
    public function getGridLabel(int $id, string $table): string|null
    {
        $columns = $this->getKissStyles($id, $table)['gridColumns'] ?? null;

        if (empty($columns) || !\is_string($columns) || !isset($this->gridColumnLabels[$columns])) {
            return null;
        }

        return (string) $this->gridColumnLabels[$columns];
    }

    /**
     * @return array<mixed>
     *
     * @throws Exception
     */
    private function getKissStyles(int $id, string $table): array
    {
        return $this->cache[$table]['self'][$id] ??= $this->loadKissStyles($id, $table);
    }

    /**
     * @return array<mixed>
     *
     * @throws Exception
     */
    private function getParentKissStyles(int $id, string $table): array
    {
        return $this->cache[$table]['parent'][$id] ??= $this->loadKissStyles($id, $table, true);
    }

    /**
     * @return array<mixed>
     *
     * @throws Exception
     */
    private function loadKissStyles(int $id, string $table, bool $fromParent = false): array
    {
        if ('tl_content' !== $table) {
            return [];
        }

        $schemaManager = $this->connection->createSchemaManager();

        $columns = array_keys($schemaManager->listTableColumns($table));

        if (!\in_array('kiss_styles', $columns, true)) {
            return [];
        }

        if ($fromParent) {
            $statement = 'SELECT kiss_styles, jsonData FROM '.$table
                .' WHERE id = (SELECT pid FROM '.$table.' WHERE id = :id AND ptable = :table)';
            $parameters = [
                'id' => $id,
                'table' => $table,
            ];
        }
        else {
            $statement = 'SELECT kiss_styles, jsonData FROM '.$table.' WHERE id = :id';
            $parameters = [
                'id' => $id,
            ];
        }

        $data = $this->connection->fetchAssociative($statement, $parameters);

        if (false === $data || !\is_string($data['kiss_styles'])) {
            return [];
        }

        // json_decode() returns mixed, narrow to array
        $styles = json_decode($data['kiss_styles'], true);
        $styles = \is_array($styles) ? $styles : [];
        $jsonData = \is_string($data['jsonData'] ?? null) ? json_decode($data['jsonData'], true) : null;
        $jsonData = \is_array($jsonData) ? $jsonData : [];

        $gridRatioData = [
            'gridRatio' => $jsonData['gridRatio'] ?? null,
            'gridRatioActive' => $jsonData['gridRatioActive'] ?? null,
        ];

        return [...$styles, ...$gridRatioData];
    }

    /**
     * @param array<mixed>    $styles
     * @param 'columns'|'gap' $type
     */
    private function getBackendClass(array $styles, string $type): string
    {
        $prefix = 'kiss_';
        $key = $styles['columns' === $type ? 'gridColumns' : 'gridGap'] ?? '';
        $key = \is_string($key) ? $key : '';

        return match ($type) {
            'columns' => $prefix.$this->stylesVariable->getColumn($key),
            'gap' => $prefix.$this->stylesVariable->getGap($key),
        };
    }
}
