<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\Tests\References\AutoCategories;

use Illuminate\Support\Str;
use Tarampampam\Wrappers\Json;
use AvtoDev\StaticReferences\Tests\AbstractUnitTestCase;
use AvtoDev\StaticReferences\References\ReferenceEntryInterface;
use AvtoDev\StaticReferences\References\AutoCategories\AutoCategoryEntry;

/**
 * @covers \AvtoDev\StaticReferences\References\AutoCategories\AutoCategoryEntry<extended>
 *
 * @group  foo
 */
class AutoCategoryEntryTest extends AbstractUnitTestCase
{
    /**
     * @return void
     */
    public function testImplementation(): void
    {
        $this->assertInstanceOf(ReferenceEntryInterface::class, new AutoCategoryEntry(Str::random(), null));
    }

    /**
     * @return void
     */
    public function testGetCode(): void
    {
        $this->assertSame($code = Str::random(), (new AutoCategoryEntry($code, null))->getCode());
    }

    /**
     * @return void
     */
    public function testGetDescription(): void
    {
        $this->assertSame($desc = Str::random(), (new AutoCategoryEntry(Str::random(), $desc))->getDescription());
        $this->assertNull((new AutoCategoryEntry(Str::random(), null))->getDescription());
    }

    /**
     * @return void
     */
    public function testToArray(): void
    {
        $as_array = (new AutoCategoryEntry($code = Str::random(), $description = Str::random()))->toArray();

        $this->assertSame($code, $as_array['code']);
        $this->assertSame($description, $as_array['description']);

        $as_array = (new AutoCategoryEntry($code = Str::random(), null))->toArray();

        $this->assertSame($code, $as_array['code']);
        $this->assertNull($as_array['description']);
    }

    /**
     * @return void
     */
    public function testToJson(): void
    {
        $as_json = (new AutoCategoryEntry($code = Str::random(), $description = Str::random()))->toJson();

        $this->assertJsonStringEqualsJsonString(Json::encode([
            'code'        => $code,
            'description' => $description,
        ]), $as_json);
    }
}
