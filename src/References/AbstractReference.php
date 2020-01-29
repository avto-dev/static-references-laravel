<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\References;

use Exception;
use Illuminate\Support\Collection;
use AvtoDev\StaticReferences\References\Traits\CollectionOverwriteToArrayTrait;

abstract class AbstractReference extends Collection implements ReferenceInterface
{
    use CollectionOverwriteToArrayTrait, Traits\TransliterateTrait;

    /**
     * @var ReferenceEntryInterface[]|array
     */
    protected $items = [];

    /**
     * {@inheritdoc}
     *
     * @throws Exception
     */
    public function create()
    {
        $this->items = \array_map(static function ($item_data) {
            return $this->referenceEntityFactory($item_data);
        }, \array_filter(static::getVendorStaticReferenceInstance()->getContent()));
    }

    /**
     * {@inheritdoc}
     *
     * @throws Exception
     */
    public function offsetSet($key, $value): void
    {
        if ($key === null) {
            $this->items[] = $this->referenceEntityFactory($value);
        } else {
            $this->items[$key] = $this->referenceEntityFactory($value);
        }
    }

    /**
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
