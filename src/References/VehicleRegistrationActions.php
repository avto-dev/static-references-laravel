<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\References;

use Generator;
use AvtoDev\StaticReferencesData\ReferencesData\StaticReferenceInterface;
use AvtoDev\StaticReferences\References\Entities\VehicleRegistrationAction;

class VehicleRegistrationActions implements ReferenceInterface
{
    /**
     * @var array<int, VehicleRegistrationAction>
     */
    protected $entities = [];

    /**
     * @var array<int, VehicleRegistrationAction>
     */
    protected $codes_idx = [];

    /**
     * Create a new reference instance.
     *
     * @param StaticReferenceInterface $static_reference
     *
     * @see \AvtoDev\StaticReferencesData\StaticReferencesData::vehicleRegistrationActions()
     */
    public function __construct(StaticReferenceInterface $static_reference)
    {
        $counter = 0;

        foreach ((array) $static_reference->getData(true) as $datum) {
            $this->entities[$counter] = new VehicleRegistrationAction($datum['codes'], $datum['description']);

            // burn codes index
            foreach ($datum['codes'] as $code) {
                /** @var int $code */
                $this->codes_idx[$code] = &$this->entities[$counter];
            }

            $counter++;
        }
    }

    /**
     * @return Generator<VehicleRegistrationAction>
     */
    public function getIterator(): Generator
    {
        foreach ($this->entities as $item) {
            yield $item;
        }
    }

    /**
     * @return array<array-key, array<string, mixed>>
     */
    public function toArray(): array
    {
        return \array_map(static function (VehicleRegistrationAction $e): array {
            return $e->toArray();
        }, \array_values($this->entities));
    }

    /**
     * Get registration action entity by code.
     *
     * @param int $reg_action_code
     *
     * @return VehicleRegistrationAction|null
     */
    public function getByCode(int $reg_action_code): ?VehicleRegistrationAction
    {
        return $this->codes_idx[$reg_action_code] ?? null;
    }

    /**
     * Check for registration action exists by code.
     *
     * @param int $reg_action_code
     *
     * @return bool
     */
    public function hasCode(int $reg_action_code): bool
    {
        return isset($this->codes_idx[$reg_action_code]);
    }

    /**
     * {@inheritdoc}
     */
    public function count(): int
    {
        return \count($this->entities);
    }
}
