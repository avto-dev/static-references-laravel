<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\Tests\References\Entities;

use Illuminate\Support\Str;
use AvtoDev\StaticReferences\Tests\AbstractUnitTestCase;
use AvtoDev\StaticReferences\References\Entities\EntityInterface;
use AvtoDev\StaticReferences\References\Entities\VehicleRepairMethod;

/**
 * @covers \AvtoDev\StaticReferences\References\Entities\VehicleRepairMethod
 */
class VehicleRepairMethodTest extends AbstractUnitTestCase
{
    /**
     * @return void
     */
    public function testImplementation(): void
    {
        $this->assertInstanceOf(EntityInterface::class, new VehicleRepairMethod([\random_int(1, 100)], Str::random()));
    }

    /**
     * @return void
     */
    public function testGetCode(): void
    {
        $this->assertSame($codes = [\random_int(1, 100)], (new VehicleRepairMethod($codes, Str::random()))->getCodes());
    }

    /**
     * @return void
     */
    public function testGetDescription(): void
    {
        $this->assertSame($desc = Str::random(), (new VehicleRepairMethod([], $desc))->getDescription());
    }

    /**
     * @return void
     */
    public function testToArray(): void
    {
        $as_array = (new VehicleRepairMethod($codes = [\random_int(1, 100)], $description = Str::random()))->toArray();

        $this->assertSame($codes, $as_array['codes']);
        $this->assertSame($description, $as_array['description']);
    }
}
