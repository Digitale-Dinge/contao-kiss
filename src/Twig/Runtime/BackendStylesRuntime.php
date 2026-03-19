<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Twig\Runtime;

use DigitaleDinge\ContaoKiss\EventListener\TranslatableEnumTrait;
use DigitaleDinge\ContaoKiss\Styles\Option\Layout\Column;
use DigitaleDinge\ContaoKiss\Twig\Global\StylesVariable;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extension\RuntimeExtensionInterface;

final readonly class BackendStylesRuntime implements RuntimeExtensionInterface
{
    use TranslatableEnumTrait;

    private array $gridColumnLabels;

    public function __construct(
        private Connection $connection,
        private StylesVariable $stylesVariable,
        private TranslatorInterface $translator,
    ) {
        $this->gridColumnLabels = $this->getTranslatedOptions(Column::class);
    }

    /**
     * @throws Exception
     */
    public function getGridClasses(int $id): array
    {
        $styles = $this->getKissStyles($id);

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
    public function getGridLabel(int $id): string|null
    {
        $styles = $this->getKissStyles($id);

        if (empty($styles['gridColumns'])) {
            return null;
        }

        return $this->gridColumnLabels[$styles['gridColumns']] ?? null;
    }

    /**
     * @throws Exception
     */
    private function getKissStyles(int $id): array
    {
        $styles = $this->connection->fetchOne('SELECT kiss_styles FROM tl_content WHERE id = :id', ['id' => $id]);

        if (false === $styles) {
            return [];
        }

        return json_decode($styles, true);
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
