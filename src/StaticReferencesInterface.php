<?php

namespace AvtoDev\StaticReferencesLaravel;

use ArrayAccess;
use AvtoDev\StaticReferencesLaravel\References\ReferenceInterface;
use AvtoDev\StaticReferencesLaravel\Exceptions\InvalidReferenceException;

/**
 * Interface StaticReferencesInterface.
 */
interface StaticReferencesInterface
{
    /**
     * StaticReferences constructor.
     *
     * @param array $config
     *
     * @throws InvalidReferenceException
     */
    public function __construct(array $config = []);

    /**
     * Возвращает инстанс справочника из стека по его бинд-алиасу, создавая и кэшируя его инстанс при необходимости.
     *
     * @param string $bind_name
     *
     * @return ReferenceInterface
     * @throws InvalidReferenceException
     */
    public function make($bind_name);

    /**
     * Алиас для извлечения инстанов справочников при обращении к ним как к public-свойствам.
     *
     * @param string $name
     *
     * @return ReferenceInterface
     */
    public function __get($name);
}
