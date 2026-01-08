<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Twig\Global;

use DigitaleDinge\ContaoKiss\Company\Company;
use DigitaleDinge\ContaoKiss\Model\KissCompanyModel;

class KissVariable
{
    public function __construct(
        private readonly Company $company,
    ) {
    }

    public function getCompany(int|null $id = null): KissCompanyModel|null
    {
        return $this->company->get($id);
    }
}
