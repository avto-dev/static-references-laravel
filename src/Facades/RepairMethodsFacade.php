<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\Facades;

use AvtoDev\StaticReferences\References\RepairMethods\RepairMethods;

class RepairMethodsFacade extends \Illuminate\Support\Facades\Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return RepairMethods::class;
    }
}
