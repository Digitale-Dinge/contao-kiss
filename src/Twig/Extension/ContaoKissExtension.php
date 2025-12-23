<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Twig\Extension;

use Contao\FilesModel;
use DigitaleDinge\ContaoKiss\Styles\ClassOptionsInterface;
use DigitaleDinge\ContaoKiss\Styles\Options\Layout\Container;
use DigitaleDinge\ContaoKiss\Twig\Global\KissVariable;
use DigitaleDinge\ContaoKiss\Twig\Global\StylesVariable;
use DigitaleDinge\ContaoKiss\Twig\Runtime\CompanyDataRuntime;
use DigitaleDinge\ContaoKiss\Twig\Runtime\FileItemRuntime;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFilter;
use Twig\TwigFunction;

class ContaoKissExtension extends AbstractExtension implements GlobalsInterface
{
    public function __construct(
        private readonly KissVariable $kissVariable,
        private readonly StylesVariable $stylesVariable,
    ) {
    }

   public function getFilters(): array
   {
	   return [
           new TwigFilter('file',
               [FileItemRuntime::class, 'getFile']
           ),
	   ];
   }

    public function getGlobals(): array
    {
        return [
            'kiss' => $this->kissVariable,
            'styles' => $this->stylesVariable,
        ];
    }

    /*public function getFunctions(): array
    {
        return [
            new TwigFunction(
                'kiss_company',
                [CompanyDataRuntime::class, 'getCompanyDetails']
            ),
        ];
    }*/
}
