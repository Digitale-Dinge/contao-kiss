<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Tools\TwigCsFixer\Tests\Rules\Variable\AttrsHookMerge;

use DigitaleDinge\ContaoKiss\Tools\TwigCsFixer\Rules\Variable\AttrsHookMergeRule;
use TwigCsFixer\Test\AbstractRuleTestCase;

final class AttrsHookMergeRuleTest extends AbstractRuleTestCase
{
    public function testRule(): void
    {
        $this->checkRule(new AttrsHookMergeRule(), [
            'AttrsHookMerge.Warning:1:8' => '"foo_attributes" replaces the hook; use "attrs(foo_attributes|default)" or ".mergeWith(foo_attributes|default)".',
            'AttrsHookMerge.Warning:4:8' => '"baz_attributes" replaces the hook; use "attrs(baz_attributes|default)" or ".mergeWith(baz_attributes|default)".',
            'AttrsHookMerge.Warning:8:8' => '"attributes" replaces the hook; use "attrs(attributes|default)" or ".mergeWith(attributes|default)".',
            'AttrsHookMerge.Warning:13:8' => '"after_macro_attributes" replaces the hook; use "attrs(after_macro_attributes|default)" or ".mergeWith(after_macro_attributes|default)".',
            'AttrsHookMerge.Warning:14:8' => '"qux_attributes" replaces the hook; use "attrs(qux_attributes|default)" or ".mergeWith(qux_attributes|default)".',
        ]);
    }
}