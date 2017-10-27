<?php

namespace AvtoDev\StaticReferencesLaravel\Tests;

use AvtoDev\StaticReferencesLaravel\References\AutoCategories\AutoCategoriesReference;
use AvtoDev\StaticReferencesLaravel\References\AutoCategories\AutoCategoryEntry;
use AvtoDev\StaticReferencesLaravel\StaticReferences;

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
            /** @var AutoCategoryEntry $category */
            $this->assertContains($category->getCode(), $codes);
        }

        // Получаем массив вида '%код_категории% => %её_описание%'
        $map = $auto_categories->mapWithKeys(function (AutoCategoryEntry $category) {
            return [$category->getCode() => $category->getDescription()];
        })->all();

        foreach ($auto_categories->all() as $category) {
            /** @var AutoCategoryEntry $category */
            $this->assertEquals($map[$category->getCode()], $category->getDescription());
        }

        // Получаем объект категории по его коду
        $category_b1 = $auto_categories->getByCode('B1');
        $this->assertInstanceOf(AutoCategoryEntry::class, $category_b1);
        $this->assertTrue($auto_categories->hasCode('B1')); // true
        $this->assertFalse($auto_categories->hasCode('A9')); // false
    }
}
