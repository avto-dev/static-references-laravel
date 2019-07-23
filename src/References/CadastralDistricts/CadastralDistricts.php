<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\References\CadastralDistricts;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;

class CadastralDistricts extends Collection
{
    /**
     * @var CadastralDistrictEntry[]
     */
    protected $items;

    /**
     * CadastralDistricts constructor.
     *
     * @param array $items
     */
    public function __construct($items = [])
    {
        parent::__construct(\array_map(function ($item) {
            return new CadastralDistrictEntry($item);
        }, $items));
    }

    /**
     * Получение районов по коду.
     *
     * @param string|int|mixed $district_code
     *
     * @return CadastralDistrictEntry|null
     */
    public function getDistrictByCode($district_code): ?CadastralDistrictEntry
    {
        foreach ($this->items as $district) {
            if ($district->getDistrictCode() === \sprintf('%02d', (int) $district_code)) {
                return $district;
            }
        }

        return null;
    }

    /**
     * Проверяет наличие района по коду.
     *
     * @param string|int|mixed $district_code
     *
     * @return bool
     */
    public function hasDistrictCode($district_code): bool
    {
        return $this->getDistrictByCode($district_code) instanceof CadastralDistrictEntry;
    }

    /**
     * Получение районов по названию.
     *
     * @param string $district_name
     *
     * @return CadastralDistrictEntry|null
     */
    public function getDistrictByName($district_name): ?CadastralDistrictEntry
    {
        foreach ($this->items as $district) {
            if (Str::lower($district->getDistrictName()) === Str::lower(\trim($district_name))) {
                return $district;
            }
        }

        return null;
    }

    /**
     * Проверяет наличие района по названию.
     *
     * @param string $district_name
     *
     * @return bool
     */
    public function hasDistrictName($district_name): bool
    {
        return $this->getDistrictByName($district_name) instanceof CadastralDistrictEntry;
    }
}
