<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\Tests;

use AvtoDev\StaticReferences\References;

/**
 * @covers \AvtoDev\StaticReferences\ServiceProvider<extended>
 */
class ServiceProviderTest extends AbstractUnitTestCase
{
    /**
     * @return void
     */
    public function testReferencesRegistration(): void
    {
        $abstracts = [
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
}
