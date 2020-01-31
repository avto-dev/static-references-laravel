<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\Tests\References;

use Mockery as m;
use AvtoDev\StaticReferences\References\VehicleTypes;
use AvtoDev\StaticReferences\Tests\AbstractUnitTestCase;
use AvtoDev\StaticReferences\References\ReferenceInterface;
use AvtoDev\StaticReferences\References\Entities\VehicleType;
use AvtoDev\StaticReferencesData\ReferencesData\StaticReferenceInterface;

/**
 * @covers \AvtoDev\StaticReferences\References\VehicleTypes
 */
class VehicleTypesTest extends AbstractUnitTestCase
{
    /**
     * @var VehicleTypes
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
                ['code' => 1, 'title' => 'foo title', 'group_title' => 'foo gr.title', 'group_slug' => 'foo gr.slug'],
                ['code' => 2, 'title' => 'bar title', 'group_title' => 'bar gr.title', 'group_slug' => 'bar gr.slug'],
            ])
            ->once()
            ->getMock();

        $this->reference = new VehicleTypes($static_reference);
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
            $this->assertInstanceOf(VehicleType::class, $item);
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
        $this->assertSame('foo title', $this->reference->getByCode(1)->getTitle());
        $this->assertSame('bar title', $this->reference->getByCode(2)->getTitle());

        $this->assertNull($this->reference->getByCode(\random_int(3, 100)));
    }

    /**
     * @return void
     */
    public function testHasCode(): void
    {
        $this->assertTrue($this->reference->hasCode(1));
        $this->assertTrue($this->reference->hasCode(2));

        $this->assertFalse($this->reference->hasCode(\random_int(3, 100)));
    }

    /**
     * @return void
     */
    public function testCount(): void
    {
        $this->assertSame(2, $this->reference->count());
    }
}
