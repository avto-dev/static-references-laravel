<?php

namespace AvtoDev\StaticReferences\Facades;

use AvtoDev\StaticReferences\References\RepairMethods\RepairMethods;

/**
 * Class RepairMethodsFacade.
 */
class RepairMethodsFacade extends AbstractFacade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return RepairMethods::class;
    }
}
