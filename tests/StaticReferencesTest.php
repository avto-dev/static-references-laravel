<?php

namespace AvtoDev\StaticReferencesLaravel\Tests;

use AvtoDev\StaticReferencesLaravel\Exceptions\InvalidReferenceException;
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
     * Базовый тест работы стека провайдеров + извлечения инстансов справочников.
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
        $binds = array_merge([AutoCategoriesProvider::class], $auto_categories->binds());
        // Класс сервис-провайдера должен сам забиндиться
        foreach ($binds as $bind) {
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
        foreach ($binds as $bind) {
            $this->assertEquals($ref, $this->instance->$bind); // magic-вызов
        }
        $this->assertCount(1, $this->instance->_references());
    }

    /**
     * Тест извлечения инстанса из кэша. Больше для покрытия.
     *
     * @depends testDeferredProviderWorks
     */
    public function testMakeFromCache()
    {
        $auto_categories = new AutoCategoriesProvider();

        // Должен прилететь из кэша. Чекать по покрытию
        $this->assertInstanceOf(
            get_class($auto_categories->instance()),
            $this->instance->make($auto_categories->binds()[0])
        );
    }

    /**
     * Тест помещения к кэш с указанным временным интервалом.
     */
    public function testPutIntoCacheWithLifetime()
    {
        $this->clearCache();
        $this->instance = new StaticReferencesMock([
            'cache' => [
                'enabled'  => true,
                'lifetime' => 10,
            ],
        ]);

        $auto_categories = new AutoCategoriesProvider();

        // Должен прилететь из кэша. Чекать по покрытию
        $this->assertInstanceOf(
            get_class($auto_categories->instance()),
            $this->instance->make($auto_categories->binds()[0])
        );
    }

    /**
     * Тест бросания исключения при не корректном извлечении (отсутствие бинда).
     */
    public function testInvalidBindNameCall()
    {
        $this->expectException(InvalidReferenceException::class);

        $this->instance->make('bla bla');
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->instance = new StaticReferencesMock([
            'cache' => [
                'enabled' => true,
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        unset($this->instance);

        parent::tearDown();
    }
}
