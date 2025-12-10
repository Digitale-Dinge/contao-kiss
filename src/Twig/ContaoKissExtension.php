<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Twig;

use Contao\FilesModel;
use DigitaleDinge\ContaoKiss\Twig\Options\ClassOptionsInterface;
use DigitaleDinge\ContaoKiss\Twig\Options\ContainerSizes;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class ContaoKissExtension extends AbstractExtension
{
   public function getFilters(): array
   {
	   return [
		   new TwigFilter('contao_find_file_by_uuid', [$this, 'findFileByUuid']),
		   new TwigFilter('contao_get_mime_type', [$this, 'getMimeType']),
	   ];
   }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('getContainerClass', [$this, 'getContainerClass']),
        ];
    }

    // ToDo: Use FileItem instead
    // e.g. Sources for videos / three different videos
    public function findFileByUuid($uuid)
    {
        $fileModel = FilesModel::findByUuid($uuid);
        return $fileModel ? $fileModel->path : null;
    }

    public function getMimeType($uuid)
    {
        $fileModel = FilesModel::findByUuid($uuid);
        if ($fileModel && $fileModel->path) {
            $mimeType = mime_content_type($fileModel->path);
            return $mimeType ?: null;
        }
        return null;
    }

    public function getContainerClass(string $size = 'base'): string|null
    {
        return $this->getClassOptionValue(ContainerSizes::class, $size);
    }

    private function getClassOptionValue(string $class, string $key): string|null
    {
        if (!is_a($class, ClassOptionsInterface::class, \true)) {
            return null;
        }

        try {
            $value = $class::{mb_strtoupper($key)}?->value;
        } catch (\Error) {
            $value = null;
        }

        return $value;
    }
}
