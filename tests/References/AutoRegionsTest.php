<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\Tests\References;

use Mockery as m;
use AvtoDev\StaticReferences\References\AutoRegions;
use AvtoDev\StaticReferences\Tests\AbstractUnitTestCase;
use AvtoDev\StaticReferences\References\ReferenceInterface;
use AvtoDev\StaticReferences\References\Entities\AutoRegion;
use AvtoDev\StaticReferencesData\ReferencesData\StaticReference;

/**
 * @covers \AvtoDev\StaticReferences\References\AutoRegions<extended>
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
            $this->assertInstanceOf(AutoRegion::class, $item);
            $array[] = $item;
        }

        $this->assertCount(2, $array);
    }

    /**
     * @return void
     */
    public function testToArray(): void
    {
        $as_array = $this->reference->toArray();

        $this->assertCount(2, $as_array);

        foreach ($as_array as $item) {
            $this->assertIsArray($item);
            $this->assertNotEmpty($item);
        }
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
    public function testHasAutoCode(): void
    {
        $this->assertTrue($this->reference->hasAutoCode(10));
        $this->assertTrue($this->reference->hasAutoCode(20));

        $this->assertFalse($this->reference->hasAutoCode(\random_int(21, 100)));
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
    public function testHasRegionCode(): void
    {
        $this->assertTrue($this->reference->hasRegionCode(1));
        $this->assertTrue($this->reference->hasRegionCode(2));

        $this->assertFalse($this->reference->hasRegionCode(\random_int(3, 100)));
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
