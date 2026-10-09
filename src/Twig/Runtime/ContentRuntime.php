<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Twig\Runtime;

use Contao\ContentModel;
use Contao\CoreBundle\Framework\ContaoFramework;
use Twig\Extension\RuntimeExtensionInterface;

/**
 * ADR: Keeping it simple stupid to allow update compatibility (see #40).
 *
 * Contao fragment controllers and their elements render inside a protected getResponse() so can't really decorate here
 * without a compiler pass copying setFragmentOptions() from the inner service, or overriding the controller completely.
 *
 * The ContentModel is already loaded by the FragmentCompositor so findById() returns it from the model registry without
 * another query anyway.
 */
final readonly class ContentRuntime implements RuntimeExtensionInterface
{
    public function __construct(private ContaoFramework $framework)
    {
    }

    public function getContentModel(ContentModel|int $model): ContentModel|null
    {
        if ($model instanceof ContentModel) {
            return $model;
        }

        return $this->framework->getAdapter(ContentModel::class)->findById($model);
    }
}
