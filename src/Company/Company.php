<?php

namespace DigitaleDinge\ContaoKiss\Company;

use Contao\PageModel;
use DigitaleDinge\ContaoKiss\Model\KissCompanyModel;
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\RequestStack;

class Company
{
    private array $cache = [];

    public function __construct(
        readonly private RequestStack $requestStack,
        readonly private Connection $connection,
    ) {
    }

    public function get(int|string|null $id = null): KissCompanyModel|null
    {
        if (null === $id) {
            return $this->getByPageModel();
        }

        return $this->cache[(int) $id] ??= KissCompanyModel::findById($id);
    }

    private function getByPageModel(): KissCompanyModel|null
    {
        $pageModel = $this->requestStack->getCurrentRequest()?->attributes->get('pageModel');

        if (!($pageModel instanceof PageModel)) {
            return null;
        }

        if (0 === ($kiss_company = $this->connection->fetchOne('SELECT kiss_company FROM tl_page WHERE id=?', [$pageModel->rootId]))) {
            return null;
        }

        return $this->cache[(int) $kiss_company] ??= KissCompanyModel::findById($kiss_company);
    }
}
