<?php

namespace AvtoDev\StaticReferences\Facades;

use AvtoDev\StaticReferences\References\RegistrationActions\RegistrationActions;

class RegistrationActionsFacade extends AbstractFacade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return RegistrationActions::class;
    }
}
