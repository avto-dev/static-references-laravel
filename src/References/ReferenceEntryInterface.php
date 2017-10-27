<?php

namespace AvtoDev\StaticReferencesLaravel\References;

use ArrayAccess;
use IteratorAggregate;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Arrayable;
use AvtoDev\StaticReferencesLaravel\Support\Contracts\Configurable;

/**
 * Interface ReferenceEntryInterface.
 */
interface ReferenceEntryInterface extends Configurable, ArrayAccess, IteratorAggregate, Arrayable, Jsonable
{
    /**
     * ReferenceEntryInterface constructor.
     *
     * @param array $raw_data
     */
    public function __construct($raw_data = []);
}
