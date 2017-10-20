<?php

namespace AvtoDev\StaticReferencesLaravel;

/**
 * Interface ReferencesStackInterface.
 */
interface ReferencesStackInterface
{
    /**
     * Возвращает массив классов провайдеров справочников.
     *
     * @return string[]
     */
    public function getProvidersClasses();

    /**
     * Возвращает имя корневого элемента конфига справочников.
     *
     * @return string
     */
    public static function getConfigRootKeyName();

    /**
     * Возвращает массив имен классов, что должны быть инициализированы "по умолчанию".
     *
     * @return string[]
     */
    public function getBasicReferencesClasses();
}
