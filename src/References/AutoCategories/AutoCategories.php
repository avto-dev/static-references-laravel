<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\References\AutoCategories;

use Generator;
use AvtoDev\StaticReferences\References\ReferenceInterface;
use AvtoDev\StaticReferencesData\ReferencesData\StaticReference;

class AutoCategories implements ReferenceInterface
{
    /**
     * @var array<string, AutoCategoryEntry>
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
            $this->entities[$datum['code']] = new AutoCategoryEntry($datum['code'], $datum['description'] ?? null);
        }
    }

    /**
     * @return Generator<AutoCategoryEntry>|AutoCategoryEntry[]
     */
    public function getIterator(): Generator
    {
        foreach ($this->entities as $item) {
            yield $item;
        }
    }

    /**
     * @return AutoCategoryEntry[]
     */
    public function all(): array
    {
        return \array_values($this->entities);
    }

    /**
     * Get category entry by code.
     *
     * @param string $code
     *
     * @return AutoCategoryEntry|null
     */
    public function getByCode(string $code): ?AutoCategoryEntry
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
