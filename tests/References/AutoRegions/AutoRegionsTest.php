<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\Tests\References\AutoRegions;

use Mockery as m;
use InvalidArgumentException;
use Illuminate\Support\Collection;
use AvtoDev\StaticReferences\Tests\AbstractUnitTestCase;
use AvtoDev\StaticReferences\References\ReferenceInterface;
use AvtoDev\StaticReferences\References\AutoRegions\AutoRegions;
use AvtoDev\StaticReferencesData\ReferencesData\StaticReference;
use AvtoDev\StaticReferences\References\AutoRegions\AutoRegionEntry;

/**
 * @covers \AvtoDev\StaticReferences\References\AutoRegions\AutoRegions<extended>
 *
 * @group  foo
 */
class AutoRegionsTest extends AbstractUnitTestCase
{
    /**
     * @var AutoRegions
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
                [
                    'title'          => 'foo title',
                    'short'          => ['fo'],
                    'code'           => 1,
                    'gibdd'          => [10],
                    'okato'          => 'okato-1',
                    'code_iso_31662' => 'foo-1',
                    'type'           => 'foo type',
                ],
                [
                    'title'          => 'bar title',
                    'short'          => ['br'],
                    'code'           => 2,
                    'gibdd'          => [20],
                    'okato'          => 'okato-2',
                    'code_iso_31662' => 'bar-1',
                    'type'           => 'bar type',
                ],
            ])
            ->once()
            ->getMock();

        $this->reference = new AutoRegions($static_reference);
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

        $this->assertInstanceOf(AutoRegionEntry::class, $all[0]);
        $this->assertSame('foo title', $all[0]->getTitle());

        $this->assertInstanceOf(AutoRegionEntry::class, $all[1]);
        $this->assertSame('bar title', $all[1]->getTitle());
    }

    /**
     * @return void
     */
    public function testFilterGetOne(): void
    {
        /** @var Collection|AutoRegionEntry[] $result */
        $result = (new Collection($this->reference))->filter(static function (AutoRegionEntry $e): bool {
            return $e->getTitle() === 'foo title';
        })->values();

        $this->assertCount(1, $result);
        $this->assertSame('foo title', $result[0]->getTitle());
    }

    /**
     * @return void
     */
    public function testFilterGetNone(): void
    {
        $result = (new Collection($this->reference))->filter(static function (AutoRegionEntry $e) {
            return false;
        })->values();

        $this->assertEmpty($result);
    }

    /**
     * @return void
     */
    public function testFilterGetAll(): void
    {
        /** @var Collection|AutoRegionEntry[] $result */
        $result = (new Collection($this->reference))->filter(static function (AutoRegionEntry $e): bool {
            return true;
        })->values();

        $this->assertCount(2, $result);
        $this->assertSame('foo title', $result[0]->getTitle());
        $this->assertSame('bar title', $result[1]->getTitle());
    }

    /**
     * @return void
     */
    public function testGetByAutoCode(): void
    {
        $this->assertSame('foo title', $this->reference->getByAutoCode(10)->getTitle());
        $this->assertSame('bar title', $this->reference->getByAutoCode(20)->getTitle());

        $this->assertNull($this->reference->getByAutoCode(\random_int(21, 100)));
    }

    /**
     * @return void
     */
    public function testGetByRegionCode(): void
    {
        $this->assertSame('foo title', $this->reference->getByRegionCode(1)->getTitle());
        $this->assertSame('bar title', $this->reference->getByRegionCode(2)->getTitle());

        $this->assertNull($this->reference->getByRegionCode(\random_int(3, 100)));
    }

    /**
     * @return void
     */
    public function testSameObjectOnDifferentGetters(): void
    {
        $first  = $this->reference->getByRegionCode(1);
        $second = $this->reference->getByAutoCode(10);

        $this->assertSame($first, $second);
    }

    /**
     * @return void
     */
    public function testCount(): void
    {
        $this->assertSame(2, $this->reference->count());
    }
}
