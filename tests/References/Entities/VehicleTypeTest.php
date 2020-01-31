<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\Tests\References\Entities;

use Illuminate\Support\Str;
use AvtoDev\StaticReferences\Tests\AbstractUnitTestCase;
use AvtoDev\StaticReferences\References\Entities\VehicleType;
use AvtoDev\StaticReferences\References\Entities\EntityInterface;

/**
 * @covers \AvtoDev\StaticReferences\References\Entities\VehicleType
 */
class VehicleTypeTest extends AbstractUnitTestCase
{
    /**
     * @return void
     */
    public function testImplementation(): void
    {
        $this->assertInstanceOf(EntityInterface::class, new VehicleType(
            \random_int(1, 100), Str::random(), Str::random(), Str::random()
        ));
    }

    /**
     * @return void
     */
    public function testGetCode(): void
    {
        $this->assertSame(
            $code = \random_int(1, 100),
            (new VehicleType($code, Str::random(), Str::random(), Str::random()))->getCode()
        );
    }

    /**
     * @return void
     */
    public function testGetTitle(): void
    {
        $this->assertSame(
            $title = Str::random(),
            (new VehicleType(\random_int(1, 100), $title, Str::random(), Str::random()))->getTitle()
        );
    }

    /**
     * @return void
     */
    public function testGetGroupTitle(): void
    {
        $this->assertSame(
            $group_title = Str::random(),
            (new VehicleType(\random_int(1, 100), Str::random(), $group_title, Str::random()))->getGroupTitle()
        );
    }

    /**
     * @return void
     */
    public function testGetGroupSlug(): void
    {
        $this->assertSame(
            $group_slug = Str::random(),
            (new VehicleType(\random_int(1, 100), Str::random(), Str::random(), $group_slug))->getGroupSlug()
        );
    }

    /**
     * @return void
     */
    public function testToArray(): void
    {
        $as_array = (new VehicleType(
            $code = \random_int(1, 100),
            $title = Str::random(),
            $group_title = Str::random(),
            $group_slug = Str::random()
        ))->toArray();

        $this->assertSame($code, $as_array['code']);
        $this->assertSame($title, $as_array['title']);
        $this->assertSame($group_title, $as_array['group_title']);
        $this->assertSame($group_slug, $as_array['group_slug']);
    }
}
