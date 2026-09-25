<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\EventListener;

use Contao\ContentModel;
use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Contao\Model;
use Symfony\Contracts\Service\ResetInterface;

final class IncludeStylesDataListener implements ResetInterface
{
    private const array INCLUDE_TYPES = ['article', 'form', 'module'];

    /**
     * @var list<array{id: int, data: array<string, mixed>}>
     */
    private array $stack = [];

    #[AsHook('isVisibleElement')]
    public function push(Model $element, bool $isVisible): bool
    {
        if ($isVisible && $this->isInclude($element)) {
            $this->stack[] = [
                'id' => $element->id,
                'data' => $element->row(),
            ];
        }

        return $isVisible;
    }

    #[AsHook('getContentElement')]
    public function pop(ContentModel $model, string $buffer): string
    {
        if ($this->isInclude($model) && (int) $model->id === (end($this->stack)['id'] ?? null)) {
            array_pop($this->stack);
        }

        return $buffer;
    }

    /**
     * @return array<string, mixed>
     */
    public function getCurrentData(): array
    {
        return end($this->stack)['data'] ?? [];
    }

    public function reset(): void
    {
        $this->stack = [];
    }

    private function isInclude(Model $element): bool
    {
        return $element instanceof ContentModel && \in_array($element->type, self::INCLUDE_TYPES, true);
    }
}
