<?php

namespace AvtoDev\StaticReferencesLaravel\References;

use Exception;
use Illuminate\Support\Collection;
use AvtoDev\StaticReferencesLaravel\Traits\TransliterateTrait;

/**
 * Class AbstractReference.
 *
 * Абстрактный класс единичного справочника.
 */
abstract class AbstractReference extends Collection implements ReferenceInterface
{
    use TransliterateTrait;

    /**
     * Стек для хранения данных справочника в сыром виде.
     *
     * @var ReferenceEntryInterface[]|array
     */
    protected $items = [];

    /**
     * {@inheritdoc}
     */
    public function __construct($items = [])
    {
        return parent::__construct(empty($items)
            ? $this->getStaticEntries()
            : $items);
    }

    /**
     * {@inheritdoc}
     */
    abstract public function getReferenceEntryClassName();

    /**
     * {@inheritdoc}
     */
    public function offsetSet($key, $value)
    {
        if (is_null($key)) {
            array_push($this->items, $this->referenceEntityFactory($value));
        } else {
            $this->items[$key] = $this->referenceEntityFactory($value);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function map(callable $callback)
    {
        $result = parent::map($callback);

        return $result->contains(function ($item) {
            return ! $item instanceof static;
        })
            ? $result->toBase()
            : $result;
    }

    /**
     * {@inheritdoc}
     */
    public function pluck($value, $key = null)
    {
        return $this->toBase()->pluck($value, $key);
    }

    /**
     * {@inheritdoc}
     */
    public function keys()
    {
        return $this->toBase()->keys();
    }

    /**
     * {@inheritdoc}
     */
    public function zip($items)
    {
        return call_user_func_array([$this->toBase(), 'zip'], func_get_args());
    }

    /**
     * {@inheritdoc}
     */
    public function collapse()
    {
        return $this->toBase()->collapse();
    }

    /**
     * {@inheritdoc}
     */
    public function flatten($depth = INF)
    {
        return $this->toBase()->flatten($depth);
    }

    /**
     * {@inheritdoc}
     */
    public function flip()
    {
        return $this->toBase()->flip();
    }

    /**
     * {@inheritdoc}
     */
    public function pad($size, $value)
    {
        return $this->toBase()->pad($size, $value);
    }

    /**
     * Возвращает массив объектов, прочитав данные о них из статических файлов данных.
     *
     * @return ReferenceEntryInterface[]|array
     */
    protected function getStaticEntries()
    {
        // Преобразуем элементы к объектам элемента справочника
        return array_map(function ($item_data) {
            return $this->referenceEntityFactory($item_data);
        }, array_filter($this->getRawSourceData()));
    }

    /**
     * Возвращает массив сырых статических данных справочника справочника.
     *
     * @return array[]|array
     */
    abstract protected function getRawSourceData();

    /**
     * Факторка по созданию инстансов элементов справочника.
     *
     * @param array ...$arguments
     *
     * @throws Exception
     *
     * @return ReferenceEntryInterface
     */
    protected function referenceEntityFactory(...$arguments)
    {
        $class_name = $this->getReferenceEntryClassName();

        if (class_exists($class_name)) {
            return new $class_name(...$arguments);
        }

        throw new Exception(sprintf('Class "%s" in "%s" does not exists', $class_name, static::class));
    }
}
