<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Tools\TwigCsFixer\Tests\Rules\Node\ForbiddenBlock;

use DigitaleDinge\ContaoKiss\Tools\TwigCsFixer\Rules\Node\ForbiddenBlockRule;
use TwigCsFixer\Test\AbstractRuleTestCase;

final class ForbiddenBlockRuleTest extends AbstractRuleTestCase
{
    public function testConfiguration(): void
    {
        ForbiddenBlockRuleTest::assertSame(
            ['blocks' => ['embed'], 'ignore' => ['_image.html.twig']],
            new ForbiddenBlockRule(['embed'], ['_image.html.twig'])->getConfiguration(),
        );
    }

    public function testRule(): void
    {
        $this->checkRule(new ForbiddenBlockRule(['embed']), [
            'ForbiddenBlock.Error:1' => 'Block "embed" is not allowed.',
        ]);
    }

    public function testIgnore(): void
    {
        $this->checkRule(new ForbiddenBlockRule(['embed'], ['ForbiddenBlockRuleTest.twig']), []);
        $this->checkRule(new ForbiddenBlockRule(['embed'], ['ForbiddenBlock/ForbiddenBlockRuleTest.twig']), []);
    }

    public function testIgnoreMatchesWholeFileName(): void
    {
        $this->checkRule(new ForbiddenBlockRule(['embed'], ['RuleTest.twig']), [
            'ForbiddenBlock.Error:1' => 'Block "embed" is not allowed.',
        ]);
    }
}
