<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\References\RepairMethods;

use AvtoDev\StaticReferences\References\AbstractReference;
use AvtoDev\StaticReferencesData\ReferencesData\StaticReferenceInterface;
use AvtoDev\StaticReferencesData\StaticReferencesData;
use Illuminate\Support\Str;

class RepairMethods extends AbstractReference
{
    /**
     * @var RepairMethodsEntry[]
     */
    protected $items = [];

    /**
     * {@inheritdoc}
     */
    public static function getVendorStaticReferenceInstance(): StaticReferenceInterface
    {
        static $instance;

        return $instance ?? $instance = StaticReferencesData::getRepairMethods();
    }

    /**
     * Get repair method by code.
     *
     * @param string $repair_method_code
     *
     * @return RepairMethodsEntry|null
     */
    public function getByCode($repair_method_code): ?RepairMethodsEntry
    {
        if (\is_string($repair_method_code)) {
            foreach ($this->items as $repair_method) {
                if (\in_array($repair_method_code, $repair_method->getCodes(), true)) {
                    return $repair_method;
                }
            }
        }

        return null;
    }

    /**
     * Repair method by code is exists?
     *
     * @param string $repair_method_code
     *
     * @return bool
     */
    public function hasCode($repair_method_code): bool
    {
        return $this->getByCode($repair_method_code) instanceof RepairMethodsEntry;
    }

    /**
     * Get repair method by description (description is case insensitive).
     *
     * @param string $description
     *
     * @return RepairMethodsEntry|null
     */
    public function getByDescription($description): ?RepairMethodsEntry
    {
        if (\is_scalar($description) && ! empty($description = Str::lower(trim((string) $description)))) {
            foreach ($this->items as $repair_method) {
                if (Str::lower($repair_method->getDescription()) === $description) {
                    return $repair_method;
                }
            }
        }

        return null;
    }

    /**
     * Проверяет наличие объекта метода ремонта по его описанию.
     *
     * @param string $description
     *
     * @return bool
     */
    public function hasDescription($description): bool
    {
        return $this->getByDescription($description) instanceof RepairMethodsEntry;
    }

    /**
     * {@inheritdoc}
     */
    public function getReferenceEntryClassName(): string
    {
        return RepairMethodsEntry::class;
    }
}
