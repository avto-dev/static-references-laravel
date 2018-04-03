<?php

namespace AvtoDev\StaticReferences\References\RepairMethods;

use Illuminate\Support\Str;
use AvtoDev\StaticReferencesData\StaticReferencesData;
use AvtoDev\StaticReferences\References\AbstractReference;

/**
 * Class RepairMethods.
 *
 * Справочник "Методы ремонта".
 */
class RepairMethods extends AbstractReference
{
    /**
     * {@inheritdoc}
     *
     * @var RepairMethodsEntry[]
     */
    protected $items = [];

    /**
     * {@inheritdoc}
     */
    public static function getVendorStaticReferenceInstance()
    {
        static $instance;

        return is_null($instance)
            ? $instance = StaticReferencesData::getRepairMethods()
            : $instance;
    }

    /**
     * Получаем объект метода ремонта по его коду.
     *
     * @param string $repair_method_code
     *
     * @return RepairMethodsEntry|null
     */
    public function getByCode($repair_method_code)
    {
        if (is_string($repair_method_code)) {
            foreach ($this->items as $repair_method) {
                if (in_array($repair_method_code, $repair_method->getCodes())) {
                    return $repair_method;
                }
            }
        }

        return null;
    }

    /**
     * Проверяет наличие объекта метода ремонта по его коду.
     *
     * @param string $repair_method_code
     *
     * @return bool
     */
    public function hasCode($repair_method_code)
    {
        return $this->getByCode($repair_method_code) instanceof RepairMethodsEntry;
    }

    /**
     * Возвращает объект метода ремонта по его описанию. Поиск НЕ СТРОГИЙ - по наличию подстроки.
     *
     * @param string $description
     *
     * @return RepairMethodsEntry|null
     */
    public function getByDescription($description)
    {
        if (is_scalar($description) && ! empty($description = Str::lower(trim((string) $description)))) {
            foreach ($this->items as $repair_method) {
                if (Str::contains(Str::lower($repair_method->getDescription()), $description)) {
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
    public function hasDescription($description)
    {
        return $this->getByDescription($description) instanceof RepairMethodsEntry;
    }

    /**
     * {@inheritdoc}
     */
    public function getReferenceEntryClassName()
    {
        return RepairMethodsEntry::class;
    }
}
