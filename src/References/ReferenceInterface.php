<?php

namespace AvtoDev\StaticReferences\References;

interface ReferenceInterface extends \IteratorAggregate, \Countable
{
    /**
     * Get all of the entries in reference.
     *
     * @return array<ReferenceEntryInterface|mixed>
     */
    public function all(): array;
}
