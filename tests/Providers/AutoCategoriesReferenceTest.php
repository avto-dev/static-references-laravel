<?php

namespace AvtoDev\StaticReferencesLaravel\Tests\Providers;

use AvtoDev\StaticReferencesLaravel\StaticReferences;
use AvtoDev\StaticReferencesLaravel\Tests\AbstractUnitTestCase;
use AvtoDev\StaticReferencesLaravel\Providers\AutoCategories\AutoCategoriesProvider;

/**
 * Class AutoCategoriesReferenceTest.
 */
class AutoCategoriesReferenceTest extends AbstractUnitTestCase
{
    /**
     * @var AutoCategoriesProvider
     */
    protected $instance;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->instance = new AutoCategoriesProvider(new StaticReferences($this->app));
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        unset($this->instance);

        parent::tearDown();
    }

    /**
     * Тест метода `getName()`.
     *
     * @return void
     */
    public function testGetName()
    {
        $this->assertEquals('autoCategories', $this->instance->getName());
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

        $this->assertTrue($this->instance->hasDescription($category_name));
        $this->assertTrue($this->instance->hasDescription(' ' . $category_name));
        $this->assertFalse($this->instance->hasDescription('ЫDfsdgfs dsfDFfds'));
    }
}
