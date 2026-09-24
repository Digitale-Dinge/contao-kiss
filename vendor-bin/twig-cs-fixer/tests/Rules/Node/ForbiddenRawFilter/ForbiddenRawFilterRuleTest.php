<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Tools\TwigCsFixer\Tests\Rules\Node\ForbiddenRawFilter;

use DigitaleDinge\ContaoKiss\Tools\TwigCsFixer\Rules\Node\ForbiddenRawFilterRule;
use TwigCsFixer\Test\AbstractRuleTestCase;

final class ForbiddenRawFilterRuleTest extends AbstractRuleTestCase
{
    public function testConfiguration(): void
    {
        ForbiddenRawFilterRuleTest::assertSame(
            ['ignore' => ['mod_newslist.html.twig']],
            new ForbiddenRawFilterRule(['mod_newslist.html.twig'])->getConfiguration(),
        );
    }

    public function testRule(): void
    {
        $this->checkRule(new ForbiddenRawFilterRule(), [
            'ForbiddenRawFilter.Warning:1:12' => 'You should not use the |raw filter. Please reconsider your architecture as this can add security risks. If you are really sure that it is fine, add the template to the ignore list.',
            'ForbiddenRawFilter.Warning:3:15' => 'You should not use the |raw filter. Please reconsider your architecture as this can add security risks. If you are really sure that it is fine, add the template to the ignore list.',
        ]);
    }

    public function testIgnore(): void
    {
        $this->checkRule(new ForbiddenRawFilterRule(['ForbiddenRawFilterRuleTest.twig']), []);
        $this->checkRule(new ForbiddenRawFilterRule(['ForbiddenRawFilter/ForbiddenRawFilterRuleTest.twig']), []);
    }

    public function testIgnoreMatchesWholeFileName(): void
    {
        $this->checkRule(new ForbiddenRawFilterRule(['RuleTest.twig']), [
            'ForbiddenRawFilter.Warning:1:12' => 'You should not use the |raw filter. Please reconsider your architecture as this can add security risks. If you are really sure that it is fine, add the template to the ignore list.',
            'ForbiddenRawFilter.Warning:3:15' => 'You should not use the |raw filter. Please reconsider your architecture as this can add security risks. If you are really sure that it is fine, add the template to the ignore list.',
        ]);
    }
}
