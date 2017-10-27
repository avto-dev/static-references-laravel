<?php

namespace AvtoDev\StaticReferencesLaravel\References;

use ArrayIterator;
use AvtoDev\StaticReferencesLaravel\Traits\TransliterateTrait;

/**
 * Class AbstractReferenceEntry.
 *
 * Абстрактный класс сущности справочника.
 *
 * Главная особенность - это хранение данных в protected-свойствах, и их имена - являются ключами доступа (используются
 * при обращении к объекту как к массиву, а так же преобразованию объекта в массив).
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
     * Возвращает массив имен свойств, скрытых для доступа "извне".
     *
     * @return string[]|array
     */
    public function hiddenPropertiesNames()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    abstract public function configure($input = []);

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return ! in_array($offset, (array) $this->hiddenPropertiesNames()) && property_exists($this, $offset);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        return $this->offsetExists($offset)
            ? $this->{$offset}
            : null;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        if ($this->offsetExists($offset)) {
            $this->{$offset} = $value;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        if ($this->offsetExists($offset)) {
            unset($this->{$offset});
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new ArrayIterator($this->toArray());
    }

    /**
     * {@inheritdoc}
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        $result = [];

        $properties_names = array_diff(array_keys(get_object_vars($this)), (array) $this->hiddenPropertiesNames());

        foreach ($properties_names as $property_name) {
            $result[$property_name] = property_exists($this, $property_name)
                ? $this->{$property_name}
                : null;
        }

        return $result;
    }
}
