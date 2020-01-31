<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\Tests\References\Entities;

use Illuminate\Support\Str;
use AvtoDev\StaticReferences\Tests\AbstractUnitTestCase;
use AvtoDev\StaticReferences\References\Entities\CadastralArea;
use AvtoDev\StaticReferences\References\Entities\EntityInterface;

/**
 * @covers \AvtoDev\StaticReferences\References\Entities\CadastralArea
 */
class CadastralAreaTest extends AbstractUnitTestCase
{
    /**
     * @return void
     */
    public function testImplementation(): void
    {
        $this->assertInstanceOf(EntityInterface::class, new CadastralArea(\random_int(1, 100), Str::random()));
    }

    /**
     * @return void
     */
    public function testGetCode(): void
    {
        $this->assertSame($code = \random_int(1, 100), (new CadastralArea($code, Str::random()))->getCode());
    }

    /**
     * @return void
     */
    public function testGetName(): void
    {
        $this->assertSame($name = Str::random(), (new CadastralArea(\random_int(1, 100), $name))->getName());
    }

    /**
     * @return void
     */
    public function testToArray(): void
    {
        $as_array = (new CadastralArea($code = \random_int(1, 100), $name = Str::random()))->toArray();

        $this->assertSame($code, $as_array['code']);
        $this->assertSame($name, $as_array['name']);
    }
}
