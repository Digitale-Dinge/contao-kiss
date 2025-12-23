<?php

namespace DigitaleDinge\ContaoKiss\Twig\Global;

use Contao\PageModel;
use DigitaleDinge\ContaoKiss\Model\KissCompanyModel;
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\RequestStack;

class KissVariable
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly Connection $connection,
    ) {
    }

    public function getCompany(): KissCompanyModel|null
    {
        $pageModel = $this->requestStack->getCurrentRequest()?->attributes->get('pageModel');

        if (!($pageModel instanceof PageModel)) {
            return null;
        }

        if (0 === ($kiss_company = $this->connection->fetchOne('SELECT kiss_company FROM tl_page WHERE id=?', [$pageModel->rootId]))) {
            return null;
        }

        return KissCompanyModel::findById($kiss_company);
    }
}
