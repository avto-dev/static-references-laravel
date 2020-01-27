<?php

namespace AvtoDev\StaticReferences\References;

use ArrayAccess;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use IteratorAggregate;

interface ReferenceEntryInterface extends ArrayAccess, IteratorAggregate, Arrayable, Jsonable
{
    /**
     * Create a new reference entry instance.
     *
     * @param array $raw_data
     */
    public function __construct($raw_data = []);

    /**
     * Configure self using passed array.
     *
     * @param array|mixed $input
     *
     * @return void
     */
    public function configure($input = []): void;
}
