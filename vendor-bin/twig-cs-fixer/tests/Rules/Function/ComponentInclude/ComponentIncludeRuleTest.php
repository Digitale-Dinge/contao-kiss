<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Tools\TwigCsFixer\Tests\Rules\Function\ComponentInclude;

use DigitaleDinge\ContaoKiss\Tools\TwigCsFixer\Rules\Function\ComponentIncludeRule;
use TwigCsFixer\Test\AbstractRuleTestCase;

final class ComponentIncludeRuleTest extends AbstractRuleTestCase
{
    public function testConfiguration(): void
    {
        ComponentIncludeRuleTest::assertSame(
            ['ignore' => ['@Contao/kiss_component/media/_icon_include.html.twig']],
            new ComponentIncludeRule()->getConfiguration(),
        );
    }

    public function testRule(): void
    {
        $this->checkRule(new ComponentIncludeRule(), [
            'ComponentInclude.Error:1:4' => 'Do not pass variables into "@Contao/kiss_component/status/_badge.html.twig" via include; use "{% use %}" and "{{ block() }}" instead.',
            'ComponentInclude.Error:2:4' => 'Do not pass variables into "@Contao/kiss_component/media/_icon_text.html.twig" via include; use "{% use %}" and "{{ block() }}" instead.',
            'ComponentInclude.Error:3:4' => 'Do not pass variables into "@Contao/kiss_component/status/_alert.html.twig" via include; use "{% use %}" and "{{ block() }}" instead.',
        ]);
    }

    public function testIgnore(): void
    {
        $this->checkRule(new ComponentIncludeRule([
            '@Contao/kiss_component/status/_badge.html.twig',
            '@Contao/kiss_component/media/_icon_text.html.twig',
            '@Contao/kiss_component/status/_alert.html.twig',
        ]), [
            'ComponentInclude.Error:4:4' => 'Do not pass variables into "@Contao/kiss_component/media/_icon_include.html.twig" via include; use "{% use %}" and "{{ block() }}" instead.',
        ]);
    }
}
