<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\Tests;

use AvtoDev\StaticReferences\References\AutoFines\AutoFines;
use AvtoDev\StaticReferences\References\AutoRegions\AutoRegions;
use AvtoDev\StaticReferences\References\RepairMethods\RepairMethods;
use AvtoDev\StaticReferences\References\AutoCategories\AutoCategories;
use AvtoDev\StaticReferences\References\CadastralDistricts\CadastralRegions;
use AvtoDev\StaticReferences\References\RegistrationActions\RegistrationActions;

/**
 * @covers \AvtoDev\StaticReferences\ServiceProvider<extended>
 */
class ServiceProviderTest extends AbstractUnitTestCase
{
    /**
     * @return void
     */
    public function testServiceProviderLoading(): void
    {
        $this->assertInstanceOf(AutoRegions::class, $this->app[AutoRegions::class]);
        $this->assertInstanceOf(AutoRegions::class, app(AutoRegions::class));

        $this->assertInstanceOf(AutoCategories::class, $this->app[AutoCategories::class]);
        $this->assertInstanceOf(AutoCategories::class, app(AutoCategories::class));

        $this->assertInstanceOf(RegistrationActions::class, $this->app[RegistrationActions::class]);
        $this->assertInstanceOf(RegistrationActions::class, app(RegistrationActions::class));

        $this->assertInstanceOf(RepairMethods::class, $this->app[RepairMethods::class]);
        $this->assertInstanceOf(RepairMethods::class, app(RepairMethods::class));

        $this->assertInstanceOf(AutoFines::class, $this->app[AutoFines::class]);
        $this->assertInstanceOf(AutoFines::class, app(AutoFines::class));

        $this->assertInstanceOf(CadastralRegions::class, $this->app[CadastralRegions::class]);
        $this->assertInstanceOf(CadastralRegions::class, app(CadastralRegions::class));
    }

    /**
     * Test instance resolving from cache.
     *
     * Checking allowed by coverage.
     *
     * @return void
     */
    public function testResolvingInstanceFromCache(): void
    {
        $this->assertInstanceOf(AutoRegions::class, $this->app[AutoRegions::class]);

        $this->clearCache();
        $this->refreshApplication();

        $this->assertInstanceOf(AutoRegions::class, $this->app[AutoRegions::class]);
    }
}
