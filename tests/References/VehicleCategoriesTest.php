<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\Tests\References;

use Mockery as m;
use Illuminate\Support\Str;
use AvtoDev\StaticReferences\References\VehicleCategories;
use AvtoDev\StaticReferences\Tests\AbstractUnitTestCase;
use AvtoDev\StaticReferences\References\ReferenceInterface;
use AvtoDev\StaticReferences\References\Entities\VehicleCategory;
use AvtoDev\StaticReferencesData\ReferencesData\StaticReferenceInterface;

/**
 * @covers \AvtoDev\StaticReferences\References\VehicleCategories
 */
class VehicleCategoriesTest extends AbstractUnitTestCase
{
    /**
     * @var VehicleCategories
     */
    protected $reference;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        /** @var m\MockInterface|StaticReferenceInterface $static_reference */
        $static_reference = m::mock(StaticReferenceInterface::class)
            ->expects('getData')
            ->andReturn([
                ['code' => 'foo', 'description' => 'foo desc'],
                ['code' => 'bar', 'description' => 'bar desc'],
            ])
            ->once()
            ->getMock();

        $this->reference = new VehicleCategories($static_reference);
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
            $this->assertInstanceOf(VehicleCategory::class, $item);
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
}
