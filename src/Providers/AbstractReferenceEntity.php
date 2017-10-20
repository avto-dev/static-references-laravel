<?php

namespace AvtoDev\StaticReferencesLaravel\Providers;

/**
 * Class AbstractReferenceEntity.
 *
 * Абстрактный класс сущности справочника.
 */
abstract class AbstractReferenceEntity implements ReferenceEntityInterface
{
    /**
     * AbstractReferenceEntity constructor.
     *
     * @param array $raw_data
     */
    public function __construct(array $raw_data = [])
    {
        $this->configure($raw_data);
    }

    /**
     * {@inheritdoc}
     */
    abstract public function configure($input = []);
}
