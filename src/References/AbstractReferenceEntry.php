<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\References;

use ArrayIterator;

abstract class AbstractReferenceEntry implements ReferenceEntryInterface
{
    use Traits\TransliterateTrait;

    /**
     * {@inheritdoc}
     */
    public function __construct($raw_data = [])
    {
        $this->configure($raw_data);
    }

    /**
     * Get "hidden" property names.
     *
     * @return string[]|array
     */
    public function hiddenPropertiesNames(): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset): bool
    {
        return ! \in_array($offset, (array) $this->hiddenPropertiesNames(), true) && \property_exists($this, $offset);
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
    public function offsetSet($offset, $value): void
    {
        if ($this->offsetExists($offset)) {
            $this->{$offset} = $value;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset): void
    {
        if ($this->offsetExists($offset)) {
            unset($this->{$offset});
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->toArray());
    }

    /**
     * {@inheritdoc}
     */
    public function toJson($options = 0): string
    {
        return (string) \json_encode($this->toArray(), $options);
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(): array
    {
        $result = [];

        $properties_names = \array_diff(\array_keys(\get_object_vars($this)), (array) $this->hiddenPropertiesNames());

        foreach ($properties_names as $property_name) {
            $result[$property_name] = \property_exists($this, $property_name)
                ? $this->{$property_name}
                : null;
        }

        return $result;
    }
}
