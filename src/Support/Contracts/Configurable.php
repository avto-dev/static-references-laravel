<?php

namespace AvtoDev\StaticReferencesLaravel\Support\Contracts;

/**
 * Interface Configurable.
 *
 * Контракт, говорящий о том, то объект его реализующий умеет себя конфигурировать.
 */
interface Configurable
{
    /**
     * Выполняет собственную конфигурации в зависимости от входящих данных.
     *
     * @param array|mixed $input
     *
     * @return void
     */
    public function configure($input = []);
}
