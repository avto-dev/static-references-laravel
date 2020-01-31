<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\Tests\References\Entities;

use Illuminate\Support\Str;
use AvtoDev\StaticReferences\Tests\AbstractUnitTestCase;
use AvtoDev\StaticReferences\References\Entities\EntityInterface;
use AvtoDev\StaticReferences\References\Entities\VehicleRegistrationAction;

/**
 * @covers \AvtoDev\StaticReferences\References\Entities\VehicleRegistrationAction
 */
class VehicleRegistrationActionTest extends AbstractUnitTestCase
{
    /**
     * @return void
     */
    public function testImplementation(): void
    {
        $this->assertInstanceOf(EntityInterface::class, new VehicleRegistrationAction([\random_int(1, 100)], Str::random()));
    }

    /**
     * @return void
     */
    public function testGetCode(): void
    {
        $this->assertSame($codes = [\random_int(1, 100)], (new VehicleRegistrationAction($codes, Str::random()))->getCodes());
    }

    /**
     * @return void
     */
    public function testGetDescription(): void
    {
        $this->assertSame($desc = Str::random(), (new VehicleRegistrationAction([], $desc))->getDescription());
    }

    /**
     * @return void
     */
    public function testToArray(): void
    {
        $as_array = (new VehicleRegistrationAction($codes = [\random_int(1, 100)], $description = Str::random()))->toArray();

        $this->assertSame($codes, $as_array['codes']);
        $this->assertSame($description, $as_array['description']);
    }
}
