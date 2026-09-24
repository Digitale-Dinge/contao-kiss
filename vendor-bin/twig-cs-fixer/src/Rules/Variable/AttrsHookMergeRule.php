<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Tools\TwigCsFixer\Rules\Variable;

use TwigCsFixer\Rules\AbstractRule;
use TwigCsFixer\Token\Token;
use TwigCsFixer\Token\Tokens;

/**
 * Ensures that an attribute hook is enriched rather than replaced.
 *
 * Before and after:
 *   {% set foo_attributes = attrs().addClass('foo') %}
 *   ->  {% set foo_attributes = attrs(foo_attributes|default).addClass('foo') %}
 */
final class AttrsHookMergeRule extends AbstractRule
{
    protected function process(int $tokenIndex, Tokens $tokens): void
    {
        $token = $tokens->get($tokenIndex);
        if (!$token->isMatching(Token::BLOCK_NAME_TYPE, 'set')) {
            return;
        }

        $nameIndex = $tokens->findNext(Token::INDENT_TOKENS, $tokenIndex + 1, exclude: true);
        $equalIndex = false === $nameIndex ? false : $tokens->findNext(Token::INDENT_TOKENS, $nameIndex + 1, exclude: true);
        $valueIndex = false === $equalIndex ? false : $tokens->findNext(Token::INDENT_TOKENS, $equalIndex + 1, exclude: true);

        if (false === $valueIndex
            || !$tokens->get($equalIndex)->isMatching(Token::OPERATOR_TYPE, '=')
            || !$tokens->get($valueIndex)->isMatching(Token::FUNCTION_NAME_TYPE, 'attrs')
            || $this->isInLocalScope($tokenIndex, $tokens)
        ) {
            return;
        }

        $name = $tokens->get($nameIndex)->getValue();
        $endIndex = $tokens->findNext(Token::BLOCK_END_TYPE, $valueIndex);
        \assert(false !== $endIndex);

        for ($i = $valueIndex + 1; $i < $endIndex; ++$i) {
            if ($tokens->get($i)->isMatching(Token::NAME_TYPE, $name)
                && !$tokens->get($i - 1)->isMatching(Token::OPERATOR_TYPE, ['.', '?.'])
            ) {
                return;
            }
        }

        $this->addWarning(
            \sprintf('"%1$s" replaces the hook; use "attrs(%1$s|default)" or ".mergeWith(%1$s|default)".', $name),
            $tokens->get($nameIndex),
        );
    }

    private function isInLocalScope(int $tokenIndex, Tokens $tokens): bool
    {
        $depth = 0;
        for ($i = 0; $i < $tokenIndex; ++$i) {
            $token = $tokens->get($i);
            if ($token->isMatching(Token::BLOCK_NAME_TYPE, ['for', 'macro'])) {
                ++$depth;
            } elseif ($token->isMatching(Token::BLOCK_NAME_TYPE, ['endfor', 'endmacro'])) {
                --$depth;
            }
        }

        return $depth > 0;
    }
}
