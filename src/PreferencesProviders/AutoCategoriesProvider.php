<?php

namespace AvtoDev\StaticReferencesLaravel\PreferencesProviders;

use AvtoDev\StaticReferencesLaravel\References\AutoCategories\AutoCategoriesReference;

/**
 * Class AutoCategoriesProvider.
 */
class AutoCategoriesProvider extends AbstractReferenceProvider
{
    /**
     * {@inheritdoc}
     */
    public function instance()
    {
        return new AutoCategoriesReference;
    }

    /**
     * {@inheritdoc}
     */
    public function binds()
    {
        return ['AutoCategories', AutoCategoriesReference::class];
    }
}
