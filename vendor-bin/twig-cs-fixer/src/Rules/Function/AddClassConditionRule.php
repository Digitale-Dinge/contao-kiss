<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Tools\TwigCsFixer\Rules\Function;

use TwigCsFixer\Rules\AbstractRule;
use TwigCsFixer\Token\Token;
use TwigCsFixer\Token\Tokens;

/**
 * Ensures that a prefixed class built from an optional value passes values as a condition
 * so empty values do not emit invalid prefixes like "card-".
 *
 * Before and after:
 *   .addClass('card-' ~ styles.size(data.elementSize|default))
 *  ->  .addClass('card-' ~ styles.size(data.elementSize|default), data.elementSize|default)
 */
final class AddClassConditionRule extends AbstractRule
{
    protected function process(int $tokenIndex, Tokens $tokens): void
    {
        $token = $tokens->get($tokenIndex);
        if (!$token->isMatching(Token::FUNCTION_NAME_TYPE, 'addClass')) {
            return;
        }

        $openIndex = $tokens->findNext(Token::INDENT_TOKENS, $tokenIndex + 1, exclude: true);
        if (false === $openIndex || !$tokens->get($openIndex)->isMatching(Token::PUNCTUATION_TYPE, '(')) {
            return;
        }

        $depth = 0;
        $concatenated = false;
        $optional = false;

        for ($i = $openIndex; $i < \count($tokens->toArray()); ++$i) {
            $current = $tokens->get($i);

            if ($current->isMatching(Token::PUNCTUATION_TYPE, ['(', '[', '{'])) {
                ++$depth;
                continue;
            }

            if ($current->isMatching(Token::PUNCTUATION_TYPE, [')', ']', '}'])) {
                if (0 === --$depth) {
                    break;
                }

                continue;
            }

            if (1 !== $depth) {
                continue;
            }

            if ($current->isMatching(Token::PUNCTUATION_TYPE, ',')) {
                return;
            }

            $concatenated = $concatenated || $current->isMatching(Token::OPERATOR_TYPE, '~');
            $optional = $optional
                || $current->isMatching(Token::NAME_TYPE, 'styles')
                || $current->isMatching(Token::FILTER_NAME_TYPE, 'default');
        }

        if ($concatenated && $optional) {
            $this->addError(
                'A prefixed class built from an optional value needs the value as a condition: ".addClass(\'prefix-\' ~ value, value)".',
                $token,
            );
        }
    }
}
