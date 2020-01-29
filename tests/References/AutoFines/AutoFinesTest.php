<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\Tests\References\AutoFines;

use Mockery as m;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Illuminate\Support\Collection;
use AvtoDev\StaticReferences\Tests\AbstractUnitTestCase;
use AvtoDev\StaticReferences\References\ReferenceInterface;
use AvtoDev\StaticReferences\References\AutoFines\AutoFines;
use AvtoDev\StaticReferences\References\AutoFines\AutoFineEntry;
use AvtoDev\StaticReferencesData\ReferencesData\StaticReference;

/**
 * @covers \AvtoDev\StaticReferences\References\AutoFines\AutoFines<extended>
 *
 * @group  foo
 */
class AutoFinesTest extends AbstractUnitTestCase
{
    /**
     * @var AutoFines
     */
    protected $reference;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        /** @var m\MockInterface|StaticReference $static_reference */
        $static_reference = m::mock(StaticReference::class)
            ->expects('getData')
            ->andReturn([
                ['article' => '1.2.3 Ч.3', 'description' => 'foo desc'],
                ['article' => '2.3', 'description' => 'bar desc'],
            ])
            ->once()
            ->getMock();

        $this->reference = new AutoFines($static_reference);
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

        new AutoFines($static_reference);
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

        $this->assertInstanceOf(AutoFineEntry::class, $all[0]);
        $this->assertSame('1.2.3 Ч.3', $all[0]->getArticle());

        $this->assertInstanceOf(AutoFineEntry::class, $all[1]);
        $this->assertSame('2.3', $all[1]->getArticle());
    }

    /**
     * @return void
     */
    public function testFilterGetOne(): void
    {
        /** @var Collection|AutoFineEntry[] $result */
        $result = (new Collection($this->reference))->filter(static function (AutoFineEntry $e): bool {
            return $e->getArticle() === '2.3';
        })->values();

        $this->assertCount(1, $result);
        $this->assertSame('2.3', $result[0]->getArticle());
    }

    /**
     * @return void
     */
    public function testFilterGetNone(): void
    {
        $result = (new Collection($this->reference))->filter(static function (AutoFineEntry $e) {
            return false;
        })->values();

        $this->assertEmpty($result);
    }

    /**
     * @return void
     */
    public function testFilterGetAll(): void
    {
        /** @var Collection|AutoFineEntry[] $result */
        $result = (new Collection($this->reference))->filter(static function (AutoFineEntry $e): bool {
            return true;
        })->values();

        $this->assertCount(2, $result);
        $this->assertSame('1.2.3 Ч.3', $result[0]->getArticle());
        $this->assertSame('2.3', $result[1]->getArticle());
    }

    /**
     * @return void
     */
    public function testGetByCode(): void
    {
        $this->assertSame($foo_desc = 'foo desc', $this->reference->getByArticle('1.2.3 Ч.3')->getDescription());
        $this->assertSame($foo_desc, $this->reference->getByArticle('1.2.3.3')->getDescription());
        $this->assertSame($foo_desc, $this->reference->getByArticle('1.2.3 часть 3')->getDescription());
        $this->assertSame($foo_desc, $this->reference->getByArticle('1.2.3 part 3')->getDescription());
        $this->assertSame($foo_desc, $this->reference->getByArticle('  1.2.3 part 3')->getDescription());
        $this->assertSame($foo_desc, $this->reference->getByArticle('1.2.3 part 3  ')->getDescription());
        $this->assertSame($foo_desc, $this->reference->getByArticle('1.2.3 3')->getDescription());

        $this->assertNull($this->reference->getByArticle(Str::random()));
    }

    /**
     * @return void
     */
    public function testHasCode(): void
    {
        $this->assertTrue($this->reference->hasArticle('1.2.3 Ч.3'));
        $this->assertTrue($this->reference->hasArticle('2.3'));

        $this->assertTrue($this->reference->hasArticle('1.2.3.3'));
        $this->assertTrue($this->reference->hasArticle('1.2.3 часть 3'));
        $this->assertTrue($this->reference->hasArticle('1.2.3 part 3'));
        $this->assertTrue($this->reference->hasArticle('  1.2.3 part 3'));
        $this->assertTrue($this->reference->hasArticle('1.2.3 part 3  '));
        $this->assertTrue($this->reference->hasArticle('1.2.3 3'));

        $this->assertFalse($this->reference->hasArticle(Str::random()));
    }

    /**
     * @return void
     */
    public function testCount(): void
    {
        $this->assertSame(2, $this->reference->count());
    }
}
