<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Tools\TwigCsFixer\Tests\Rules\Function\AddClassCondition;

use DigitaleDinge\ContaoKiss\Tools\TwigCsFixer\Rules\Function\AddClassConditionRule;
use TwigCsFixer\Test\AbstractRuleTestCase;

final class AddClassConditionRuleTest extends AbstractRuleTestCase
{
    public function testRule(): void
    {
        $this->checkRule(new AddClassConditionRule(), [
            'AddClassCondition.Error:2:6' => 'A prefixed class built from an optional value needs that value as condition: ".addClass(\'prefix-\' ~ value, value)".',
            'AddClassCondition.Error:3:6' => 'A prefixed class built from an optional value needs that value as condition: ".addClass(\'prefix-\' ~ value, value)".',
        ]);
    }
}
