<?php

namespace AvtoDev\StaticReferencesLaravel\References;

use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Arrayable;
use AvtoDev\StaticReferencesLaravel\Support\Contracts\Configurable;

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
