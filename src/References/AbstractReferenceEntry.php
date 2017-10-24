<?php

namespace AvtoDev\StaticReferencesLaravel\References;

use AvtoDev\StaticReferencesLaravel\Traits\TransliterateTrait;

/**
 * Class AbstractReferenceEntry.
 *
 * Абстрактный класс сущности справочника.
 */
abstract class AbstractReferenceEntry implements ReferenceEntryInterface
{
    use TransliterateTrait;

    /**
     * {@inheritdoc}
     */
    public function __construct($raw_data = [])
    {
        $this->configure($raw_data);
    }

    /**
     * {@inheritdoc}
     */
    abstract public function configure($input = []);

    /**
     * {@inheritdoc}
     */
    abstract public function toArray();

    /**
     * {@inheritdoc}
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }
}
