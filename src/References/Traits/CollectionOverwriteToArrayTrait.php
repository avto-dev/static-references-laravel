<?php

namespace AvtoDev\StaticReferences\References\Traits;

use Illuminate\Contracts\Support\Arrayable;

trait CollectionOverwriteToArrayTrait
{
    /**
     * @return array
     */
    public function toArray(): array
    {
        return array_map(function ($value) {
            return $value instanceof Arrayable
                ? $value->toArray()
                : $value;
        }, $this->all());
    }

    /**
     * Get all of the items in the collection.
     *
     * @return array
     */
    abstract public function all();
}
