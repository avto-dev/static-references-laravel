<?php

namespace AvtoDev\StaticReferencesLaravel\References;

use AvtoDev\StaticReferencesLaravel\Traits\TransliterateTrait;
use ArrayIterator;

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
    public function offsetExists($offset)
    {
        return !in_array($offset, $this->hiddenPropertiesNames()) && property_exists($this, $offset);
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

        // Пробегаемся по всем свойствам объекта, за исключением скрытых
        foreach (array_diff(get_object_vars($this), $this->hiddenPropertiesNames()) as $property_name => $property_value) {
            $result[$property_name] = $property_value;
        }

        return $result;
    }
}
