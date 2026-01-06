<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\EventListener;

use Contao\CoreBundle\DependencyInjection\Attribute\AsInsertTag;
use Contao\CoreBundle\InsertTag\Exception\InvalidInsertTagException;
use Contao\CoreBundle\InsertTag\InsertTagResult;
use Contao\CoreBundle\InsertTag\OutputType;
use Contao\CoreBundle\InsertTag\ResolvedInsertTag;
use Contao\CoreBundle\InsertTag\Resolver\InsertTagResolverNestedResolvedInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;

#[AsInsertTag('kiss_company')]
readonly class KissCompanyInsertTag implements InsertTagResolverNestedResolvedInterface
{
    public function __construct(
        private RouterInterface $router,
        private RequestStack $requestStack,
    ) {
    }

    public function __invoke(ResolvedInsertTag $insertTag): InsertTagResult
    {
        if (null === $insertTag->getParameters()->get(0)) {
            throw new InvalidInsertTagException('Missing parameters for insert tag.');
        }

        $parameter = $insertTag->getParameters()->get(0);

        $result = $this->replaceCompanyInsertTags($parameter);

        return new InsertTagResult($result, OutputType::html);
    }

    private function replaceCompanyInsertTags(string $parameter): string
    {
        return $parameter;
    }
}
