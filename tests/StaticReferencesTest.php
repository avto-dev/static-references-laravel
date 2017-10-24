<?php

namespace AvtoDev\StaticReferencesLaravel\Tests;

use AvtoDev\StaticReferencesLaravel\PreferencesProviders\AutoCategoriesProvider;
use AvtoDev\StaticReferencesLaravel\StaticReferences;
use AvtoDev\StaticReferencesLaravel\StaticReferencesInterface;
use AvtoDev\StaticReferencesLaravel\Tests\Mocks\StaticReferencesMock;

/**
 * Class StaticReferencesTest.
 */
class StaticReferencesTest extends AbstractUnitTestCase
{
    /**
     * @var StaticReferencesMock
     */
    protected $instance;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->instance = new StaticReferencesMock();
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
     * Тест доступности справочников.
     *
     * @return void
     */
    public function testDeferredProviderWorks()
    {
        // Из коробки у нас должен быть хоть один провайдер
        $this->assertGreaterThanOrEqual(1, $this->instance->_config()['providers']);

        // И этот провайдер - провайдер категорий авто
        $auto_categories = new AutoCategoriesProvider();
        $last_bind = null;
        // Класс сервис-провайдера должен сам забиндиться
        foreach (array_merge($auto_categories->binds(), [AutoCategoriesProvider::class]) as $bind) {
            // Убеждаемся что бинды - есть
            $this->assertArrayHasKey($bind, $this->instance->_binds_map());
            // И они есть в карте биндов, и соответствуют ожиданиям
            $this->assertInstanceOf(AutoCategoriesProvider::class, $this->instance->_binds_map()[$bind]);
            $last_bind = $bind;
        }

        // Пока не вызвали make() - инстансы справочников пустые
        $this->assertEmpty($this->instance->_references());

        // После этого делаем make(), и убеждаемся что прилетел корректный инстанс самого справочника
        $this->assertInstanceOf(get_class($auto_categories->instance()), $ref = $this->instance->make($last_bind));
        // И создался его инстанс в стеке уже справочников
        $this->assertCount(1, $this->instance->_references());

        // После этого делаем make() по имени провайдера справочника, и должен вернуться тот-же объект
        $this->assertEquals($ref, $this->instance->make(AutoCategoriesProvider::class));
        $this->assertCount(1, $this->instance->_references());

        dump($this->instance);
    }
}
