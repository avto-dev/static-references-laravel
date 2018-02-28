<?php

namespace AvtoDev\StaticReferences\Tests\References;

use Illuminate\Support\Str;
use AvtoDev\StaticReferences\References\AutoCategories\AutoCategories;

class AutoCategoriesReferenceTest extends AbstractReferenceTestCase
{
    /**
     * @var AutoCategories
     */
    protected $instance;

    /**
     * {@inheritdoc}
     */
    protected function getReferenceClassName()
    {
        return AutoCategories::class;
    }

    /**
     * {@inheritdoc}
     */
    public function testArrayKeys()
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
    public function testBasicData()
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
}
