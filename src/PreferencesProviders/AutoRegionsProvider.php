<?php

namespace AvtoDev\StaticReferencesLaravel\PreferencesProviders;

use AvtoDev\StaticReferencesLaravel\References\AutoRegions\AutoRegionsReference;

/**
 * Class AutoRegionsProvider.
 *
 * Провайдер справочника "Регионы субъектов".
 *
 * @see https://ru.wikipedia.org/wiki/Коды_субъектов_Российской_Федерации
 */
class AutoRegionsProvider extends AbstractReferenceProvider
{
    /**
     * {@inheritdoc}
     */
    public function instance()
    {
        return new AutoRegionsReference;
    }

    /**
     * {@inheritdoc}
     */
    public function binds()
    {
        return ['AutoRegions', 'autoRegions', AutoRegionsReference::class];
    }
}
