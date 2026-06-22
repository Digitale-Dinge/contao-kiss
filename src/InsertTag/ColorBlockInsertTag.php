<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\InsertTag;

use Contao\CoreBundle\DependencyInjection\Attribute\AsBlockInsertTag;
use Contao\CoreBundle\InsertTag\InsertTagResult;
use Contao\CoreBundle\InsertTag\OutputType;
use Contao\CoreBundle\InsertTag\ParsedSequence;
use Contao\CoreBundle\InsertTag\ResolvedInsertTag;
use Contao\CoreBundle\InsertTag\Resolver\BlockInsertTagResolverNestedResolvedInterface;
use Twig\Environment;

#[AsBlockInsertTag('color', endTag: 'endcolor')]
final class ColorBlockInsertTag implements BlockInsertTagResolverNestedResolvedInterface
{
    public function __construct(private readonly Environment $twig)
    {
    }

    public function __invoke(ResolvedInsertTag $insertTag, ParsedSequence $wrappedContent): ParsedSequence
    {
        $value = $insertTag->getParameters()->get(0);

        if (null === $value) {
            return $wrappedContent;
        }

        $prefix = $insertTag->getParameters()->get(1);
        $prefix = $prefix ? $prefix . '-' : null;

        $html = $this->twig->render('@Contao/kiss_component/_color_insert_tag.html.twig', [
            'value' => $value,
            'prefix' => $prefix,
            'content' => $wrappedContent,
        ]);

        // Return the HTML as an explicit "html" result. The headline renders via
        return new ParsedSequence([new InsertTagResult($html, OutputType::html)]);
    }
}
