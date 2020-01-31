<?php

namespace AvtoDev\StaticReferences\References;

/**
 * @extends \IteratorAggregate<\AvtoDev\StaticReferences\References\Entities\EntityInterface>
 */
interface ReferenceInterface extends \IteratorAggregate, \Countable, \Illuminate\Contracts\Support\Arrayable
{
    //
}
