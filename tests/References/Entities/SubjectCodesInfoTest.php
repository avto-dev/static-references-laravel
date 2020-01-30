<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\Tests\References\Entities;

use Illuminate\Support\Str;
use Tarampampam\Wrappers\Json;
use AvtoDev\StaticReferences\Tests\AbstractUnitTestCase;
use AvtoDev\StaticReferences\References\Entities\SubjectCodesInfo;
use AvtoDev\StaticReferences\References\Entities\EntityInterface;

/**
 * @covers \AvtoDev\StaticReferences\References\Entities\SubjectCodesInfo
 */
class SubjectCodesInfoTest extends AbstractUnitTestCase
{
    /**
     * @return void
     */
    public function testImplementation(): void
    {
        $this->assertInstanceOf(EntityInterface::class, new SubjectCodesInfo(
            \random_int(1, 100), Str::random(), [], Str::random()
        ));
    }

    /**
     * @return void
     */
    public function testGetSubjectCode(): void
    {
        $this->assertSame($value = \random_int(1, 100), (new SubjectCodesInfo(
            $value, Str::random(), [], Str::random()
        ))->getSubjectCode());
    }

    /**
     * @return void
     */
    public function testGetTitle(): void
    {
        $this->assertSame($value = Str::random(), (new SubjectCodesInfo(
            \random_int(1, 100), $value, [], Str::random()
        ))->getTitle());
    }

    /**
     * @return void
     */
    public function testGetGibddCodes(): void
    {
        $this->assertSame($value = [\random_int(1, 50), \random_int(51, 100)], (new SubjectCodesInfo(
            \random_int(1, 100), Str::random(), $value, Str::random()
        ))->getGibddCodes());
    }

    /**
     * @return void
     */
    public function testGetIso31662Code(): void
    {
        $this->assertSame($value = Str::random(), (new SubjectCodesInfo(
            \random_int(1, 100), Str::random(), [], $value
        ))->getIso31662Code());
    }

    /**
     * @return void
     */
    public function testToArray(): void
    {
        $as_array = (new SubjectCodesInfo(
            $code = \random_int(1, 100),
            $title = Str::random(),
            $auto_codes = [\random_int(1, 50), \random_int(51, 100)],
            $iso_31662 = Str::random()
        ))->toArray();

        $this->assertSame($code, $as_array['code']);
        $this->assertSame($title, $as_array['title']);
        $this->assertSame($auto_codes, $as_array['gibdd']);
        $this->assertSame($iso_31662, $as_array['code_iso_31662']);
    }

    /**
     * @return void
     */
    public function testToJson(): void
    {
        $as_json = (new SubjectCodesInfo(
            $code = \random_int(1, 100),
            $title = Str::random(),
            $auto_codes = [\random_int(1, 50), \random_int(51, 100)],
            $iso_31662 = Str::random()
        ))->toJson();

        $this->assertJsonStringEqualsJsonString(Json::encode([
            'code'           => $code,
            'title'          => $title,
            'gibdd'          => $auto_codes,
            'code_iso_31662' => $iso_31662,
        ]), $as_json);
    }
}
