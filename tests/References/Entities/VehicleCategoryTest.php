<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\Tests\References\Entities;

use Illuminate\Support\Str;
use Tarampampam\Wrappers\Json;
use AvtoDev\StaticReferences\Tests\AbstractUnitTestCase;
use AvtoDev\StaticReferences\References\Entities\EntityInterface;
use AvtoDev\StaticReferences\References\Entities\VehicleCategory;

/**
 * @covers \AvtoDev\StaticReferences\References\Entities\VehicleCategory
 */
class VehicleCategoryTest extends AbstractUnitTestCase
{
    /**
     * @return void
     */
    public function testImplementation(): void
    {
        $this->assertInstanceOf(EntityInterface::class, new VehicleCategory(Str::random(), Str::random()));
    }

    /**
     * @return void
     */
    public function testGetCode(): void
    {
        $this->assertSame($code = Str::random(), (new VehicleCategory($code, Str::random()))->getCode());
    }

    /**
     * @return void
     */
    public function testGetDescription(): void
    {
        $this->assertSame($desc = Str::random(), (new VehicleCategory(Str::random(), $desc))->getDescription());
    }

    /**
     * @return void
     */
    public function testToArray(): void
    {
        $as_array = (new VehicleCategory($code = Str::random(), $description = Str::random()))->toArray();

        $this->assertSame($code, $as_array['code']);
        $this->assertSame($description, $as_array['description']);
    }
}
