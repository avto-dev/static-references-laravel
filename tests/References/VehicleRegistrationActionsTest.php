<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\Tests\References;

use Mockery as m;
use AvtoDev\StaticReferences\Tests\AbstractUnitTestCase;
use AvtoDev\StaticReferences\References\ReferenceInterface;
use AvtoDev\StaticReferences\References\VehicleRegistrationActions;
use AvtoDev\StaticReferencesData\ReferencesData\StaticReferenceInterface;
use AvtoDev\StaticReferences\References\Entities\VehicleRegistrationAction;

/**
 * @covers \AvtoDev\StaticReferences\References\VehicleRegistrationActions
 */
class VehicleRegistrationActionsTest extends AbstractUnitTestCase
{
    /**
     * @var VehicleRegistrationActions
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
                ['codes' => [1, 2], 'description' => 'foo desc'],
                ['codes' => [3, 4], 'description' => 'bar desc'],
            ])
            ->once()
            ->getMock();

        $this->reference = new VehicleRegistrationActions($static_reference);
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
            $this->assertInstanceOf(VehicleRegistrationAction::class, $item);
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
        $this->assertSame('foo desc', $this->reference->getByCode(1)->getDescription());
        $this->assertSame('foo desc', $this->reference->getByCode(2)->getDescription());
        $this->assertSame('bar desc', $this->reference->getByCode(3)->getDescription());
        $this->assertSame('bar desc', $this->reference->getByCode(4)->getDescription());

        $this->assertNull($this->reference->getByCode(\random_int(5, 100)));
    }

    /**
     * @return void
     */
    public function testHasCode(): void
    {
        $this->assertTrue($this->reference->hasCode(1));
        $this->assertTrue($this->reference->hasCode(2));
        $this->assertTrue($this->reference->hasCode(3));
        $this->assertTrue($this->reference->hasCode(4));

        $this->assertFalse($this->reference->hasCode(\random_int(5, 100)));
    }

    /**
     * @return void
     */
    public function testSameObjectOnDifferentGetters(): void
    {
        $first  = $this->reference->getByCode(1);
        $second = $this->reference->getByCode(2);

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
