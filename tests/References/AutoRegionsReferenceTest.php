<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\Tests\References;

use AvtoDev\StaticReferences\References\AutoRegions\AutoRegions;

/**
 * @covers \AvtoDev\StaticReferences\References\AutoRegions\AutoRegions<extended>
 * @covers \AvtoDev\StaticReferences\References\AutoRegions\AutoRegionEntry<extended>
 */
class AutoRegionsReferenceTest extends AbstractReferenceTestCase
{
    /**
     * @var AutoRegions
     */
    protected $instance;

    /**
     * Тест базовых акцессоров данных.
     *
     * @return void
     */
    public function testBasicData(): void
    {
        $this->assertGreaterThan(10, count($this->instance->all()));
    }

    /**
     * {@inheritdoc}
     */
    public function testArrayKeys(): void
    {
        $keys = [
            'title',
            'short_titles',
            'region_code',
            'auto_codes',
            'okato',
            'iso_31662',
            'type',
        ];

        foreach ($keys as $key_name) {
            $this->assertArrayHasKey($key_name, $this->instance->first()->toArray());
        }
    }

    /**
     * Тест метода `getByRegionCode()` + has.
     */
    public function testGetByRegionCode(): void
    {
        $this->assertEquals(
            'Республика Марий Эл',
            $this->instance->getByRegionCode(12)->getTitle()
        );

        $this->assertEquals(
            12,
            $this->instance->getByRegionCode(12)->getRegionCode()
        );
        $this->assertEquals(
            'RU-ME',
            $this->instance->getByRegionCode(12)->getIso31662()
        );
        $this->assertEquals(
            'RU-TA',
            $this->instance->getByRegionCode(16)->getIso31662()
        );

        $this->assertTrue($this->instance->hasRegionCode(12));
        $this->assertFalse($this->instance->hasRegionCode(999));
    }

    /**
     * Тестируем метод `getByAutoCode()` + has.
     */
    public function testGetByAutoCode(): void
    {
        // Существующие области
        $moscow = 'Москва';
        $this->assertEquals($moscow, $this->instance->getByAutoCode(77)->getTitle());
        $this->assertEquals($moscow, $this->instance->getByAutoCode('077')->getTitle());
        $this->assertEquals($moscow, $this->instance->getByAutoCode('арарара 077')->getTitle());
        $this->assertEquals(
            [77, 97, 99, 177, 197, 199, 799, 777],
            $this->instance->getByAutoCode(77)->getAutoCodes()
        );

        $sverdl_obl = 'Свердловская область';
        $this->assertEquals($sverdl_obl, $this->instance->getByAutoCode(66)->getTitle());
        $this->assertEquals($sverdl_obl, $this->instance->getByAutoCode('66')->getTitle());
        $this->assertEquals($sverdl_obl, $this->instance->getByAutoCode('boom 66 пыщь')->getTitle());
        $this->assertEquals([66, 96, 196], $this->instance->getByAutoCode(66)->getAutoCodes());

        $this->assertTrue($this->instance->hasAutoCode(716));
        $this->assertEquals('Республика Татарстан', $this->instance->getByAutoCode(716)->getTitle());

        // И не существующие области
        $this->assertNull($this->instance->getByAutoCode(997));
        $this->assertNull($this->instance->getByAutoCode(299));
        $this->assertNull($this->instance->getByAutoCode(0));
        $this->assertNull($this->instance->getByAutoCode(null));
        $this->assertNull($this->instance->getByAutoCode([]));
        $this->assertNull($this->instance->getByAutoCode('трали вали'));

        $this->assertTrue($this->instance->hasAutoCode(66));
        $this->assertFalse($this->instance->hasAutoCode(997));
    }

    /**
     * Тестируем метод `getByTitle()` + has.
     */
    public function testGetByTitle(): void
    {
        $moscow = 'Москва';

        // Находит без опечаток
        $this->assertEquals($moscow, $this->instance->getByTitle('Москва')->getTitle());

        // Находит с опечатками
        $this->assertEquals($moscow, $this->instance->getByTitle('Мaсcква')->getTitle());
        $this->assertEquals($moscow, $this->instance->getByTitle('Мсква')->getTitle());

        // Не находит, если передан совсем уж пиздец или пустая строка
        $this->assertNull($this->instance->getByTitle('Мяусикывуа'));
        $this->assertNull($this->instance->getByTitle(''));

        // Не находит, если включен режим строго соответствия
        $this->assertNull($this->instance->getByTitle('Мaсcква', true));
        $this->assertNull($this->instance->getByTitle('Мaсcкваaaa', true));

        // Ну и парочка дополнительных тестов на не строгое соответствие
        $expected = 86;
        $this->assertEquals($expected, $this->instance->getByTitle('Хмао')->getRegionCode());
        $this->assertEquals($expected, $this->instance->getByTitle('Югра')->getRegionCode());
        $this->assertEquals($expected, $this->instance->getByTitle('Ханты-Мансийский')->getRegionCode());

        $this->assertTrue($this->instance->hasTitle($moscow));
        $this->assertFalse($this->instance->hasTitle('Мaсcкваaaa'));
    }

    /**
     * Тестируем метод `getByOkato()` + has.
     */
    public function testGetByOkato(): void
    {
        $sverdl_obl = 'Свердловская область';
        $this->assertEquals($sverdl_obl, $this->instance->getByOkato(65)->getTitle());
        $this->assertEquals($sverdl_obl, $this->instance->getByOkato('65')->getTitle());

        $this->assertEquals('Ямало-Ненецкий автономный округ',
            $this->instance->getByOkato('71-9')->getTitle());
        $this->assertEquals('Ханты-Мансийский автономный округ - Югра', $this->instance->getByOkato('71-8')
            ->getTitle());

        $this->assertNull($this->instance->getByOkato(123));
        $this->assertNull($this->instance->getByOkato(null));
        $this->assertNull($this->instance->getByOkato([]));

        $this->assertTrue($this->instance->hasOkato(65));
        $this->assertFalse($this->instance->hasOkato(123));
    }

    /**
     * Тестируем метод `getByIso31662()` + has.
     */
    public function testGetByIso31662(): void
    {
        $sverdl_obl = 'Свердловская область';
        $this->assertEquals(
            $sverdl_obl,
            $this->instance->getByIso31662('RU-SVE')->getTitle()
        );
        $this->assertEquals(
            $sverdl_obl,
            $this->instance->getByIso31662('ууу RU-SVE ыыы')->getTitle()
        );
        $this->assertEquals(
            'Смоленская область',
            $this->instance->getByIso31662('RU-SMO')->getTitle()
        );
        $this->assertEquals(
            'Республика Крым',
            $this->instance->getByIso31662('RU-CR')->getTitle()
        );

        $this->assertNull($this->instance->getByIso31662('123123'));
        $this->assertNull($this->instance->getByIso31662('SDFD-RYGF'));
        $this->assertNull($this->instance->getByIso31662(''));

        $this->assertTrue($this->instance->hasIso31662('RU-SMO'));
        $this->assertFalse($this->instance->hasOkato('SDFD-RYGF'));
    }

    /**
     * {@inheritdoc}
     */
    protected function getReferenceClassName(): string
    {
        return AutoRegions::class;
    }
}
