<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\Facades;

use AvtoDev\StaticReferences\References\CadastralDistricts\CadastralRegions;

class CadastralRegionsFacade extends \Illuminate\Support\Facades\Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return CadastralRegions::class;
    }
}
