<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Tools\TwigCsFixer\Tests\Rules\Variable\VariableName\HtmlAttributesVariableName;

use DigitaleDinge\ContaoKiss\Tools\TwigCsFixer\Rules\Variable\HtmlAttributesVariableNameRule;
use TwigCsFixer\Test\AbstractRuleTestCase;

final class HtmlAttributesVariableNameRuleTest extends AbstractRuleTestCase
{
    public function testConfiguration(): void
    {
        HtmlAttributesVariableNameRuleTest::assertSame(
            ['ignore' => ['wrapperAttributes']],
            new HtmlAttributesVariableNameRule(['wrapperAttributes'])->getConfiguration(),
        );
    }

    public function testRule(): void
    {
        $this->checkRule(new HtmlAttributesVariableNameRule(), [
            'HtmlAttributesVariableName.Error:3:8' => 'The variable name storing the result of an "attrs()" function must have the "attributes" suffix, got "bar".',
            'HtmlAttributesVariableName.Error:4:8' => 'The variable name storing the result of an "attrs()" function must have the "attributes" suffix, got "bar_attrs".',
            'HtmlAttributesVariableName.Error:5:8' => 'The variable name storing the result of an "attrs()" function must have the "attributes" suffix, got "barattributes".',
            'HtmlAttributesVariableName.Error:6:8' => 'The variable name storing the result of an "attrs()" function must have the "attributes" suffix, got "wrapperAttributes".',
        ]);
    }

    public function testIgnore(): void
    {
        $this->checkRule(new HtmlAttributesVariableNameRule(['wrapperAttributes']), [
            'HtmlAttributesVariableName.Error:3:8' => 'The variable name storing the result of an "attrs()" function must have the "attributes" suffix, got "bar".',
            'HtmlAttributesVariableName.Error:4:8' => 'The variable name storing the result of an "attrs()" function must have the "attributes" suffix, got "bar_attrs".',
            'HtmlAttributesVariableName.Error:5:8' => 'The variable name storing the result of an "attrs()" function must have the "attributes" suffix, got "barattributes".',
        ]);
    }
}
