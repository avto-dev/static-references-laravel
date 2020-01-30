<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\Tests\References\Entities;

use Illuminate\Support\Str;
use Tarampampam\Wrappers\Json;
use AvtoDev\StaticReferences\Tests\AbstractUnitTestCase;
use AvtoDev\StaticReferences\References\Entities\CadastralArea;
use AvtoDev\StaticReferences\References\Entities\EntityInterface;
use AvtoDev\StaticReferences\References\Entities\CadastralDistrict;

/**
 * @covers \AvtoDev\StaticReferences\References\Entities\CadastralDistrict
 */
class CadastralDistrictTest extends AbstractUnitTestCase
{
    /**
     * @return void
     */
    public function testImplementation(): void
    {
        $this->assertInstanceOf(EntityInterface::class, new CadastralDistrict(\random_int(1, 100), Str::random(), []));
    }

    /**
     * @return void
     */
    public function testGetCode(): void
    {
        $this->assertSame($code = \random_int(1, 100), (new CadastralDistrict($code, Str::random(), []))->getCode());
    }

    /**
     * @return void
     */
    public function testGetName(): void
    {
        $this->assertSame($name = Str::random(), (new CadastralDistrict(\random_int(1, 100), $name, []))->getName());
    }

    /**
     * @return void
     */
    public function testGetAreas(): void
    {
        $this->assertSame($areas = [new CadastralArea(\random_int(1, 100), Str::random())], (new CadastralDistrict(
            \random_int(1, 100), Str::random(), $areas
        ))->getAreas());
    }

    /**
     * @return void
     */
    public function testHasAreaWithCode(): void
    {
        $district = new CadastralDistrict(\random_int(1, 100), Str::random(), [
            new CadastralArea($code_1 = \random_int(1, 50), Str::random()),
            new CadastralArea($code_2 = \random_int(51, 100), Str::random()),
        ]);

        $this->assertTrue($district->hasAreaWithCode($code_1));
        $this->assertTrue($district->hasAreaWithCode($code_2));
        $this->assertFalse($district->hasAreaWithCode(\random_int(101, 200)));
    }

    /**
     * @return void
     */
    public function testToArray(): void
    {
        $as_array = (new CadastralDistrict(
            $code = \random_int(1, 100),
            $name = Str::random(),
            $areas = [new CadastralArea($area_code = \random_int(1, 100), $area_name = Str::random())]
        ))->toArray();

        $this->assertSame($code, $as_array['code']);
        $this->assertSame($name, $as_array['name']);
        $this->assertSame($area_code, $as_array['areas'][0]['code']);
        $this->assertSame($area_name, $as_array['areas'][0]['name']);
    }

    /**
     * @return void
     */
    public function testToJson(): void
    {
        $as_json = (new CadastralDistrict(
            $code = \random_int(1, 100),
            $name = Str::random(),
            $areas = [new CadastralArea($area_code = \random_int(1, 100), $area_name = Str::random())]
        ))->toJson();

        $this->assertJsonStringEqualsJsonString(Json::encode([
            'code'  => $code,
            'name'  => $name,
            'areas' => [['code' => $area_code, 'name' => $area_name]],
        ]), $as_json);
    }
}
