<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\Tests\References\Entities;

use Illuminate\Support\Str;
use Tarampampam\Wrappers\Json;
use AvtoDev\StaticReferences\Tests\AbstractUnitTestCase;
use AvtoDev\StaticReferences\References\Entities\AutoCategory;
use AvtoDev\StaticReferences\References\Entities\EntityInterface;

/**
 * @covers \AvtoDev\StaticReferences\References\AutoCategory<extended>
 */
class AutoCategoryTest extends AbstractUnitTestCase
{
    /**
     * @return void
     */
    public function testImplementation(): void
    {
        $this->assertInstanceOf(EntityInterface::class, new AutoCategory(Str::random(), Str::random()));
    }

    /**
     * @return void
     */
    public function testGetCode(): void
    {
        $this->assertSame($code = Str::random(), (new AutoCategory($code, Str::random()))->getCode());
    }

    /**
     * @return void
     */
    public function testGetDescription(): void
    {
        $this->assertSame($desc = Str::random(), (new AutoCategory(Str::random(), $desc))->getDescription());
    }

    /**
     * @return void
     */
    public function testToArray(): void
    {
        $as_array = (new AutoCategory($code = Str::random(), $description = Str::random()))->toArray();

        $this->assertSame($code, $as_array['code']);
        $this->assertSame($description, $as_array['description']);
    }

    /**
     * @return void
     */
    public function testToJson(): void
    {
        $as_json = (new AutoCategory($code = Str::random(), $description = Str::random()))->toJson();

        $this->assertJsonStringEqualsJsonString(Json::encode([
            'code'        => $code,
            'description' => $description,
        ]), $as_json);
    }
}
