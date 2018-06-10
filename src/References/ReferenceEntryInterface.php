<?php

namespace AvtoDev\StaticReferences\References;

use ArrayAccess;
use IteratorAggregate;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Arrayable;

interface ReferenceEntryInterface extends ArrayAccess, IteratorAggregate, Arrayable, Jsonable
{
    /**
     * ReferenceEntryInterface constructor.
     *
     * @param array $raw_data
     */
    public function __construct($raw_data = []);

    /**
     * Выполняет собственную конфигурации в зависимости от входящих данных.
     *
     * @param array|mixed $input
     *
     * @return void
     */
    public function configure($input = []);
}
