<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\Tests\References;

use Illuminate\Support\Str;
use AvtoDev\StaticReferences\References\AutoCategories\AutoCategories;

/**
 * @covers \AvtoDev\StaticReferences\References\AutoCategories\AutoCategories<extended>
 * @covers \AvtoDev\StaticReferences\References\AutoCategories\AutoCategoryEntry<extended>
 */
class AutoCategoriesReferenceTest extends AbstractReferenceTestCase
{
    /**
     * @var AutoCategories
     */
    protected $instance;

    /**
     * {@inheritdoc}
     */
    public function testArrayKeys(): void
    {
        foreach (['code', 'description'] as $key_name) {
            $this->assertArrayHasKey($key_name, $this->instance->first()->toArray());
        }
    }

    /**
     * Тест базовых акцессоров данных.
     *
     * @return void
     */
    public function testBasicData(): void
    {
        $this->assertGreaterThan(10, count($this->instance->all()));
        $assert_with = 'Мотоциклы';

        /*
         * По кодам.
         */
        // На латинице
        $this->assertEquals($assert_with, $this->instance->getByCode('A ')->getDescription());
        $this->assertEquals($assert_with, $this->instance->getByCode('  a')->getDescription());

        // На кириллице
        $this->assertEquals($assert_with, $this->instance->getByCode('  А ')->getDescription());
        $this->assertEquals($assert_with, $this->instance->getByCode(' а ')->getDescription());

        $this->assertTrue($this->instance->hasCode('A '));
        $this->assertTrue($this->instance->hasCode(' а'));
        $this->assertFalse($this->instance->hasCode('Ы'));

        /*
         * По описаниям.
         */
        $category_name = 'Трициклы';
        $assert_with   = 'B1';

        $this->assertEquals($assert_with, $this->instance->getByDescription($category_name)->getCode());
        $this->assertEquals($assert_with, $this->instance->getByDescription(' ' . $category_name)->getCode());
        $this->assertEquals($assert_with, $this->instance->getByDescription($category_name . ' ')->getCode());
        $this->assertEquals($assert_with, $this->instance->getByDescription(Str::upper($category_name))->getCode());

        $this->assertTrue($this->instance->hasDescription($category_name));
        $this->assertTrue($this->instance->hasDescription(' ' . $category_name));
        $this->assertFalse($this->instance->hasDescription('ЫDfsdgfs dsfDFfds'));
    }

    /**
     * {@inheritdoc}
     */
    protected function getReferenceClassName(): string
    {
        return AutoCategories::class;
    }
}
