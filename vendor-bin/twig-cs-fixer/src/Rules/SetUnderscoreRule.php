<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Tools\TwigCsFixer\Rules;

use TwigCsFixer\Rules\AbstractFixableRule;
use TwigCsFixer\Token\Token;
use TwigCsFixer\Token\Tokens;

/**
 * Ensures that expressions evaluated only for their side effect use the "do"
 * tag instead of being assigned to a throwaway "_" variable.
 *
 * Before and after:
 *   {% set _ = attributes.set('foobar', foobar) %}  →  {% do attributes.set('foobar', foobar) %}
 */
final class SetUnderscoreRule extends AbstractFixableRule
{
    protected function process(int $tokenIndex, Tokens $tokens): void
    {
        $token = $tokens->get($tokenIndex);

        if (!$token->isMatching(Token::BLOCK_NAME_TYPE, 'set')) {
            return;
        }

        $nameIndex = $tokens->findNext(Token::INDENT_TOKENS, $tokenIndex + 1, exclude: true);

        if (false === $nameIndex || !$tokens->get($nameIndex)->isMatching(Token::NAME_TYPE, '_')) {
            return;
        }

        // Skip "{% set _, foo = … %}" and the capture form "{% set _ %}…{% endset %}".
        $equalIndex = $tokens->findNext(Token::INDENT_TOKENS, $nameIndex + 1, exclude: true);

        if (false === $equalIndex || !$tokens->get($equalIndex)->isMatching(Token::OPERATOR_TYPE, '=')) {
            return;
        }

        $fixer = $this->addFixableError('Use "{% do … %}" instead of "{% set _ = … %}".', $token);

        if (null === $fixer) {
            return;
        }

        $valueIndex = $tokens->findNext(Token::INDENT_TOKENS, $equalIndex + 1, exclude: true);
        \assert(false !== $valueIndex);

        $fixer->beginChangeSet();
        $fixer->replaceToken($tokenIndex, 'do');

        for ($i = $nameIndex; $i < $valueIndex; ++$i) {
            $fixer->replaceToken($i, '');
        }

        $fixer->endChangeSet();
    }
}
