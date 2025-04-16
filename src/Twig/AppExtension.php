<?php

// src/Twig/AppExtension.php

namespace App\Twig;

use Contao\FilesModel;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
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
}
