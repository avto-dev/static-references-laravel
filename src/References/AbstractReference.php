<?php

namespace AvtoDev\StaticReferences\References;

use Exception;
use Illuminate\Support\Collection;

/**
 * Абстрактный класс справочника.
 */
abstract class AbstractReference extends Collection implements ReferenceInterface
{
    use Traits\TransliterateTrait;

    /**
     * Стек для хранения данных справочника в сыром виде.
     *
     * @var ReferenceEntryInterface[]|array
     */
    protected $items = [];

    /**
     * {@inheritdoc}
     *
     * @throws Exception
     */
    public function __construct()
    {
        parent::__construct(array_map(function ($item_data) {
            return $this->referenceEntityFactory($item_data);
        }, array_filter(static::getVendorStaticReferenceInstance()->getContent())));
    }

    /**
     * {@inheritdoc}
     */
    abstract public function getReferenceEntryClassName();

    /**
     * {@inheritdoc}
     *
     * @throws Exception
     */
    public function offsetSet($key, $value)
    {
        if ($key === null) {
            $this->items[] = $this->referenceEntityFactory($value);
        } else {
            $this->items[$key] = $this->referenceEntityFactory($value);
        }
    }

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

        return new $class_name(...$arguments);
    }
}
