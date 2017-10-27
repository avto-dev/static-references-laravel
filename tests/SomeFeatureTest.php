<?php

namespace AvtoDev\StaticReferencesLaravel\Tests;

use AvtoDev\StaticReferencesLaravel\StaticReferences;
use AvtoDev\StaticReferencesLaravel\References\AutoCategories\AutoCategoryEntry;
use AvtoDev\StaticReferencesLaravel\References\AutoCategories\AutoCategoriesReference;

use AvtoDev\StaticReferencesLaravel\References\AutoRegions\AutoRegionsReference;
use AvtoDev\StaticReferencesLaravel\References\AutoRegions\AutoRegionEntry;

use AvtoDev\StaticReferencesLaravel\References\RegistrationActions\RegistrationActionsReference;
use AvtoDev\StaticReferencesLaravel\References\RegistrationActions\RegistrationActionEntry;

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
        /** @var AutoCategoriesReference $auto_categories */
        $auto_categories = app(StaticReferences::class)->make(AutoCategoriesReference::class);
        $this->assertInstanceOf(AutoCategoriesReference::class, $auto_categories);

        // Перебираем все категории ТС
        $auto_categories->each(function (AutoCategoryEntry $category) {
            $category->getCode();
            $category->getDescription();
        });

        // Получаем коды всех категорий одним массивом
        $codes = $auto_categories->pluck('code')->toArray(); // === ['A', 'B1', 'B', ...];

        foreach ($auto_categories->all() as $category) {
            /* @var AutoCategoryEntry $category */
            $this->assertContains($category->getCode(), $codes);
        }

        // Получаем массив вида '%код_категории% => %её_описание%'
        $map = $auto_categories->mapWithKeys(function (AutoCategoryEntry $category) {
            return [$category->getCode() => $category->getDescription()];
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
        /** @var AutoRegionsReference $auto_regions */
        $auto_regions = app(StaticReferences::class)->make(AutoRegionsReference::class);
        $this->assertInstanceOf(AutoRegionsReference::class, $auto_regions);

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
        $title = $auto_regions->pluck('title')->toArray(); // === ['Республика Адыгея', 'Республика Алтай', ...];

        foreach ($auto_regions->all() as $region) {
            /* @var AutoRegionEntry $region */
            $this->assertContains($region->getTitle(), $title);
        }

        // Получаем массив вида '%название_региона% => [%его_гибдд_коды%]'
        $map = $auto_regions->mapWithKeys(function (AutoRegionEntry $region) {
            return [$region->getTitle() => $region->getAutoCodes()];
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
        /** @var RegistrationActionsReference $reg_actions */
        $reg_actions = app(StaticReferences::class)->make(RegistrationActionsReference::class);
        $this->assertInstanceOf(RegistrationActionsReference::class, $reg_actions);

        // Перебираем все регистрационные действия
        $reg_actions->each(function (RegistrationActionEntry $reg_action) {
            $reg_action->getCodes(); // коды регистрационного действия
            $reg_action->getDescription(); // описание регистрационного действия
        });

        // Получаем описания всех регистрационных действий одним массивом
        $descriptions = $reg_actions->pluck('description')->toArray(); // === ['Первичная регистрация', ...];

        foreach ($reg_actions->all() as $reg_action) {
            /* @var RegistrationActionEntry $reg_action */
            $this->assertContains($reg_action->getDescription(), $descriptions);
        }

        // Получаем массив вида '%описание_рег_действия% => [%его_коды%]'
        $map = $reg_actions->mapWithKeys(function (RegistrationActionEntry $reg_action) {
            return [$reg_action->getDescription() => $reg_action->getCodes()];
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
