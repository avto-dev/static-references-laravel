<?php

namespace AvtoDev\StaticReferencesLaravel;

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
     * Алиас для извлечения инстанов справочников при обращении к ним как к public-свойствам.
     *
     * @param string $name
     *
     * @return ReferenceInterface
     */
    public function __get($name);

    /**
     * Возвращает инстанс справочника из стека по его бинд-алиасу, создавая и кэшируя его инстанс при необходимости.
     *
     * @param string $bind_name
     *
     * @throws InvalidReferenceException
     *
     * @return ReferenceInterface
     */
    public function make($bind_name);
}
