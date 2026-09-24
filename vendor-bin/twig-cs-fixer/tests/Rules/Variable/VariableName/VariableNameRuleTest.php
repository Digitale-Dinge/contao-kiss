<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Tools\TwigCsFixer\Tests\Rules\Variable\VariableName;

use DigitaleDinge\ContaoKiss\Tools\TwigCsFixer\Rules\Variable\VariableNameRule;
use TwigCsFixer\Test\AbstractRuleTestCase;

final class VariableNameRuleTest extends AbstractRuleTestCase
{
    public function testConfiguration(): void
    {
        VariableNameRuleTest::assertSame(
            ['case' => VariableNameRule::SNAKE_CASE, 'optionalPrefix' => '_', 'ignore' => ['wrapperAttributes']],
            new VariableNameRule(optionalPrefix: '_', ignore: ['wrapperAttributes'])->getConfiguration(),
        );
    }

    public function testRule(): void
    {
        $this->checkRule(new VariableNameRule(), [
            'VariableName.Error:2:8' => 'The var name must use snake_case; expected camel_case.',
            'VariableName.Error:3:8' => 'The var name must use snake_case; expected prefixed.',
            'VariableName.Error:4:8' => 'The var name must use snake_case; expected wrapper_attributes.',
            'VariableName.Error:5:8' => 'The var name must use snake_case; expected key_name.',
            'VariableName.Error:5:17' => 'The var name must use snake_case; expected value_name.',
        ]);
    }

    public function testPrefixAndIgnore(): void
    {
        $this->checkRule(new VariableNameRule(optionalPrefix: '_', ignore: ['wrapperAttributes']), [
            'VariableName.Error:2:8' => 'The var name must use snake_case; expected camel_case.',
            'VariableName.Error:5:8' => 'The var name must use snake_case; expected key_name.',
            'VariableName.Error:5:17' => 'The var name must use snake_case; expected value_name.',
        ]);
    }
}
