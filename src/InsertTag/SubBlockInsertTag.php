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

#[AsBlockInsertTag('sub', endTag: 'endsub')]
final class SubBlockInsertTag implements BlockInsertTagResolverNestedResolvedInterface
{
    public function __construct(private readonly Environment $twig)
    {}

    public function __invoke(ResolvedInsertTag $insertTag, ParsedSequence $wrappedContent): ParsedSequence
    {
        $html = $this->twig->render('@Contao/kiss_component/_sub_insert_tag.html.twig', [
            'content' => $wrappedContent->serialize(),
        ]);

        return new ParsedSequence([new InsertTagResult($html, OutputType::html)]);
    }
}
