<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\Tests\References\AutoFines;

use Illuminate\Support\Str;
use Tarampampam\Wrappers\Json;
use AvtoDev\StaticReferences\Tests\AbstractUnitTestCase;
use AvtoDev\StaticReferences\References\ReferenceEntryInterface;
use AvtoDev\StaticReferences\References\AutoFines\AutoFineEntry;

/**
 * @covers \AvtoDev\StaticReferences\References\AutoFines\AutoFineEntry<extended>
 *
 * @group  foo
 */
class AutoFineEntryTest extends AbstractUnitTestCase
{
    /**
     * @return void
     */
    public function testImplementation(): void
    {
        $this->assertInstanceOf(ReferenceEntryInterface::class, new AutoFineEntry(Str::random(), null));
    }

    /**
     * @return void
     */
    public function testGetCode(): void
    {
        $this->assertSame($article = Str::random(), (new AutoFineEntry($article, null))->getArticle());
    }

    /**
     * @return void
     */
    public function testGetDescription(): void
    {
        $this->assertSame($desc = Str::random(), (new AutoFineEntry(Str::random(), $desc))->getDescription());
        $this->assertNull((new AutoFineEntry(Str::random(), null))->getDescription());
    }

    /**
     * @return void
     */
    public function testToArray(): void
    {
        $as_array = (new AutoFineEntry($article = Str::random(), $description = Str::random()))->toArray();

        $this->assertSame($article, $as_array['article']);
        $this->assertSame($description, $as_array['description']);

        $as_array = (new AutoFineEntry($article = Str::random(), null))->toArray();

        $this->assertSame($article, $as_array['article']);
        $this->assertNull($as_array['description']);
    }

    /**
     * @return void
     */
    public function testToJson(): void
    {
        $as_json = (new AutoFineEntry($article = Str::random(), $description = Str::random()))->toJson();

        $this->assertJsonStringEqualsJsonString(Json::encode([
            'article'     => $article,
            'description' => $description,
        ]), $as_json);
    }
}
