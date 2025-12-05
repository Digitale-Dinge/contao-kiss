<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Twig\Options;

enum ContainerSizes: string
{
    case SMALL = 'container-small';
    case BASE = 'container-base';
    case NARROW = 'container-narrow';
    case FULL_PAD = 'container-full-pad';
    case FULL = 'container-full';

    public static function fromCase(string $case): self|null
    {
        $caseName = mb_strtoupper($case);

        return ContainerSizes::cases()[$caseName] ?? null;
    }
}
