<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\Tests\References;

use AvtoDev\StaticReferences\References\CadastralDistricts\CadastralRegions;
use AvtoDev\StaticReferences\References\CadastralDistricts\CadastralDistricts;

/**
 * @covers \AvtoDev\StaticReferences\References\CadastralDistricts\CadastralDistricts<extended>
 * @covers \AvtoDev\StaticReferences\References\CadastralDistricts\CadastralDistrictEntry<extended>
 * @covers \AvtoDev\StaticReferences\References\CadastralDistricts\CadastralRegions<extended>
 * @covers \AvtoDev\StaticReferences\References\CadastralDistricts\CadastralRegionEntry<extended>
 */
class CadastralDistrictsReferenceTest extends AbstractReferenceTestCase
{
    /**
     * @var CadastralRegions
     */
    protected $instance;

    /**
     * {@inheritdoc}
     */
    public function testArrayKeys(): void
    {
        foreach (['code', 'name', 'districts'] as $key_name) {
            $elements = $this->instance->first()->toArray();
            $this->assertArrayHasKey($key_name, $elements);
            $this->assertInstanceOf(CadastralDistricts::class, $elements['districts']);
        }
    }

    /**
     * Тест базовых акцессоров данных.
     *
     * @return void
     */
    public function testBasicData(): void
    {
        $this->assertGreaterThan(90, count($this->instance->all()));
    }

    /**
     * Тест методов getRegionByCode & hasRegionCode.
     */
    public function testGetRegionByCode()
    {
        $this->assertEquals(
            'Чеченский',
            $this->instance->getRegionByCode(20)->getRegionName()
        );
        $this->assertEquals(
            'Читинский',
            $this->instance->getRegionByCode('75')->getRegionName()
        );
        $this->assertEquals(
            'Тверской',
            $this->instance->getRegionByCode(' 69  ')->getRegionName()
        );
        $this->assertEquals(
            66,
            $this->instance->getRegionByCode(66)->getRegionCode()
        );
        $this->assertGreaterThan(
            25,
            \count($this->instance->getRegionByCode(68)->getDistricts())
        );

        $districts = [
            ['code' => '01', 'name' => 'Балаклавский'],
            ['code' => '02', 'name' => 'Гагаринский'],
            ['code' => '03', 'name' => 'Ленинский'],
            ['code' => '04', 'name' => 'Нахимовский'],
            ['code' => '05', 'name' => 'Морской'],
        ];
        $this->assertSame(
            $districts,
            $this->instance->getRegionByCode(91)->getDistricts()->toArray()
        );

        $this->assertTrue($this->instance->hasRegionCode(77));
        $this->assertFalse($this->instance->hasRegionCode(191));
    }

    /**
     * Тест методов getRegionByName & hasRegionName.
     */
    public function testGetRegionByName()
    {
        $this->assertEquals(
            'Чеченский',
            $this->instance->getRegionByName(' Чеченский ')->getRegionName()
        );
        $this->assertEquals(
            66,
            $this->instance->getRegionByName('Свердловский  ')->getRegionCode()
        );
        $this->assertGreaterThan(
            7,
            \count($this->instance->getRegionByName('   Магаданский')->getDistricts())
        );

        $districts = [
            ['code' => '01', 'name' => 'Балаклавский'],
            ['code' => '02', 'name' => 'Гагаринский'],
            ['code' => '03', 'name' => 'Ленинский'],
            ['code' => '04', 'name' => 'Нахимовский'],
            ['code' => '05', 'name' => 'Морской'],
        ];
        $this->assertSame(
            $districts,
            $this->instance->getRegionByName('Севастопольский')->getDistricts()->toArray()
        );

        $this->assertTrue($this->instance->hasRegionName('  Иркутский'));
        $this->assertFalse($this->instance->hasRegionName('Фуубарский'));
    }

    /**
     * Тест методов getDistrictByCode & hasDistrictCode.
     */
    public function testGetDistrictByCode()
    {
        $this->assertEquals(
            'Заволжский',
            $this->instance->getRegionByCode('37')
                ->getDistricts()
                ->getDistrictByCode('  04     ')
                ->getDistrictName()
        );
        $this->assertEquals(
            '04',
            $this->instance->getRegionByName('Свердловский  ')
                ->getDistricts()
                ->getDistrictByCode('  04     ')
                ->getDistrictCode()
        );
        $this->assertGreaterThan(
            7,
            \count($this->instance->getRegionByName('   Магаданский')->getDistricts())
        );

        $this->assertTrue($this->instance->getRegionByName('  Владимирский')->getDistricts()->hasDistrictCode('07'));
        $this->assertFalse($this->instance->getRegionByName('Краснодарский')->getDistricts()->hasDistrictCode('711'));
    }

    /**
     * Тест методов getDistrictByName & hasDistrictName.
     */
    public function testGetDistrictByName()
    {
        $this->assertEquals(
            'Сысольский',
            $this->instance->getRegionByCode('11')
                ->getDistricts()
                ->getDistrictByName('  Сысольский     ')
                ->getDistrictName()
        );
        $this->assertEquals(
            '10',
            $this->instance->getRegionByName('Карельский  ')
                ->getDistricts()
                ->getDistrictByName('  Ладожский')
                ->getDistrictCode()
        );
        $this->assertGreaterThan(
            8,
            \count($this->instance->getRegionByName('   Карачаево-Черкесский')->getDistricts())
        );

        $this->assertTrue($this->instance->getRegionByName('  Дагестанский')
            ->getDistricts()
            ->hasDistrictName('Дербентский районный'));
        $this->assertFalse($this->instance->getRegionByName('  Дагестанский')
            ->getDistricts()
            ->hasDistrictName('Каакентский'));
    }

    /**
     * {@inheritdoc}
     */
    protected function getReferenceClassName(): string
    {
        return CadastralRegions::class;
    }
}
