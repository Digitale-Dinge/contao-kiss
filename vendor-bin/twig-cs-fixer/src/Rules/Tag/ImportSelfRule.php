<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Tools\TwigCsFixer\Rules\Tag;

use TwigCsFixer\Rules\AbstractRule;
use TwigCsFixer\Token\Token;
use TwigCsFixer\Token\Tokens;

/**
 * Macros of Twig templates are auto-imported as "_self" in Twig ^3.15,
 * importing them with a name is redundant now.
 *
 * Before and after:
 *   {% import _self as foo %}{{ foo.bar() }}  ->  {{ _self.bar() }}
 *   {% from _self import bar %}{{ bar() }}  ->  {{ _self.bar() }}
 */
final class ImportSelfRule extends AbstractRule
{
    protected function process(int $tokenIndex, Tokens $tokens): void
    {
        $token = $tokens->get($tokenIndex);

        if (!$token->isMatching(Token::BLOCK_NAME_TYPE, ['import', 'from'])) {
            return;
        }

        $sourceIndex = $tokens->findNext(Token::INDENT_TOKENS, $tokenIndex + 1, exclude: true);

        if (false === $sourceIndex || !$tokens->get($sourceIndex)->isMatching(Token::NAME_TYPE, '_self')) {
            return;
        }

        $this->addWarning(
            \sprintf(
                'Macros of the current template are auto-imported. Remove "{%% %s _self … %%}" and call "_self.macro()" directly.',
                $token->getValue(),
            ),
            $token,
        );
    }
}
