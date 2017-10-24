<?php

namespace AvtoDev\StaticReferencesLaravel\PreferencesProviders;

use AvtoDev\StaticReferencesLaravel\StaticReferencesInterface;

/**
 * Class AbstractReference.
 *
 * Абстрактный класс провайдеров справочников.
 */
abstract class AbstractReferenceProvider implements ReferenceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function isDeferred()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function boot(StaticReferencesInterface $static_references)
    {
        //
    }

    /**
     * {@inheritdoc}
     */
    abstract public function instance();

    /**
     * {@inheritdoc}
     */
    abstract public function binds();
}
