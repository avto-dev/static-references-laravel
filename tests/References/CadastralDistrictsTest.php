<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\Tests\References;

use Mockery as m;
use Illuminate\Support\Str;
use AvtoDev\StaticReferences\References\CadastralDistricts;
use AvtoDev\StaticReferences\Tests\AbstractUnitTestCase;
use AvtoDev\StaticReferences\References\ReferenceInterface;
use AvtoDev\StaticReferences\References\Entities\CadastralDistrict;
use AvtoDev\StaticReferencesData\ReferencesData\StaticReferenceInterface;

/**
 * @covers \AvtoDev\StaticReferences\References\CadastralDistricts
 */
class CadastralDistrictsTest extends AbstractUnitTestCase
{
    /**
     * @var CadastralDistricts
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
                ['code' => 1, 'name' => 'foo name', 'areas' => [
                    ['code' => 1, 'name' => 'foo area 1'],
                    ['code' => 2, 'name' => 'foo area 2'],
                ]],
                ['code' => 2, 'name' => 'bar name', 'areas' => [
                    ['code' => 2, 'name' => 'bar area 2'],
                    ['code' => 3, 'name' => 'bar area 3'],
                ]],
            ])
            ->once()
            ->getMock();

        $this->reference = new CadastralDistricts($static_reference);
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
            $this->assertInstanceOf(CadastralDistrict::class, $item);
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
        $this->assertSame('foo name', $this->reference->getByCode(1)->getName());
        $this->assertSame('bar name', $this->reference->getByCode(2)->getName());

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
