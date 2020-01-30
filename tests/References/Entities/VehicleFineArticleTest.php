<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\Tests\References\Entities;

use Illuminate\Support\Str;
use Tarampampam\Wrappers\Json;
use AvtoDev\StaticReferences\Tests\AbstractUnitTestCase;
use AvtoDev\StaticReferences\References\Entities\EntityInterface;
use AvtoDev\StaticReferences\References\Entities\VehicleFineArticle;

/**
 * @covers \AvtoDev\StaticReferences\References\Entities\VehicleFineArticle
 */
class VehicleFineArticleTest extends AbstractUnitTestCase
{
    /**
     * @return void
     */
    public function testImplementation(): void
    {
        $this->assertInstanceOf(EntityInterface::class, new VehicleFineArticle(Str::random(), Str::random()));
    }

    /**
     * @return void
     */
    public function testGetCode(): void
    {
        $this->assertSame($article = Str::random(), (new VehicleFineArticle($article, Str::random()))->getArticle());
    }

    /**
     * @return void
     */
    public function testGetDescription(): void
    {
        $this->assertSame($desc = Str::random(), (new VehicleFineArticle(Str::random(), $desc))->getDescription());
    }

    /**
     * @return void
     */
    public function testToArray(): void
    {
        $as_array = (new VehicleFineArticle($article = Str::random(), $description = Str::random()))->toArray();

        $this->assertSame($article, $as_array['article']);
        $this->assertSame($description, $as_array['description']);
    }

    /**
     * @return void
     */
    public function testToJson(): void
    {
        $as_json = (new VehicleFineArticle($article = Str::random(), $description = Str::random()))->toJson();

        $this->assertJsonStringEqualsJsonString(Json::encode([
            'article'     => $article,
            'description' => $description,
        ]), $as_json);
    }
}
