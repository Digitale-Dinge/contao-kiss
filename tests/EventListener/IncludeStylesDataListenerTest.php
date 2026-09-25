<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Tests\EventListener;

use Contao\ArticleModel;
use Contao\ContentModel;
use Contao\Model;
use DigitaleDinge\ContaoKiss\EventListener\IncludeStylesDataListener;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class IncludeStylesDataListenerTest extends TestCase
{
    public function testIsEmptyOutsideOfAnInclude(): void
    {
        $this->assertSame([], new IncludeStylesDataListener()->getCurrentData());
    }

    /**
     * @throws \ReflectionException
     */
    public function testExposesTheRowOfAVisibleInclude(): void
    {
        $listener = new IncludeStylesDataListener();
        $row = ['id' => 1, 'type' => 'form', 'showAsCard' => '1'];

        $this->assertTrue($listener->push($this->createModel(ContentModel::class, $row), true));
        $this->assertSame($row, $listener->getCurrentData());
    }

    /**
     * @param class-string<Model> $class
     * @param array<string, mixed> $row
     * @throws \ReflectionException
     */
    #[DataProvider('provideIgnoredElements')]
    public function testIgnoresElementsThatAreNotVisibleIncludes(string $class, array $row, bool $isVisible): void
    {
        $listener = new IncludeStylesDataListener();

        $this->assertSame($isVisible, $listener->push($this->createModel($class, $row), $isVisible));
        $this->assertSame([], $listener->getCurrentData());
    }

    public static function provideIgnoredElements(): iterable
    {
        yield 'invisible include' => [ContentModel::class, ['id' => 1, 'type' => 'form'], false];
        yield 'element that is no include' => [ContentModel::class, ['id' => 1, 'type' => 'text'], true];
        yield 'model that is no content element' => [ArticleModel::class, ['id' => 1, 'type' => 'form'], true];
    }

    /**
     * @throws \ReflectionException
     */
    public function testPopsOnlyTheMatchingInclude(): void
    {
        $listener = new IncludeStylesDataListener();
        $listener->push($this->createModel(ContentModel::class, ['id' => 1, 'type' => 'form']), true);

        $listener->pop($this->createModel(ContentModel::class, ['id' => 2, 'type' => 'form']), '');
        $this->assertSame(1, $listener->getCurrentData()['id']);

        $listener->pop($this->createModel(ContentModel::class, ['id' => 1, 'type' => 'form']), '');
        $this->assertSame([], $listener->getCurrentData());
    }

    /**
     * @throws \ReflectionException
     */
    public function testRestoresTheOuterIncludeAfterANestedOne(): void
    {
        $listener = new IncludeStylesDataListener();
        $listener->push($this->createModel(ContentModel::class, ['id' => 1, 'type' => 'article']), true);
        $listener->push($this->createModel(ContentModel::class, ['id' => 2, 'type' => 'form']), true);

        $this->assertSame('form', $listener->getCurrentData()['type']);

        $listener->pop($this->createModel(ContentModel::class, ['id' => 2, 'type' => 'form']), '');

        $this->assertSame('article', $listener->getCurrentData()['type']);
    }

    /**
     * @throws \ReflectionException
     */
    public function testMatchesTheIdWhetherItIsStoredAsStringOrInteger(): void
    {
        $listener = new IncludeStylesDataListener();
        $listener->push($this->createModel(ContentModel::class, ['id' => '5', 'type' => 'form']), true);

        $listener->pop($this->createModel(ContentModel::class, ['id' => 5, 'type' => 'form']), '');

        $this->assertSame([], $listener->getCurrentData());
    }

    public function testPopReturnsTheBufferUnchanged(): void
    {
        $this->assertSame('<form></form>', new IncludeStylesDataListener()->pop($this->createModel(ContentModel::class, ['id' => 1, 'type' => 'form']), '<form></form>'));
    }

    /**
     * @throws \ReflectionException
     */
    public function testResetClearsTheStack(): void
    {
        $listener = new IncludeStylesDataListener();
        $listener->push($this->createModel(ContentModel::class, ['id' => 1, 'type' => 'form']), true);

        $listener->reset();

        $this->assertSame([], $listener->getCurrentData());
    }

    /**
     * @template T of Model
     *
     * @param class-string<T>      $class
     * @param array<string, mixed> $row
     *
     * @return T
     *
     * @throws \ReflectionException
     */
    private function createModel(string $class, array $row): Model
    {
        $model = new \ReflectionClass($class)->newInstanceWithoutConstructor();
        new \ReflectionProperty(Model::class, 'arrData')->setValue($model, $row);

        return $model;
    }
}
