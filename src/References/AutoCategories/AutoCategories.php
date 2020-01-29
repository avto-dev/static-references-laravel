<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\References\AutoCategories;

use Generator;
use InvalidArgumentException;
use Tarampampam\Wrappers\Json;
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
     * @throws InvalidArgumentException
     *
     * @see \AvtoDev\StaticReferencesData\StaticReferencesData::getAutoCategories()
     */
    public function __construct(StaticReference $static_reference)
    {
        foreach ((array) $static_reference->getData(true) as $datum) {
            if (! $this->validateRawEntry($datum)) {
                throw new InvalidArgumentException('Wrong reference element passed: ' . Json::encode($datum));
            }

            $this->entities[$datum['code']] = new AutoCategoryEntry($datum['code'], $datum['description'] ?? null);
        }
    }

    /**
     * Validate raw data entry.
     *
     * @param mixed $entry
     *
     * @return bool
     */
    protected function validateRawEntry($entry): bool
    {
        return \is_array($entry) && \array_key_exists('code', $entry) && \is_string($entry['code']);
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
     * {@inheritDoc}
     */
    public function count(): int
    {
        return \count($this->entities);
    }
}
