<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Tools\TwigCsFixer\Tests\Rules\Tag\SetUnderscore;

use DigitaleDinge\ContaoKiss\Tools\TwigCsFixer\Rules\Tag\SetUnderscoreRule;
use TwigCsFixer\Test\AbstractRuleTestCase;

final class SetUnderscoreRuleTest extends AbstractRuleTestCase
{
    public function testRule(): void
    {
        $this->checkRule(new SetUnderscoreRule(), [
            'SetUnderscore.Error:1:4' => 'Use "{% do … %}" instead of "{% set _ = … %}".',
            'SetUnderscore.Error:2:5' => 'Use "{% do … %}" instead of "{% set _ = … %}".',
        ]);
    }
}
