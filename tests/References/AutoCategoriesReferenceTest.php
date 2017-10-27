<?php

namespace AvtoDev\StaticReferencesLaravel\Tests\References;

use Illuminate\Support\Str;
use AvtoDev\StaticReferencesLaravel\PreferencesProviders\AutoCategoriesProvider;
use AvtoDev\StaticReferencesLaravel\References\AutoCategories\AutoCategoriesReference;

class AutoCategoriesReferenceTest extends AbstractReferenceTestCase
{
    /**
     * @var AutoCategoriesReference
     */
    protected $reference_instance;

    /**
     * @var string
     */
    protected $reference_class = AutoCategoriesReference::class;

    /**
     * @var string
     */
    protected $reference_provider_class = AutoCategoriesProvider::class;

    /**
     * {@inheritdoc}
     */
    function testArrayKeys()
    {
        foreach (['code', 'description'] as $key_name) {
            $this->assertArrayHasKey($key_name, $this->reference_instance->first()->toArray());
        }
    }

    /**
     * Тест базовых акцессоров данных.
     *
     * @return void
     */
    public function testBasicData()
    {
        $this->assertGreaterThan(10, count($this->reference_instance->all()));
        $assert_with = 'Мотоциклы';

        /*
         * По кодам.
         */
        // На латинице
        $this->assertEquals($assert_with, $this->reference_instance->getByCode('A ')->getDescription());
        $this->assertEquals($assert_with, $this->reference_instance->getByCode('  a')->getDescription());

        // На кириллице
        $this->assertEquals($assert_with, $this->reference_instance->getByCode('  А ')->getDescription());
        $this->assertEquals($assert_with, $this->reference_instance->getByCode(' а ')->getDescription());

        $this->assertTrue($this->reference_instance->hasCode('A '));
        $this->assertTrue($this->reference_instance->hasCode(' а'));
        $this->assertFalse($this->reference_instance->hasCode('Ы'));

        /*
         * По описаниям.
         */
        $category_name = 'Трициклы';
        $assert_with   = 'B1';

        $this->assertEquals($assert_with, $this->reference_instance->getByDescription($category_name)->getCode());
        $this->assertEquals($assert_with, $this->reference_instance->getByDescription(' ' . $category_name)->getCode());
        $this->assertEquals($assert_with, $this->reference_instance->getByDescription($category_name . ' ')->getCode());
        $this->assertEquals($assert_with, $this->reference_instance->getByDescription(Str::upper($category_name))->getCode());

        $this->assertTrue($this->reference_instance->hasDescription($category_name));
        $this->assertTrue($this->reference_instance->hasDescription(' ' . $category_name));
        $this->assertFalse($this->reference_instance->hasDescription('ЫDfsdgfs dsfDFfds'));
    }
}
