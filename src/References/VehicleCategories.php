<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\References;

use Generator;
use AvtoDev\StaticReferences\References\Entities\VehicleCategory;
use AvtoDev\StaticReferencesData\ReferencesData\StaticReferenceInterface;

class VehicleCategories implements ReferenceInterface
{
    /**
     * @var array<string, VehicleCategory>
     */
    protected $entities = [];

    /**
     * Create a new reference instance.
     *
     * @param StaticReferenceInterface $static_reference
     *
     * @see \AvtoDev\StaticReferencesData\StaticReferencesData::vehicleCategories()
     */
    public function __construct(StaticReferenceInterface $static_reference)
    {
        foreach ((array) $static_reference->getData(true) as $datum) {
            $this->entities[$datum['code']] = new VehicleCategory($datum['code'], $datum['description']);
        }
    }

    /**
     * @return Generator<VehicleCategory>|VehicleCategory[]
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
        return \array_map(static function (VehicleCategory $e): array {
            return $e->toArray();
        }, \array_values($this->entities));
    }

    /**
     * Get category entry by code.
     *
     * @param string $code
     *
     * @return VehicleCategory|null
     */
    public function getByCode(string $code): ?VehicleCategory
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
