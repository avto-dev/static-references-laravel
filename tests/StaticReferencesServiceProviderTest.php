<?php

namespace AvtoDev\StaticReferences\Tests;

use AvtoDev\StaticReferences\References\AutoRegions\AutoRegions;
use AvtoDev\StaticReferences\References\RepairMethods\RepairMethods;
use AvtoDev\StaticReferences\References\AutoCategories\AutoCategories;
use AvtoDev\StaticReferences\References\RegistrationActions\RegistrationActions;

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
        $this->assertInstanceOf(AutoRegions::class, $this->app[AutoRegions::class]);
        $this->assertInstanceOf(AutoRegions::class, app(AutoRegions::class));

        $this->assertInstanceOf(AutoCategories::class, $this->app[AutoCategories::class]);
        $this->assertInstanceOf(AutoCategories::class, app(AutoCategories::class));

        $this->assertInstanceOf(RegistrationActions::class, $this->app[RegistrationActions::class]);
        $this->assertInstanceOf(RegistrationActions::class, app(RegistrationActions::class));

        $this->assertInstanceOf(RepairMethods::class, $this->app[RepairMethods::class]);
        $this->assertInstanceOf(RepairMethods::class, app(RepairMethods::class));
    }

    /**
     * Test instance resolving from cache.
     *
     * Checking allowed by coverage.
     *
     * @return void
     */
    public function testResolvingInstanceFromCache()
    {
        $this->assertInstanceOf(AutoRegions::class, $this->app[AutoRegions::class]);

        $this->refreshApplication();

        $this->assertInstanceOf(AutoRegions::class, $this->app[AutoRegions::class]);
    }
}
