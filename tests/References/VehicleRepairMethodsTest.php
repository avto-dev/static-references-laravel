<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\Tests\References;

use Mockery as m;
use Illuminate\Support\Str;
use AvtoDev\StaticReferences\Tests\AbstractUnitTestCase;
use AvtoDev\StaticReferences\References\ReferenceInterface;
use AvtoDev\StaticReferences\References\VehicleRepairMethods;
use AvtoDev\StaticReferences\References\Entities\VehicleRepairMethod;
use AvtoDev\StaticReferencesData\ReferencesData\StaticReferenceInterface;

/**
 * @covers \AvtoDev\StaticReferences\References\VehicleRepairMethods
 */
class VehicleRepairMethodsTest extends AbstractUnitTestCase
{
    /**
     * @var VehicleRepairMethods
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
                ['codes' => ['A', 'B'], 'description' => 'foo desc'],
                ['codes' => ['C'], 'description' => 'bar desc'],
            ])
            ->once()
            ->getMock();

        $this->reference = new VehicleRepairMethods($static_reference);
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
            $this->assertInstanceOf(VehicleRepairMethod::class, $item);
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
        $this->assertSame('foo desc', $this->reference->getByCode('A')->getDescription());
        $this->assertSame('foo desc', $this->reference->getByCode('B')->getDescription());
        $this->assertSame('bar desc', $this->reference->getByCode('C')->getDescription());

        $this->assertNull($this->reference->getByCode(Str::random()));
    }

    /**
     * @return void
     */
    public function testHasCode(): void
    {
        $this->assertTrue($this->reference->hasCode('A'));
        $this->assertTrue($this->reference->hasCode('B'));
        $this->assertTrue($this->reference->hasCode('C'));

        $this->assertFalse($this->reference->hasCode(Str::random()));
    }

    /**
     * @return void
     */
    public function testSameObjectOnDifferentGetters(): void
    {
        $first  = $this->reference->getByCode('A');
        $second = $this->reference->getByCode('B');

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
