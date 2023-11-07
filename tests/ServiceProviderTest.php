<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\Tests;

use AvtoDev\StaticReferences\References;
use AvtoDev\StaticReferencesData\StaticReferencesData;

/**
 * @covers \AvtoDev\StaticReferences\ServiceProvider
 */
class ServiceProviderTest extends AbstractUnitTestCase
{
    /**
     * @return void
     */
    public function testReferencesRegistration(): void
    {
        $abstracts = [
            References\CadastralDistricts::class,
            References\SubjectCodes::class,
            References\VehicleCategories::class,
            References\VehicleFineArticles::class,
            References\VehicleRegistrationActions::class,
            References\VehicleRepairMethods::class,
            References\VehicleTypes::class,
        ];

        foreach ($abstracts as $abstract) {
            $this->assertTrue($this->app->bound($abstract));

            /** @var References\ReferenceInterface $first */
            $this->assertInstanceOf($abstract, $first = $this->app->make($abstract));
            $second = $this->app->make($abstract);

            $this->assertSame($first, $second, "{$abstract} bound as not singleton");

            $this->assertGreaterThanOrEqual(1, $first->count());

            foreach ($first as $item) {
                $this->assertInstanceOf(References\Entities\EntityInterface::class, $item);
            }
        }
    }

    /**
     * @return void
     */
    public function testBackwardCompatibleArrayConvert(): void
    {
        $this->assertEquals(
            $this->app->make(References\CadastralDistricts::class)->toArray(),
            StaticReferencesData::cadastralDistricts()->getData(true)
        );

        $this->assertEquals(
            $this->app->make(References\SubjectCodes::class)->toArray(),
            StaticReferencesData::subjectCodes()->getData(true)
        );

        $this->assertEquals(
            $this->app->make(References\VehicleCategories::class)->toArray(),
            StaticReferencesData::vehicleCategories()->getData(true)
        );

        $this->assertEquals(
            $this->app->make(References\VehicleFineArticles::class)->toArray(),
            StaticReferencesData::vehicleFineArticles()->getData(true)
        );

        $this->assertEquals(
            $this->app->make(References\VehicleRegistrationActions::class)->toArray(),
            StaticReferencesData::vehicleRegistrationActions()->getData(true)
        );

        $this->assertEquals(
            $this->app->make(References\VehicleRepairMethods::class)->toArray(),
            StaticReferencesData::vehicleRepairMethods()->getData(true)
        );

        $this->assertEquals(
            $this->app->make(References\VehicleTypes::class)->toArray(),
            StaticReferencesData::vehicleTypes()->getData(true)
        );
    }
}
