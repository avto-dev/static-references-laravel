<?php

namespace AvtoDev\StaticReferences\Facades;

use AvtoDev\StaticReferences\References\AutoCategories\AutoCategories;

/**
 * Class RepositoryFacade.
 */
class AutoCategoriesFacade extends AbstractFacade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return AutoCategories::class;
    }
}
