<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\References;

use Generator;
use AvtoDev\StaticReferences\References\Entities\VehicleType;
use AvtoDev\StaticReferencesData\ReferencesData\StaticReferenceInterface;

class VehicleTypes implements ReferenceInterface
{
    /**
     * Key is type code.
     *
     * @var array<int, VehicleType>
     */
    protected $entities = [];

    /**
     * Create a new reference instance.
     *
     * @param StaticReferenceInterface $static_reference
     *
     * @see \AvtoDev\StaticReferencesData\StaticReferencesData::vehicleTypes()
     */
    public function __construct(StaticReferenceInterface $static_reference)
    {
        foreach ((array) $static_reference->getData(true) as $datum) {
            /** @var int $code */
            $code = $datum['code'];

            $this->entities[$code] = new VehicleType(
                $datum['code'], $datum['title'], $datum['group_title'], $datum['group_slug'],
            );
        }
    }

    /**
     * @return Generator<VehicleType>
     */
    public function getIterator(): Generator
    {
        foreach ($this->entities as $item) {
            yield $item;
        }
    }

    /**
     * @return array<array-key, array<string, int|string>>
     */
    public function toArray(): array
    {
        return \array_map(static function (VehicleType $e): array {
            return $e->toArray();
        }, \array_values($this->entities));
    }

    /**
     * Get vehicle type entry by code.
     *
     * @param int $code
     *
     * @return VehicleType|null
     */
    public function getByCode(int $code): ?VehicleType
    {
        return $this->entities[$code] ?? null;
    }

    /**
     * Check for vehicle type with passed code is exists in reference?
     *
     * @param int $code
     *
     * @return bool
     */
    public function hasCode(int $code): bool
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
