<?php

namespace AvtoDev\StaticReferencesLaravel\Tests;

use AvtoDev\StaticReferencesLaravel\StaticReferences;
use AvtoDev\StaticReferencesLaravel\StaticReferencesInterface;
use AvtoDev\StaticReferencesLaravel\Providers\AutoCategories\AutoCategoriesProvider;

/**
 * Class StaticReferencesTest.
 */
class StaticReferencesTest extends AbstractUnitTestCase
{
    /**
     * @var StaticReferences
     */
    protected $instance;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->instance = new StaticReferences($this->app);
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
     * Тест реализации.
     *
     * @return void
     */
    public function testImplementations()
    {
        $this->assertInstanceOf(StaticReferencesInterface::class, $this->instance);
    }

    /**
     * Тест методов, прилетевших из трейтов.
     *
     * @return void
     */
    public function testTraits()
    {
        /*
         * @see InstanceableTrait
         */
        $this->assertInstanceOf(StaticReferences::class, $this->instance->instance());
    }

    /**
     * Тест публичных констант.
     *
     * @return void
     */
    public function testConstants()
    {
        $this->assertNotEmpty(StaticReferences::CACHE_KEY_PREFIX);
    }

    /**
     * Тест метода `getPackageProvidersClasses()`.
     *
     * @return void
     */
    public function testGetBasicReferencesClasses()
    {
        $this->assertTrue(is_array($this->instance->getPackageProvidersClasses()));
    }

    /**
     * Тест доступности справочников.
     *
     * @return void
     */
    public function testReferencesAccess()
    {
        /*
         * Тест доступности справочника "Категории ТС".
         */
        $this->assertInstanceOf(
            AutoCategoriesProvider::class,
            $this->instance->getByClass(AutoCategoriesProvider::class)
        );
        $this->assertInstanceOf(
            AutoCategoriesProvider::class,
            $this->instance->getByName('autoCategories')
        );
        $this->assertInstanceOf(
            AutoCategoriesProvider::class,
            $this->instance->autoCategories
        );
    }
}
