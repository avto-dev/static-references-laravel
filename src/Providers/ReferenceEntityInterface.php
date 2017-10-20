<?php

namespace AvtoDev\StaticReferencesLaravel\Providers;

use AvtoDev\StaticReferencesLaravel\Support\Contracts\Configurable;

/**
 * Interface ReferenceEntityInterface.
 */
interface ReferenceEntityInterface extends Configurable
{
    /**
     * ReferenceEntityInterface constructor.
     *
     * @param array $raw_data
     */
    public function __construct(array $raw_data = []);
}
