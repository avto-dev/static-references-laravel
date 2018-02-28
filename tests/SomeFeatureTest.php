<?php

namespace AvtoDev\StaticReferences\Tests;

use AvtoDev\StaticReferences\References\AutoRegions\AutoRegionEntry;
use AvtoDev\StaticReferences\References\AutoCategories\AutoCategoryEntry;
use AvtoDev\StaticReferences\References\AutoRegions\AutoRegions;
use AvtoDev\StaticReferences\References\AutoCategories\AutoCategories;
use AvtoDev\StaticReferences\References\RegistrationActions\RegistrationActionEntry;
use AvtoDev\StaticReferences\References\RegistrationActions\RegistrationActions;

/**
 * Class SomeFeatureTest.
 *
 * @group feature
 */
class SomeFeatureTest extends AbstractUnitTestCase
{
    /**
     * Функциональный тест использования справочника "Категории транспортных средств".
     *
     * @return void
     */
    public function testAutoCategoriesReferenceUsage()
    {
        /** @var AutoCategories $auto_categories */
        $auto_categories = resolve(AutoCategories::class);

        // Перебираем все категории ТС
        $auto_categories->each(function (AutoCategoryEntry $category) {
            $category->getCode();
            $category->getDescription();
        });

        // Получаем коды всех категорий одним массивом
        $codes = collect($auto_categories->toArray())->pluck('code')->all(); // ['A', 'B1', 'B', ...];

        foreach ($auto_categories->all() as $category) {
            /* @var AutoCategoryEntry $category */
            $this->assertContains($category->getCode(), $codes);
        }

        // Получаем массив вида '%код_категории% => %её_описание%'
        $map = collect($auto_categories->toArray())->mapWithKeys(function (array $category) {
            return [$category['code'] => $category['description']];
        })->all();

        foreach ($auto_categories->all() as $category) {
            /* @var AutoCategoryEntry $category */
            $this->assertEquals($map[$category->getCode()], $category->getDescription());
        }

        // Получаем объект категории по его коду
        $category_b1 = $auto_categories->getByCode('B1');
        $this->assertInstanceOf(AutoCategoryEntry::class, $category_b1);
        $this->assertTrue($auto_categories->hasCode('B1')); // true
        $this->assertFalse($auto_categories->hasCode('A9')); // false
    }

    /**
     * Функциональный тест использования справочника "Регионы субъектов".
     *
     * @return void
     */
    public function testAutoRegionsReferenceUsage()
    {
        /** @var AutoRegions $auto_regions */
        $auto_regions = resolve(AutoRegions::class);

        // Перебираем все регионы субъектов
        $auto_regions->each(function (AutoRegionEntry $region) {
            $region->getRegionCode(); // код субъекта РФ
            $region->getAutoCodes(); // автомобильные коды (коды ГИБДД)
            $region->getIso31662(); // код региона по стандарту ISO-31662
            $region->getOkato(); // код региона по ОКАТО
            $region->getShortTitles(); // варианты короткого наименования региона
            $region->getTitle(); // заголовок региона
            $region->getType(); // тип (республика/край/и т.д.)
        });

        // Получаем заголовки всех регионов одним массивом
        $title = collect($auto_regions->toArray())->pluck('title')->toArray(); // === ['Республика Адыгея', 'Республика Алтай', ...];

        foreach ($auto_regions->all() as $region) {
            /* @var AutoRegionEntry $region */
            $this->assertContains($region->getTitle(), $title);
        }

        // Получаем массив вида '%название_региона% => [%его_гибдд_коды%]'
        $map = collect($auto_regions->toArray())->mapWithKeys(function (array $region) {
            return [$region['title'] => $region['auto_codes']];
        })->all();

        foreach ($auto_regions->all() as $region) {
            /* @var AutoRegionEntry $region */
            $this->assertEquals($map[$region->getTitle()], $region->getAutoCodes());
        }

        // Получаем объект региона по его заголовку
        $moscow_region = $auto_regions->getByTitle('Москва');
        $this->assertInstanceOf(AutoRegionEntry::class, $moscow_region);
        $this->assertTrue($auto_regions->hasAutoCode(177)); // true
        $this->assertFalse($auto_regions->hasAutoCode(666)); // false
    }

    /**
     * Функциональный тест использования справочника "Регистрационные действия".
     *
     * @return void
     */
    public function testRegistrationActionsReferenceUsage()
    {
        /** @var RegistrationActions $reg_actions */
        $reg_actions = resolve(RegistrationActions::class);
        $this->assertInstanceOf(RegistrationActions::class, $reg_actions);

        // Перебираем все регистрационные действия
        $reg_actions->each(function (RegistrationActionEntry $reg_action) {
            $reg_action->getCodes(); // коды регистрационного действия
            $reg_action->getDescription(); // описание регистрационного действия
        });

        // Получаем описания всех регистрационных действий одним массивом
        $descriptions = collect($reg_actions->toArray())->pluck('description')->toArray(); // === ['Первичная регистрация', ...];

        foreach ($reg_actions->all() as $reg_action) {
            /* @var RegistrationActionEntry $reg_action */
            $this->assertContains($reg_action->getDescription(), $descriptions);
        }

        // Получаем массив вида '%описание_рег_действия% => [%его_коды%]'
        $map = collect($reg_actions->toArray())->mapWithKeys(function (array $reg_action) {
            return [$reg_action['description'] => $reg_action['codes']];
        })->all();

        foreach ($reg_actions->all() as $reg_action) {
            /* @var RegistrationActionEntry $reg_action */
            $this->assertEquals($map[$reg_action->getDescription()], $reg_action->getCodes());
        }

        // Получаем объект категории по его заголовку
        $reg_action = $reg_actions->getByCode(11); // Первичная регистрация
        $this->assertInstanceOf(RegistrationActionEntry::class, $reg_action);
        $this->assertTrue($reg_actions->hasCode(11)); // true
        $this->assertFalse($reg_actions->hasCode(666)); // false
    }
}
