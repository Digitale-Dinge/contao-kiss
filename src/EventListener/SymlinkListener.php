<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\EventListener;

use Contao\CoreBundle\Event\GenerateSymlinksEvent;
use Symfony\Component\Asset\PathPackage;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;


#[AsEventListener('contao.generate_symlinks')]
class SymlinkListener
{

    private readonly string $bundleDir;


    public function __construct(
        private readonly string $projectDir,
        private readonly string $webDir,
        private readonly Filesystem $filesystem,
        PathPackage $pathPackage
    )
    {
        $this->bundleDir = $pathPackage->getBasePath();
    }

    public function __invoke(GenerateSymlinksEvent $event): void
    {
        $webDir = $this->filesystem->makePathRelative($this->webDir, $this->projectDir);
        $bundleDir = Path::join($webDir, $this->bundleDir);

        $event->addSymlink($bundleDir, 'files/contao-kiss');
    }

}
