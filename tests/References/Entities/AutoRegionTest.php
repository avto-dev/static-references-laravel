<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\Tests\References\Entities;

use Illuminate\Support\Str;
use Tarampampam\Wrappers\Json;
use AvtoDev\StaticReferences\Tests\AbstractUnitTestCase;
use AvtoDev\StaticReferences\References\Entities\AutoRegion;
use AvtoDev\StaticReferences\References\Entities\EntityInterface;

/**
 * @covers \AvtoDev\StaticReferences\References\Entities\AutoRegion<extended>
 */
class AutoRegionTest extends AbstractUnitTestCase
{
    /**
     * @return void
     */
    public function testImplementation(): void
    {
        $this->assertInstanceOf(EntityInterface::class, new AutoRegion(
            \random_int(1, 100), Str::random(), [], [], Str::random(), Str::random(), Str::random()
        ));
    }

    /**
     * @return void
     */
    public function testGetRegionCode(): void
    {
        $this->assertSame($value = \random_int(1, 100), (new AutoRegion(
            $value, Str::random(), [], [], Str::random(), Str::random(), Str::random()
        ))->getRegionCode());
    }

    /**
     * @return void
     */
    public function testGetTitle(): void
    {
        $this->assertSame($value = Str::random(), (new AutoRegion(
            \random_int(1, 100), $value, [], [], Str::random(), Str::random(), Str::random()
        ))->getTitle());
    }

    /**
     * @return void
     */
    public function testGetShortTitles(): void
    {
        $this->assertSame($value = [Str::random()], (new AutoRegion(
            \random_int(1, 100), Str::random(), $value, [], Str::random(), Str::random(), Str::random()
        ))->getShortTitles());
    }

    /**
     * @return void
     */
    public function testGetAutoCodes(): void
    {
        $this->assertSame($value = [\random_int(1, 50), \random_int(51, 100)], (new AutoRegion(
            \random_int(1, 100), Str::random(), [], $value, Str::random(), Str::random(), Str::random()
        ))->getAutoCodes());
    }

    /**
     * @return void
     */
    public function testGetOkato(): void
    {
        $this->assertSame($value = Str::random(), (new AutoRegion(
            \random_int(1, 100), Str::random(), [], [], $value, Str::random(), Str::random()
        ))->getOkato());
    }

    /**
     * @return void
     */
    public function testGetIso31662(): void
    {
        $this->assertSame($value = Str::random(), (new AutoRegion(
            \random_int(1, 100), Str::random(), [], [], Str::random(), $value, Str::random()
        ))->getIso31662());
    }

    /**
     * @return void
     */
    public function testGetType(): void
    {
        $this->assertSame($value = Str::random(), (new AutoRegion(
            \random_int(1, 100), Str::random(), [], [], Str::random(), Str::random(), $value
        ))->getType());
    }

    /**
     * @return void
     */
    public function testToArray(): void
    {
        $as_array = (new AutoRegion(
            $code = \random_int(1, 100),
            $title = Str::random(),
            $short_titles = [Str::random()],
            $auto_codes = [\random_int(1, 50), \random_int(51, 100)],
            $okato = Str::random(),
            $iso_31662 = Str::random(),
            $type = Str::random()
        ))->toArray();

        $this->assertSame($code, $as_array['region_code']);
        $this->assertSame($title, $as_array['title']);
        $this->assertSame($auto_codes, $as_array['auto_codes']);
        $this->assertSame($iso_31662, $as_array['iso_31662']);
        $this->assertSame($okato, $as_array['okato']);
        $this->assertSame($short_titles, $as_array['short_titles']);
        $this->assertSame($type, $as_array['type']);
    }

    /**
     * @return void
     */
    public function testToJson(): void
    {
        $as_json = (new AutoRegion(
            $code = \random_int(1, 100),
            $title = Str::random(),
            $short_titles = [Str::random()],
            $auto_codes = [\random_int(1, 50), \random_int(51, 100)],
            $okato = Str::random(),
            $iso_31662 = Str::random(),
            $type = Str::random()
        ))->toJson();

        $this->assertJsonStringEqualsJsonString(Json::encode([
            'region_code'  => $code,
            'title'        => $title,
            'auto_codes'   => $auto_codes,
            'iso_31662'    => $iso_31662,
            'okato'        => $okato,
            'short_titles' => $short_titles,
            'type'         => $type,
        ]), $as_json);
    }
}
