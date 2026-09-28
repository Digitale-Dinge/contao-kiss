<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Twig\Runtime;

use Contao\CoreBundle\Filesystem\Dbafs\UnableToResolveUuidException;
use Contao\CoreBundle\Filesystem\FilesystemItem;
use Contao\CoreBundle\Filesystem\VirtualFilesystemInterface;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\FilesModel;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\Uid\Uuid;
use Twig\Extension\RuntimeExtensionInterface;

final readonly class FilesRuntime implements RuntimeExtensionInterface
{
    private const string ICON_DIRECTORY = 'public/kiss_icons/svg';

    private const array FILE_ICONS = [
        'pdf' => 'file-pdf',
        'doc' => 'file-doc',
        'docx' => 'file-doc',
        'odt' => 'file-doc',
        'rtf' => 'file-doc',
        'txt' => 'file-doc',
        'xls' => 'file-xls',
        'xlsx' => 'file-xls',
        'csv' => 'file-xls',
        'ods' => 'file-xls',
        'ppt' => 'file-ppt',
        'pptx' => 'file-ppt',
        'odp' => 'file-ppt',
        'zip' => 'file-zip',
        'rar' => 'file-zip',
        '7z' => 'file-zip',
        'tar' => 'file-zip',
        'gz' => 'file-zip',
        'jpg' => 'file-jpg',
        'jpeg' => 'file-jpg',
        'webp' => 'file-jpg',
        'svg' => 'file-jpg',
        'png' => 'file-png',
        'gif' => 'file-gif',
        'mp3' => 'file-mp3',
        'wav' => 'file-mp3',
        'm4a' => 'file-mp3',
        'aac' => 'file-mp3',
        'ogg' => 'file-mp3',
        'flac' => 'file-mp3',
        'mp4' => 'file-mp4',
        'mov' => 'file-mp4',
        'avi' => 'file-mp4',
        'mkv' => 'file-mp4',
        'webm' => 'file-mp4',
        'exe' => 'file-exe',
    ];

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
    public function getFileIcon(string $extension): string
    {
        $icon = self::ICON_DIRECTORY.'/'.(self::FILE_ICONS[strtolower($extension)] ?? 'file').'.svg';

        return is_file(Path::join($this->projectDir, $icon)) ? $icon : self::ICON_DIRECTORY.'/file.svg';
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
