<?php

namespace AvtoDev\StaticReferencesLaravel\PreferencesProviders;

use AvtoDev\StaticReferencesLaravel\References\AutoCategories\AutoCategoriesReference;

/**
 * Class AutoCategoriesProvider.
 *
 * Провайдер справочника "Категории транспортных средств".
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
        return ['AutoCategories', 'autoCategories', AutoCategoriesReference::class];
    }
}
