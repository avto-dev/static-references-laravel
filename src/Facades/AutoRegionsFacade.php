<?php

namespace AvtoDev\StaticReferences\Facades;

use AvtoDev\StaticReferences\References\AutoRegions\AutoRegions;

class AutoRegionsFacade extends AbstractFacade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return AutoRegions::class;
    }
}
