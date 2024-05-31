<?php

namespace AvtoDev\StaticReferences\References;

use Illuminate\Contracts\Support\Arrayable;

/**
 * @extends \IteratorAggregate<\AvtoDev\StaticReferences\References\Entities\EntityInterface>
 * @extends Arrayable<string, mixed>
 */
interface ReferenceInterface extends \IteratorAggregate, \Countable, \Illuminate\Contracts\Support\Arrayable
{
    //
}
