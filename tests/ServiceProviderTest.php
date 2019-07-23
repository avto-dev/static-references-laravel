<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\Tests;

use AvtoDev\StaticReferences\Facades\AutoFinesFacade;
use AvtoDev\StaticReferences\Facades\AutoRegionsFacade;
use AvtoDev\StaticReferences\Facades\RepairMethodsFacade;
use AvtoDev\StaticReferences\Facades\AutoCategoriesFacade;
use AvtoDev\StaticReferences\Facades\CadastralRegionsFacade;
use AvtoDev\StaticReferences\References\AutoFines\AutoFines;
use AvtoDev\StaticReferences\Facades\RegistrationActionsFacade;
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
     * Tests service-provider loading.
     *
     * @covers \AvtoDev\StaticReferences\Facades\AutoCategoriesFacade
     * @covers \AvtoDev\StaticReferences\Facades\AutoFinesFacade
     * @covers \AvtoDev\StaticReferences\Facades\AutoRegionsFacade
     * @covers \AvtoDev\StaticReferences\Facades\RegistrationActionsFacade
     * @covers \AvtoDev\StaticReferences\Facades\RepairMethodsFacade
     *
     * @return void
     */
    public function testServiceProviderLoading(): void
    {
        $this->assertInstanceOf(AutoRegions::class, $this->app[AutoRegions::class]);
        $this->assertInstanceOf(AutoRegions::class, app(AutoRegions::class));
        $this->assertInstanceOf(AutoRegions::class, AutoRegionsFacade::getFacadeRoot());

        $this->assertInstanceOf(AutoCategories::class, $this->app[AutoCategories::class]);
        $this->assertInstanceOf(AutoCategories::class, app(AutoCategories::class));
        $this->assertInstanceOf(AutoCategories::class, AutoCategoriesFacade::getFacadeRoot());

        $this->assertInstanceOf(RegistrationActions::class, $this->app[RegistrationActions::class]);
        $this->assertInstanceOf(RegistrationActions::class, app(RegistrationActions::class));
        $this->assertInstanceOf(RegistrationActions::class, RegistrationActionsFacade::getFacadeRoot());

        $this->assertInstanceOf(RepairMethods::class, $this->app[RepairMethods::class]);
        $this->assertInstanceOf(RepairMethods::class, app(RepairMethods::class));
        $this->assertInstanceOf(RepairMethods::class, RepairMethodsFacade::getFacadeRoot());

        $this->assertInstanceOf(AutoFines::class, $this->app[AutoFines::class]);
        $this->assertInstanceOf(AutoFines::class, app(AutoFines::class));
        $this->assertInstanceOf(AutoFines::class, AutoFinesFacade::getFacadeRoot());

        $this->assertInstanceOf(CadastralRegions::class, $this->app[CadastralRegions::class]);
        $this->assertInstanceOf(CadastralRegions::class, app(CadastralRegions::class));
        $this->assertInstanceOf(CadastralRegions::class, CadastralRegionsFacade::getFacadeRoot());
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
