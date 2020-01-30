<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\References;

use Generator;
use AvtoDev\StaticReferences\References\Entities\VehicleRepairMethod;
use AvtoDev\StaticReferencesData\ReferencesData\StaticReferenceInterface;

class VehicleRepairMethods implements ReferenceInterface
{
    /**
     * @var array<int, VehicleRepairMethod>
     */
    protected $entities = [];

    /**
     * @var array<string, VehicleRepairMethod>
     */
    protected $repair_codes_idx = [];

    /**
     * Create a new reference instance.
     *
     * @param StaticReferenceInterface $static_reference
     *
     * @see \AvtoDev\StaticReferencesData\StaticReferencesData::vehicleRepairMethods()
     */
    public function __construct(StaticReferenceInterface $static_reference)
    {
        $counter = 0;

        foreach ((array) $static_reference->getData(true) as $datum) {
            $this->entities[$counter] = new VehicleRepairMethod($datum['codes'], $datum['description']);

            // burn repair codes index
            foreach ($datum['codes'] as $code) {
                $this->repair_codes_idx[$code] = &$this->entities[$counter];
            }

            $counter++;
        }
    }

    /**
     * @return Generator<VehicleRepairMethod>|VehicleRepairMethod[]
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
        return \array_map(static function (VehicleRepairMethod $e) {
            return $e->toArray();
        }, $this->entities);
    }

    /**
     * Get repair method by code.
     *
     * @param string $repair_method_code
     *
     * @return VehicleRepairMethod|null
     */
    public function getByCode(string $repair_method_code): ?VehicleRepairMethod
    {
        return $this->repair_codes_idx[$repair_method_code] ?? null;
    }

    /**
     * Repair method by code is exists?
     *
     * @param string $repair_method_code
     *
     * @return bool
     */
    public function hasCode(string $repair_method_code): bool
    {
        return isset($this->repair_codes_idx[$repair_method_code]);
    }

    /**
     * {@inheritdoc}
     */
    public function count(): int
    {
        return \count($this->entities);
    }
}
