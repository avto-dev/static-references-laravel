<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\Tests\References;

use Mockery as m;
use Illuminate\Support\Str;
use AvtoDev\StaticReferences\References\RepairMethods;
use AvtoDev\StaticReferences\Tests\AbstractUnitTestCase;
use AvtoDev\StaticReferences\References\ReferenceInterface;
use AvtoDev\StaticReferences\References\Entities\RepairMethod;
use AvtoDev\StaticReferencesData\ReferencesData\StaticReference;

/**
 * @covers \AvtoDev\StaticReferences\References\RepairMethods<extended>
 */
class RepairMethodsTest extends AbstractUnitTestCase
{
    /**
     * @var RepairMethods
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
                ['codes' => ['A', 'B'], 'description' => 'foo desc'],
                ['codes' => ['C'], 'description' => 'bar desc'],
            ])
            ->once()
            ->getMock();

        $this->reference = new RepairMethods($static_reference);
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
            $this->assertInstanceOf(RepairMethod::class, $item);
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
    public function testCount(): void
    {
        $this->assertSame(2, $this->reference->count());
    }
}
