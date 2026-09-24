<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Tools\TwigCsFixer\Rules\Function;

use TwigCsFixer\Rules\AbstractRule;
use TwigCsFixer\Rules\ConfigurableRuleInterface;
use TwigCsFixer\Token\Token;
use TwigCsFixer\Token\Tokens;

/**
 * Ensures that kiss components are not called with variables via "include", as
 * that cuts them off from the inheritance chain.
 *
 *   {{ include('@Contao/kiss_component/status/_badge.html.twig', {…}) }}
 *   ->  {% use '@Contao/kiss_component/status/_badge.html.twig' %} … {{ block('badge') }}
 */
final class ComponentIncludeRule extends AbstractRule implements ConfigurableRuleInterface
{
    private const string COMPONENT_PREFIX = '@Contao/kiss_component/';

    /**
     * @param list<string> $ignore
     */
    public function __construct(
        private readonly array $ignore = ['@Contao/kiss_component/media/_icon_include.html.twig'],
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

        if ($token->isMatching(Token::FUNCTION_NAME_TYPE, 'include')) {
            $templateIndex = $tokens->findNext(Token::INDENT_TOKENS, $tokenIndex + 1, exclude: true);
            if (false === $templateIndex || !$tokens->get($templateIndex)->isMatching(Token::PUNCTUATION_TYPE, '(')) {
                return;
            }

            $templateIndex = $tokens->findNext(Token::INDENT_TOKENS, $templateIndex + 1, exclude: true);
            $argument = [Token::PUNCTUATION_TYPE, ','];
        } elseif ($token->isMatching(Token::BLOCK_NAME_TYPE, 'include')) {
            $templateIndex = $tokens->findNext(Token::INDENT_TOKENS, $tokenIndex + 1, exclude: true);
            $argument = [Token::NAME_TYPE, 'with'];
        } else {
            return;
        }

        if (false === $templateIndex || !$tokens->get($templateIndex)->isMatching(Token::STRING_TYPE)) {
            return;
        }

        $template = substr($tokens->get($templateIndex)->getValue(), 1, -1);
        if (!str_starts_with($template, self::COMPONENT_PREFIX) || \in_array($template, $this->ignore, true)) {
            return;
        }

        $nextIndex = $tokens->findNext(Token::INDENT_TOKENS, $templateIndex + 1, exclude: true);
        if (false === $nextIndex || !$tokens->get($nextIndex)->isMatching(...$argument)) {
            return;
        }

        $this->addError(
            \sprintf('Do not pass variables into "%s" via include; use "{%% use %%}" and "{{ block() }}" instead.', $template),
            $token,
        );
    }
}
