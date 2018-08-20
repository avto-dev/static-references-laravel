<?php

namespace AvtoDev\StaticReferences\Facades;

use AvtoDev\StaticReferences\References\AutoFines\AutoFines;

class AutoFinesFacade extends AbstractFacade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return AutoFines::class;
    }
}
