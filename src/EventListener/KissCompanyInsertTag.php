<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\EventListener;

use Contao\CoreBundle\DependencyInjection\Attribute\AsInsertTag;
use Contao\CoreBundle\InsertTag\Exception\InvalidInsertTagException;
use Contao\CoreBundle\InsertTag\InsertTagResult;
use Contao\CoreBundle\InsertTag\OutputType;
use Contao\CoreBundle\InsertTag\ResolvedInsertTag;
use Contao\CoreBundle\InsertTag\Resolver\InsertTagResolverNestedResolvedInterface;
use Contao\StringUtil;
use DigitaleDinge\ContaoKiss\Company\Company;

#[AsInsertTag('kiss_company')]
#[AsInsertTag('kiss_company_id')]
class KissCompanyInsertTag implements InsertTagResolverNestedResolvedInterface
{
    private int|null $companyId = null;

    public function __construct(private readonly Company $company)
    {}

    public function __invoke(ResolvedInsertTag $insertTag): InsertTagResult
    {
        if (null === $insertTag->getParameters()->get(0)) {
            throw new InvalidInsertTagException('Missing parameters for insert tag.');
        }

        $parameters = $insertTag->getParameters()->all();

        if ('kiss_company_id' === $insertTag->getName()) {
            $this->companyId = (int) array_shift($parameters);
        }

        $result = $this->replaceInsertTag($parameters);

        return new InsertTagResult($result, OutputType::html);
    }

    private function replaceInsertTag(array $parameters): string
    {
        $company = $this->company->get($this->companyId);

        if (null === $company) {
            return '';
        }

        $name = $parameters[0] ?? null;
        $position = (int) $parameters[1] ?? 1;
        $modifier = $parameters[2] ?? null;

        return match ($name) {
            'phone' => $this->getFromSerialized($company->phone_numbers, $position, $modifier),
            'mail' => $this->getFromSerialized($company->emails, $position, $modifier),

            default => $company->{$name} ?? '',
        };
    }

    private function getFromSerialized(string|null $string, int $position, string|null $modifier = null): string
    {
        $values = StringUtil::deserialize($string, true);

        $value = $values[$position - 1] ?? [];

        return (string) reset($value);
    }
}
