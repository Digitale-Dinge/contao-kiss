<?php

// src/Twig/AppExtension.php

namespace DigitaleDinge\ContaoKiss\Twig;

use Contao\FilesModel;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
   const CONTAINER_SIZES = [
       'base'       => "container-base",
       'small'      => "container-small",
       'narrow'     => "container-narrow",
       'full-pad'   => "container-full-pad",
       'full'       => "container-full"
   ];

   public function getFilters(): array
   {
	   return [
		   new TwigFilter('contao_find_file_by_uuid', [$this, 'findFileByUuid']), // Gib den Pfad einer UUID aus
		   new TwigFilter('contao_get_mime_type', [$this, 'getMimeType']), // Gib den Mime Type einer UUID aus
	   ];
   }

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

    public function getFunctions(): array
    {
        return [
            new TwigFunction('getContainerClass', [$this, 'getContainerClass']),
        ];
    }

    public function getContainerClass(String $size = 'base'): String
    {
        if ( array_key_exists($size,self::CONTAINER_SIZES) ) {
            return self::CONTAINER_SIZES[$size];
        }

        return '';
    }
}
