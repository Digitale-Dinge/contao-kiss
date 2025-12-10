<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\EventListener;

use Contao\CoreBundle\Routing\ScopeMatcher;
use Symfony\Component\Asset\Packages;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\RequestEvent;

#[AsEventListener]
readonly class BackendAssetsListener
{
    public function __construct(
        private ScopeMatcher $scopeMatcher,
        private Packages $package,
    ) {
    }

    public function __invoke(RequestEvent $event): void
    {
        if ($this->scopeMatcher->isBackendMainRequest($event)) {
            $GLOBALS['TL_CSS'][] = $this->package->getUrl('dist/backend/contao-kiss.css', 'digitale_dinge_contao_kiss');
            $GLOBALS['TL_JAVASCRIPT'][] = $this->package->getUrl('dist/backend/contao-kiss.js', 'digitale_dinge_contao_kiss');
        }
    }
}
