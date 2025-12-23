<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Twig\Runtime;

use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\PageModel;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\RuntimeExtensionInterface;

final class CompanyDataRuntime implements RuntimeExtensionInterface
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly ContaoFramework $framework
    ) {
    }

    public function getCompanyDetails(string|null $type = null): string|null
    {
        return match ($type) {
            default => null,
        };
    }
}
