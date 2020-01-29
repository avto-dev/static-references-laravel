<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\Tests\References\AutoRegions;

use Illuminate\Support\Str;
use Tarampampam\Wrappers\Json;
use AvtoDev\StaticReferences\Tests\AbstractUnitTestCase;
use AvtoDev\StaticReferences\References\ReferenceEntryInterface;
use AvtoDev\StaticReferences\References\AutoRegions\AutoRegionEntry;

/**
 * @covers \AvtoDev\StaticReferences\References\AutoRegions\AutoRegionEntry<extended>
 *
 * @group  foo
 */
class AutoRegionEntryTest extends AbstractUnitTestCase
{
    /**
     * @return void
     */
    public function testImplementation(): void
    {
        $this->assertInstanceOf(ReferenceEntryInterface::class, new AutoRegionEntry(
            \random_int(1, 100), null, null, null, null, null, null
        ));
    }

    /**
     * @return void
     */
    public function testGetRegionCode(): void
    {
        $this->assertSame($value = \random_int(1, 100), (new AutoRegionEntry(
            $value, null, null, null, null, null, null
        ))->getRegionCode());
    }

    /**
     * @return void
     */
    public function testGetTitle(): void
    {
        $this->assertSame($value = Str::random(), (new AutoRegionEntry(
            \random_int(1, 100), $value, null, null, null, null, null
        ))->getTitle());

        $this->assertNull((new AutoRegionEntry(
            \random_int(1, 100), null, null, null, null, null, null
        ))->getTitle());
    }

    /**
     * @return void
     */
    public function testGetShortTitles(): void
    {
        $this->assertSame($value = [Str::random()], (new AutoRegionEntry(
            \random_int(1, 100), null, $value, null, null, null, null
        ))->getShortTitles());

        $this->assertNull((new AutoRegionEntry(
            \random_int(1, 100), null, null, null, null, null, null
        ))->getShortTitles());
    }

    /**
     * @return void
     */
    public function testGetAutoCodes(): void
    {
        $this->assertSame($value = [\random_int(1, 50), \random_int(51, 100)], (new AutoRegionEntry(
            \random_int(1, 100), null, null, $value, null, null, null
        ))->getAutoCodes());

        $this->assertNull((new AutoRegionEntry(
            \random_int(1, 100), null, null, null, null, null, null
        ))->getAutoCodes());
    }

    /**
     * @return void
     */
    public function testGetOkato(): void
    {
        $this->assertSame($value = Str::random(), (new AutoRegionEntry(
            \random_int(1, 100), null, null, null, $value, null, null
        ))->getOkato());

        $this->assertNull((new AutoRegionEntry(
            \random_int(1, 100), null, null, null, null, null, null
        ))->getOkato());
    }

    /**
     * @return void
     */
    public function testGetIso31662(): void
    {
        $this->assertSame($value = Str::random(), (new AutoRegionEntry(
            \random_int(1, 100), null, null, null, null, $value, null
        ))->getIso31662());

        $this->assertNull((new AutoRegionEntry(
            \random_int(1, 100), null, null, null, null, null, null
        ))->getIso31662());
    }

    /**
     * @return void
     */
    public function testGetType(): void
    {
        $this->assertSame($value = Str::random(), (new AutoRegionEntry(
            \random_int(1, 100), null, null, null, null, null, $value
        ))->getType());

        $this->assertNull((new AutoRegionEntry(
            \random_int(1, 100), null, null, null, null, null, null
        ))->getType());
    }

    /**
     * @return void
     */
    public function testToArray(): void
    {
        $as_array = (new AutoRegionEntry(
            $code = \random_int(1, 100),
            $title = Str::random(),
            $short_titles = [Str::random()],
            $auto_codes = [\random_int(1, 50), \random_int(51, 100)],
            $okato = Str::random(),
            $iso_31662 = Str::random(),
            $type = Str::random()
        ))->toArray();

        $this->assertSame($code, $as_array['code']);
        $this->assertSame($title, $as_array['title']);
        $this->assertSame($auto_codes, $as_array['gibdd']);
        $this->assertSame($iso_31662, $as_array['code_iso_31662']);
        $this->assertSame($okato, $as_array['okato']);
        $this->assertSame($short_titles, $as_array['short']);
        $this->assertSame($type, $as_array['type']);

        $as_array = (new AutoRegionEntry(
            $code = \random_int(1, 100), null, null, null, null, null, null
        ))->toArray();

        $this->assertSame($code, $as_array['code']);
        $this->assertNull($as_array['title']);
        $this->assertNull($as_array['gibdd']);
        $this->assertNull($as_array['code_iso_31662']);
        $this->assertNull($as_array['okato']);
        $this->assertNull($as_array['short']);
        $this->assertNull($as_array['type']);
    }

    /**
     * @return void
     */
    public function testToJson(): void
    {
        $as_json = (new AutoRegionEntry(
            $code = \random_int(1, 100),
            $title = Str::random(),
            $short_titles = [Str::random()],
            $auto_codes = [\random_int(1, 50), \random_int(51, 100)],
            $okato = Str::random(),
            $iso_31662 = Str::random(),
            $type = Str::random()
        ))->toJson();

        $this->assertJsonStringEqualsJsonString(Json::encode([
            'code'           => $code,
            'title'          => $title,
            'gibdd'          => $auto_codes,
            'code_iso_31662' => $iso_31662,
            'okato'          => $okato,
            'short'          => $short_titles,
            'type'           => $type,
        ]), $as_json);
    }
}
