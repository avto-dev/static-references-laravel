<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\References;

use Generator;
use AvtoDev\StaticReferences\References\Entities\RepairMethod;
use AvtoDev\StaticReferencesData\ReferencesData\StaticReference;

class RepairMethods implements ReferenceInterface
{
    /**
     * @var array<int, RepairMethod>
     */
    protected $entities = [];

    /**
     * @var array<string, RepairMethod>
     */
    protected $repair_codes_idx = [];

    /**
     * Create a new reference instance.
     *
     * @param StaticReference $static_reference
     *
     * @see \AvtoDev\StaticReferencesData\StaticReferencesData::getRepairMethods()
     */
    public function __construct(StaticReference $static_reference)
    {
        $counter = 0;

        foreach ((array) $static_reference->getData(true) as $datum) {
            $this->entities[$counter] = new RepairMethod($datum['codes'], $datum['description']);

            // burn repair codes index
            foreach ($datum['codes'] as $code) {
                $this->repair_codes_idx[$code] = &$this->entities[$counter];
            }

            $counter++;
        }
    }

    /**
     * @return Generator<RepairMethod>|RepairMethod[]
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
        return \array_map(static function (RepairMethod $e) {
            return $e->toArray();
        }, $this->entities);
    }

    /**
     * Get repair method by code.
     *
     * @param string $repair_method_code
     *
     * @return RepairMethod|null
     */
    public function getByCode(string $repair_method_code): ?RepairMethod
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
