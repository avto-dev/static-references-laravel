<?php

namespace AvtoDev\StaticReferencesLaravel;

/**
 * Class StaticReferences.
 */
class StaticReferences extends AbstractReferencesStack
{
    /**
     * Возвращает имя корневого элемента конфига справочников.
     *
     * @return string
     */
    public static function getConfigRootKeyName()
    {
        return 'static-references';
    }

    /**
     * {@inheritdoc}
     */
    public function getBasicReferencesClasses()
    {
        return [];
    }
}
