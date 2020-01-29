<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\Tests\References\AutoCategories;

use Mockery as m;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Illuminate\Support\Collection;
use AvtoDev\StaticReferences\Tests\AbstractUnitTestCase;
use AvtoDev\StaticReferences\References\ReferenceInterface;
use AvtoDev\StaticReferencesData\ReferencesData\StaticReference;
use AvtoDev\StaticReferences\References\AutoCategories\AutoCategories;
use AvtoDev\StaticReferences\References\AutoCategories\AutoCategoryEntry;

/**
 * @covers \AvtoDev\StaticReferences\References\AutoCategories\AutoCategories<extended>
 *
 * @group  foo
 */
class AutoCategoriesTest extends AbstractUnitTestCase
{
    /**
     * @var AutoCategories
     */
    protected $reference;

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        /** @var m\MockInterface|StaticReference $static_reference */
        $static_reference = m::mock(StaticReference::class)
            ->expects('getData')
            ->andReturn([
                ['code' => 'foo', 'description' => 'foo desc'],
                ['code' => 'bar', 'description' => 'bar desc'],
            ])
            ->once()
            ->getMock();

        $this->reference = new AutoCategories($static_reference);
    }

    /**
     * @return void
     */
    public function testImplementation(): void
    {
        $this->assertInstanceOf(ReferenceInterface::class, $this->reference);
    }

    /**
     * @return void
     */
    public function testConstructorThrowsExceptionOnInvalidDataStructure(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageRegExp('~Wrong.+element~i');

        /** @var m\MockInterface|StaticReference $static_reference */
        $static_reference = m::mock(StaticReference::class)
            ->expects('getData')
            ->andReturn([
                ['foo' => 1, 'description' => 'bar'],
            ])
            ->once()
            ->getMock();

        new AutoCategories($static_reference);
    }

    /**
     * @return void
     */
    public function testIterator(): void
    {
        $array = [];

        foreach ($this->reference as $item) {
            $array[] = $item;
        }

        $this->assertCount(2, $array);
        $this->assertSame($this->reference->all(), $array);
    }

    /**
     * @return void
     */
    public function testAll(): void
    {
        $all = $this->reference->all();

        $this->assertCount(2, $all);

        $this->assertInstanceOf(AutoCategoryEntry::class, $all[0]);
        $this->assertSame('foo', $all[0]->getCode());

        $this->assertInstanceOf(AutoCategoryEntry::class, $all[1]);
        $this->assertSame('bar', $all[1]->getCode());
    }

    /**
     * @return void
     */
    public function testFilterGetOne(): void
    {
        /** @var Collection|AutoCategoryEntry[] $result */
        $result = (new Collection($this->reference))->filter(static function (AutoCategoryEntry $e): bool {
            return $e->getCode() === 'bar';
        })->values();

        $this->assertCount(1, $result);
        $this->assertSame('bar', $result[0]->getCode());
    }

    /**
     * @return void
     */
    public function testFilterGetNone(): void
    {
        $result = (new Collection($this->reference))->filter(static function (AutoCategoryEntry $e) {
            return false;
        })->values();

        $this->assertEmpty($result);
    }

    /**
     * @return void
     */
    public function testFilterGetAll(): void
    {
        /** @var Collection|AutoCategoryEntry[] $result */
        $result = (new Collection($this->reference))->filter(static function (AutoCategoryEntry $e): bool {
            return true;
        })->values();

        $this->assertCount(2, $result);
        $this->assertSame('foo', $result[0]->getCode());
        $this->assertSame('bar', $result[1]->getCode());
    }

    /**
     * @return void
     */
    public function testGetByCode(): void
    {
        $this->assertSame('foo desc', $this->reference->getByCode('foo')->getDescription());
        $this->assertNull($this->reference->getByCode(Str::random()));
    }

    /**
     * @return void
     */
    public function testHasCode(): void
    {
        $this->assertTrue($this->reference->hasCode('foo'));
        $this->assertTrue($this->reference->hasCode('bar'));

        $this->assertFalse($this->reference->hasCode(Str::random()));
    }

    /**
     * @return void
     */
    public function testCount(): void
    {
        $this->assertSame(2, $this->reference->count());
    }

//    /**
//     * @return void
//     */
//    public function testCollectionPluck(): void
//    {
//        $this->assertSame(['foo', 'bar'], (new Collection($this->reference))->pluck('code'));
//    }
}
