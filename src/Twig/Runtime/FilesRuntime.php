<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Twig\Runtime;

use Contao\CoreBundle\Filesystem\Dbafs\UnableToResolveUuidException;
use Contao\CoreBundle\Filesystem\FilesystemItem;
use Contao\CoreBundle\Filesystem\VirtualFilesystemInterface;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\FilesModel;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Uid\Uuid;
use Twig\Extension\RuntimeExtensionInterface;

final readonly class FilesRuntime implements RuntimeExtensionInterface
{
    private const string ICON_DIRECTORY = 'public/kiss_icons/svg';

    public function __construct(
        private ContaoFramework $framework,
        private VirtualFilesystemInterface $filesStorage,
        #[Autowire('%kernel.project_dir%')]
        private string $projectDir,
    ) {
    }

    /**
     * svg_icon() throws on a missing file, so an icon the project does not provide falls back to file.svg.
     */
    public function getFileIcon(string $name): string
    {
        $icon = self::ICON_DIRECTORY.'/'.trim($name).'.svg';

        return is_file($this->projectDir.'/'.$icon) ? $icon : self::ICON_DIRECTORY.'/file.svg';
    }

    public function getFile(string $uuid): array|null
    {
        try {
            $uuidObject = Uuid::isValid($uuid) ? Uuid::fromString($uuid) : Uuid::fromBinary($uuid);

            if (!($item = $this->filesStorage->get($uuidObject)) instanceof FilesystemItem) {
                return null;
            }
        }
        catch (\InvalidArgumentException|UnableToResolveUuidException) {
            return null;
        }

        $filesModel = $this->framework->getAdapter(FilesModel::class)->findByUuid($uuid);

        if (null === $filesModel) {
            return null;
        }

        return [
            ...$filesModel->row(), ...[
                'item' => $item,
                'publicUri' => $this->filesStorage->generatePublicUri($uuid),
            ],
        ];
    }
}
