<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\References\CadastralDistricts;

use Illuminate\Support\Str;
use AvtoDev\StaticReferencesData\StaticReferencesData;
use AvtoDev\StaticReferences\References\AbstractReference;
use AvtoDev\StaticReferencesData\ReferencesData\StaticReferenceInterface;

class CadastralRegions extends AbstractReference
{
    /**
     * @var CadastralRegionEntry[]
     */
    protected $items = [];

    /**
     * {@inheritdoc}
     */
    public static function getVendorStaticReferenceInstance(): StaticReferenceInterface
    {
        static $instance;

        return $instance ?? $instance = StaticReferencesData::getCadastralDistricts();
    }

    /**
     * Получение субъекта по коду.
     *
     * @param string|int|mixed $region_code
     *
     * @return CadastralRegionEntry|null
     */
    public function getRegionByCode($region_code): ?CadastralRegionEntry
    {
        if (\is_int($region_code) || \is_string($region_code)) {
            foreach ($this->items as $region) {
                if (\sprintf('%02d', (int) $region_code) === $region->getRegionCode()) {
                    return $region;
                }
            }
        }

        return null;
    }

    /**
     * Проверяет наличие субъекта по его коду.
     *
     * @param string|int $region_code
     *
     * @return bool
     */
    public function hasRegionCode($region_code): bool
    {
        return $this->getRegionByCode($region_code) instanceof CadastralRegionEntry;
    }

    /**
     * Получение субъекта по его названию.
     *
     * @param string|int $name
     *
     * @return CadastralRegionEntry|null
     */
    public function getRegionByName($name): ?CadastralRegionEntry
    {
        if (($name = Str::lower(\trim((string) $name))) !== '') {
            foreach ($this->items as $region) {
                if (Str::lower($region->getRegionName()) === $name) {
                    return $region;
                }
            }
        }

        return null;
    }

    /**
     * Проверяет наличие субъекта по его названию.
     *
     * @param string|int $region_code
     *
     * @return bool
     */
    public function hasRegionName($region_code): bool
    {
        return $this->getRegionByName($region_code) instanceof CadastralRegionEntry;
    }

    /**
     * {@inheritdoc}
     */
    public function getReferenceEntryClassName(): string
    {
        return CadastralRegionEntry::class;
    }
}
