<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Asset\VersionStrategy;

use Symfony\Component\Asset\VersionStrategy\VersionStrategyInterface;
use Symfony\Reprise\Asset\EntrypointsLookupInterface;

final readonly class ViteVersionStrategy implements VersionStrategyInterface
{
    public function __construct(
        private EntrypointsLookupInterface $entrypointsLookup,
        private VersionStrategyInterface $manifestStrategy,
    ) {
    }

    public function getVersion(string $path): string
    {
        return $this->applyVersion($path);
    }

    public function applyVersion(string $path): string
    {
        return $this->entryPath($path) ?? $this->manifestPath($path);
    }

    private function entryPath(string $path): string|null
    {
        $extension = pathinfo($path, PATHINFO_EXTENSION);

        if (!\in_array($extension, ['js', 'css'], true)) {
            return null;
        }

        // Dev server registers stylesheet under full name, build attaches it to the entry it belongs to
        $entry = $path;

        if (!$this->entrypointsLookup->entryExists($entry)) {
            $entry = substr($path, 0, -\strlen($extension) - 1);

            if (!$this->entrypointsLookup->entryExists($entry)) {
                return null;
            }
        }

        $files = 'js' === $extension
            ? $this->entrypointsLookup->getJavaScriptFiles($entry)
            : $this->entrypointsLookup->getCssFiles($entry);

        return $files[0] ?? null;
    }

    /**
     * The manifest is absent until a theme is built, kiss sets a default package strategy for every project, including
     * those without a theme.
     */
    private function manifestPath(string $path): string
    {
        try {
            return $this->manifestStrategy->applyVersion($path);
        } catch (\RuntimeException) {
            return $path;
        }
    }
}
