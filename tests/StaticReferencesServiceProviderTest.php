<?php

namespace AvtoDev\StaticReferencesLaravel\Tests;

use AvtoDev\StaticReferencesLaravel\StaticReferences;
use AvtoDev\StaticReferencesLaravel\Facades\StaticReferencesFacade;
use AvtoDev\StaticReferencesLaravel\StaticReferencesServiceProvider;
use AvtoDev\StaticReferencesLaravel\References\AutoRegions\AutoRegionsReference;
use AvtoDev\StaticReferencesLaravel\References\AutoCategories\AutoCategoriesReference;
use AvtoDev\StaticReferencesLaravel\References\RegistrationActions\RegistrationActionsReference;

/**
 * Class StaticReferencesServiceProviderTest.
 */
class StaticReferencesServiceProviderTest extends AbstractUnitTestCase
{
    /**
     * Tests service-provider loading.
     *
     * @return void
     */
    public function testServiceProviderLoading()
    {
        $this->assertInstanceOf(StaticReferences::class, $this->app['static-references']);
        $this->assertInstanceOf(StaticReferences::class, $this->app[StaticReferences::class]);
        $this->assertInstanceOf(StaticReferences::class, app('static-references'));
        $this->assertInstanceOf(StaticReferences::class, app(StaticReferences::class));
        $this->assertInstanceOf(StaticReferences::class, resolve(StaticReferences::class));
    }

    /**
     * Test accessible from facade.
     *
     * @return void
     */
    public function testAccessibleFromFacade()
    {
        $this->assertInstanceOf(StaticReferences::class, StaticReferencesFacade::instance());
    }

    /**
     * Test default configs values.
     *
     * @return void
     */
    public function testDefaultConfigValues()
    {
        $config = config(StaticReferencesServiceProvider::getConfigRootKeyName());

        $this->assertTrue(is_array($config['cache']));
        $this->assertTrue($config['cache']['enabled']);
        $this->assertEquals(0, $config['cache']['lifetime']);
        $this->assertNotEmpty($config['cache']['store']);
        $this->assertTrue(is_array($config['providers']));
    }

    /**
     * Тест контейнера статических справочников.
     *
     * @return void
     */
    public function testStaticReferencesContainer()
    {
        /** @var StaticReferences $container */
        $container = app(StaticReferences::class);

        $this->assertInstanceOf(AutoCategoriesReference::class, $a = $container->autoCategories);
        $this->assertInstanceOf(AutoCategoriesReference::class, $b = $container->make('autoCategories'));
        $this->assertEquals($a, $b);

        $this->assertInstanceOf(AutoRegionsReference::class, $a = $container->autoRegions);
        $this->assertInstanceOf(AutoRegionsReference::class, $b = $container->make('autoRegions'));
        $this->assertEquals($a, $b);

        $this->assertInstanceOf(RegistrationActionsReference::class, $a = $container->registrationActions);
        $this->assertInstanceOf(RegistrationActionsReference::class, $b = $container->make('registrationActions'));
        $this->assertEquals($a, $b);
    }
}
