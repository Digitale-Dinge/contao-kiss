<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Tools\TwigCsFixer\Rules\Node;

use Twig\Environment;
use Twig\Node\Node;
use TwigCsFixer\Rules\ConfigurableRuleInterface;
use TwigCsFixer\Rules\Node\AbstractNodeRule;

/**
 * Ensures some blocks are not used, except in the ignored templates.
 */
final class ForbiddenBlockRule extends AbstractNodeRule implements ConfigurableRuleInterface
{
    /**
     * @param list<string> $blocks
     * @param list<string> $ignore
     */
    public function __construct(
        private readonly array $blocks,
        private readonly array $ignore = [],
    ) {
    }

    public function getConfiguration(): array
    {
        return [
            'blocks' => $this->blocks,
            'ignore' => $this->ignore,
        ];
    }

    public function enterNode(Node $node, Environment $env): Node
    {
        $blockName = $node->getNodeTag();
        if (null === $blockName || !\in_array($blockName, $this->blocks, true)) {
            return $node;
        }

        $file = str_replace('\\', '/', $node->getTemplateName() ?? '');

        if (array_any($this->ignore, fn($template) => $file === $template || str_ends_with($file, '/' . $template))) {
            return $node;
        }

        $this->addError(
            \sprintf('Block "%s" is not allowed.', $blockName),
            $node,
        );

        return $node;
    }
}
