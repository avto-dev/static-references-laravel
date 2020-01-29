<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\Tests\References\Entities;

use Illuminate\Support\Str;
use Tarampampam\Wrappers\Json;
use AvtoDev\StaticReferences\Tests\AbstractUnitTestCase;
use AvtoDev\StaticReferences\References\Entities\EntityInterface;
use AvtoDev\StaticReferences\References\Entities\RegistrationAction;

/**
 * @covers \AvtoDev\StaticReferences\References\Entities\RegistrationAction<extended>
 */
class RegistrationActionTest extends AbstractUnitTestCase
{
    /**
     * @return void
     */
    public function testImplementation(): void
    {
        $this->assertInstanceOf(EntityInterface::class, new RegistrationAction([\random_int(1, 100)], Str::random()));
    }

    /**
     * @return void
     */
    public function testGetCode(): void
    {
        $this->assertSame($codes = [\random_int(1, 100)], (new RegistrationAction($codes, Str::random()))->getCodes());
    }

    /**
     * @return void
     */
    public function testGetDescription(): void
    {
        $this->assertSame($desc = Str::random(), (new RegistrationAction([], $desc))->getDescription());
    }

    /**
     * @return void
     */
    public function testToArray(): void
    {
        $as_array = (new RegistrationAction($codes = [\random_int(1, 100)], $description = Str::random()))->toArray();

        $this->assertSame($codes, $as_array['codes']);
        $this->assertSame($description, $as_array['description']);
    }

    /**
     * @return void
     */
    public function testToJson(): void
    {
        $as_json = (new RegistrationAction($codes = [\random_int(1, 100)], $description = Str::random()))->toJson();

        $this->assertJsonStringEqualsJsonString(Json::encode([
            'codes'       => $codes,
            'description' => $description,
        ]), $as_json);
    }
}
