<?php

namespace AvtoDev\StaticReferencesLaravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class StaticReferencesFacade.
 */
class StaticReferencesFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'static-references';
    }
}
