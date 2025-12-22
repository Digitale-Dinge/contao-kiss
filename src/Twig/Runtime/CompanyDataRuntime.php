<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Twig\Runtime;

use Contao\CoreBundle\Framework\ContaoFramework;
use Twig\Extension\RuntimeExtensionInterface;

final class CompanyDataRuntime implements RuntimeExtensionInterface
{
    public function __construct(private readonly ContaoFramework $framework)
    {
    }

    public function getCompanyDetails(): string
    {
        return '';
    }
}
