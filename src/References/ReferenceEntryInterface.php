<?php

namespace AvtoDev\StaticReferencesLaravel\References;

use AvtoDev\StaticReferencesLaravel\Support\Contracts\Configurable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

/**
 * Interface ReferenceEntryInterface.
 */
interface ReferenceEntryInterface extends Configurable, Arrayable, Jsonable
{
    /**
     * ReferenceEntryInterface constructor.
     *
     * @param array $raw_data
     */
    public function __construct($raw_data = []);
}
