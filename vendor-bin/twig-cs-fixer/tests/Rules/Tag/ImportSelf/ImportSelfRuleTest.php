<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Tools\TwigCsFixer\Tests\Rules\Tag\ImportSelf;

use DigitaleDinge\ContaoKiss\Tools\TwigCsFixer\Rules\Tag\ImportSelfRule;
use TwigCsFixer\Test\AbstractRuleTestCase;

final class ImportSelfRuleTest extends AbstractRuleTestCase
{
    public function testRule(): void
    {
        $this->checkRule(new ImportSelfRule(), [
            'ImportSelf.Warning:1:4' => 'Macros of the current template are auto-imported. Remove "{% import _self … %}" and call "_self.macro()" directly.',
            'ImportSelf.Warning:3:4' => 'Macros of the current template are auto-imported. Remove "{% from _self … %}" and call "_self.macro()" directly.',
        ]);
    }
}
