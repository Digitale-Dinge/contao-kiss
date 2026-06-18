<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\InsertTag;

use Contao\CoreBundle\DependencyInjection\Attribute\AsBlockInsertTag;
use Contao\CoreBundle\InsertTag\ParsedSequence;
use Contao\CoreBundle\InsertTag\ResolvedInsertTag;
use Contao\CoreBundle\InsertTag\Resolver\BlockInsertTagResolverNestedResolvedInterface;
use Twig\Environment;

/**
 * Colour a span of text via a block insert tag.
 *
 * Usage in the back end (e.g. inside a headline):
 *     {{color::primary}}UNSERE MARKE.{{endcolor}}{{br}}KLAR DEFINIERT.
 *
 * Produces:
 *     <span class="text-primary">UNSERE MARKE.</span><br>KLAR DEFINIERT.
 *
 */
#[AsBlockInsertTag('color', endTag: 'endcolor')]
final class ColorBlockInsertTag implements BlockInsertTagResolverNestedResolvedInterface
{
    public function __construct(private readonly Environment $twig)
    {
    }

    public function __invoke(ResolvedInsertTag $insertTag, ParsedSequence $wrappedContent): ParsedSequence
    {
        $value = (string) $insertTag->getParameters()->get(0);

        // No colour given -> output the wrapped content unchanged.
        if ('' === $value) {
            return $wrappedContent;
        }

        $html = $this->twig->render('@Contao/component/_color_text.html.twig', [
            'value' => $value,
            'content' => $wrappedContent->serialize(),
        ]);

        return new ParsedSequence([$html]);
    }
}
