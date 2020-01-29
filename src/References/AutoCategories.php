<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\References;

use Generator;
use AvtoDev\StaticReferences\References\Entities\AutoCategory;
use AvtoDev\StaticReferencesData\ReferencesData\StaticReference;

class AutoCategories implements ReferenceInterface
{
    /**
     * @var array<string, AutoCategory>
     */
    protected $entities = [];

    /**
     * Create a new reference instance.
     *
     * @param StaticReference $static_reference
     *
     * @see \AvtoDev\StaticReferencesData\StaticReferencesData::getAutoCategories()
     */
    public function __construct(StaticReference $static_reference)
    {
        foreach ((array) $static_reference->getData(true) as $datum) {
            $this->entities[$datum['code']] = new AutoCategory($datum['code'], $datum['description']);
        }
    }

    /**
     * @return Generator<AutoCategory>|AutoCategory[]
     */
    public function getIterator(): Generator
    {
        foreach ($this->entities as $item) {
            yield $item;
        }
    }

    /**
     * @return array[]
     */
    public function toArray(): array
    {
        return \array_map(static function (AutoCategory $e) {
            return $e->toArray();
        }, $this->entities);
    }

    /**
     * Get category entry by code.
     *
     * @param string $code
     *
     * @return AutoCategory|null
     */
    public function getByCode(string $code): ?AutoCategory
    {
        return $this->entities[$code] ?? null;
    }

    /**
     * Check for category is exists in reference?
     *
     * @param string $code
     *
     * @return bool
     */
    public function hasCode(string $code): bool
    {
        return isset($this->entities[$code]);
    }

    /**
     * {@inheritdoc}
     */
    public function count(): int
    {
        return \count($this->entities);
    }
}
