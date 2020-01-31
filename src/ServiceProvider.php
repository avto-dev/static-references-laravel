<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences;

use AvtoDev\StaticReferences\References\SubjectCodes;
use AvtoDev\StaticReferences\References\VehicleTypes;
use AvtoDev\StaticReferencesData\StaticReferencesData;
use AvtoDev\StaticReferences\References\VehicleCategories;
use AvtoDev\StaticReferences\References\CadastralDistricts;
use AvtoDev\StaticReferences\References\VehicleFineArticles;
use AvtoDev\StaticReferences\References\VehicleRepairMethods;
use AvtoDev\StaticReferences\References\VehicleRegistrationActions;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Register package services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(CadastralDistricts::class, static function (): CadastralDistricts {
            return new CadastralDistricts(StaticReferencesData::cadastralDistricts());
        });

        $this->app->singleton(SubjectCodes::class, static function (): SubjectCodes {
            return new SubjectCodes(StaticReferencesData::subjectCodes());
        });

        $this->app->singleton(VehicleCategories::class, static function (): VehicleCategories {
            return new VehicleCategories(StaticReferencesData::vehicleCategories());
        });

        $this->app->singleton(VehicleFineArticles::class, static function (): VehicleFineArticles {
            return new VehicleFineArticles(StaticReferencesData::vehicleFineArticles());
        });

        $this->app->singleton(VehicleRegistrationActions::class, static function (): VehicleRegistrationActions {
            return new VehicleRegistrationActions(StaticReferencesData::vehicleRegistrationActions());
        });

        $this->app->singleton(VehicleRepairMethods::class, static function (): VehicleRepairMethods {
            return new VehicleRepairMethods(StaticReferencesData::vehicleRepairMethods());
        });

        $this->app->singleton(VehicleTypes::class, static function (): VehicleTypes {
            return new VehicleTypes(StaticReferencesData::vehicleTypes());
        });
    }
}
