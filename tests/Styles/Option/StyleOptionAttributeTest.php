<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Tests\Styles\Option;

use DigitaleDinge\ContaoKiss\DependencyInjection\Attribute\AsKissStyleOption;
use DigitaleDinge\ContaoKiss\Tests\Fixtures\Styles\StyleOptionRegistryFactory;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class StyleOptionAttributeTest extends TestCase
{
    /**
     * @throws \ReflectionException
     */
    #[DataProvider('provideOptionClasses')]
    public function testEveryOptionClassCarriesTheAttribute(string $class): void
    {
        $this->assertNotEmpty(new \ReflectionClass($class)->getAttributes(AsKissStyleOption::class));
    }

    public static function provideOptionClasses(): iterable
    {
        foreach (StyleOptionRegistryFactory::getKissOptionClasses() as $class) {
            yield $class => [$class];
        }
    }
}
