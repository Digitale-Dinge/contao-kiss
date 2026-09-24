<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Tools\TwigCsFixer\Rules\Node;

use TwigCsFixer\Rules\AbstractRule;
use TwigCsFixer\Rules\ConfigurableRuleInterface;
use TwigCsFixer\Token\Token;
use TwigCsFixer\Token\Tokens;

/**
 * Warns about the "raw" filter.
 */
final class ForbiddenRawFilterRule extends AbstractRule implements ConfigurableRuleInterface
{
    /**
     * @param list<string> $ignore
     */
    public function __construct(
        private readonly array $ignore = [],
    ) {
    }

    public function getConfiguration(): array
    {
        return [
            'ignore' => $this->ignore,
        ];
    }

    protected function process(int $tokenIndex, Tokens $tokens): void
    {
        $token = $tokens->get($tokenIndex);
        if (!$token->isMatching(Token::FILTER_NAME_TYPE, 'raw')) {
            return;
        }

        $file = str_replace('\\', '/', $token->getFilename());

        if (array_any($this->ignore, fn($template) => $file === $template || str_ends_with($file, '/' . $template))) {
            return;
        }

        $this->addWarning(
            'You should not use the |raw filter. Please reconsider your architecture as this can add security risks. If you are really sure that it is fine, add the template to the ignore list.',
            $token,
        );
    }
}
