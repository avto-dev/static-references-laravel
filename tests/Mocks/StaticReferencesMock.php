<?php

namespace AvtoDev\StaticReferencesLaravel\Tests\Mocks;

use AvtoDev\StaticReferencesLaravel\StaticReferences;
use AvtoDev\StaticReferencesLaravel\References\ReferenceInterface;
use AvtoDev\StaticReferencesLaravel\PreferencesProviders\ReferenceProviderInterface;

/**
 * Class StaticReferencesMock.
 */
class StaticReferencesMock extends StaticReferences
{
    /**
     * @return array|ReferenceProviderInterface[]
     */
    public function &_providers()
    {
        return $this->providers;
    }

    /**
     * @return array|ReferenceProviderInterface[]
     */
    public function &_binds_map()
    {
        return $this->binds_map;
    }

    /**
     * @return array|ReferenceInterface[]
     */
    public function &_references()
    {
        return $this->references;
    }

    /**
     * @return array
     */
    public function &_config()
    {
        return $this->config;
    }
}
