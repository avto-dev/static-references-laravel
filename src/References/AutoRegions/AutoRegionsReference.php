<?php

namespace AvtoDev\StaticReferencesLaravel\References\AutoRegions;

use Illuminate\Support\Str;
use AvtoDev\StaticReferencesLaravel\References\AbstractReference;

/**
 * Class AutoRegionsReference.
 *
 * Справочник "Регионы субъектов".
 */
class AutoRegionsReference extends AbstractReference
{
    /**
     * @var RegionEntry[]
     */
    protected $items = [];

    /**
     * Получаем объект региона по коду субъекта РФ.
     *
     * @param string|int $region_code
     *
     * @return RegionEntry|null
     */
    public function getByRegionCode($region_code)
    {
        if (is_scalar($region_code)) {
            // Очищаем входящее значение и приводим к числу
            $region_code = (int) preg_replace('~[^0-9]~', '', $region_code);
            foreach ($this->items as $region) {
                if ($region->getRegionCode() == $region_code) {
                    return $region;
                }
            }
        }

        return null;
    }

    /**
     * Проверяет наличие данных о регионе в справочнике по коду региона.
     *
     * @param string|int $region_code
     *
     * @return bool
     */
    public function hasRegionCode($region_code)
    {
        return $this->getByRegionCode($region_code) instanceof RegionEntry;
    }

    /**
     * Получаем регион по его названию. Если параметр $strict_search == false, то производится не строгий поиск по
     * переданной строке. Внимание - алгоритм не самый надежный, и надо быть с эти дерьмом поаккуратнее.
     *
     * @param string $region_title
     * @param bool   $strict_search
     *
     * @return RegionEntry|null
     */
    public function getByTitle($region_title, $strict_search = false)
    {
        if (!empty($region_title) && is_string($region_title)) {
            // Ближайшее совпадение
            $closest = null;
            // Наименьшее расстояние
            $shortest = 0;

            foreach ($this->items as $region) {
                $titles = [$region->getTitle()];
                // Проверяем наличие свойства 'short_title', и если оно есть - то берем его в работу
                if (!empty($region->getShortTitles())) {
                    $titles = array_merge($titles, (array) $region->getShortTitles());
                }
                foreach ($titles as $title) {
                    $title = trim($title);
                    // Вычисляем расстояние между входным словом и текущим
                    $lev = levenshtein($region_title, $title);
                    // Проверяем полное совпадение
                    if ($lev == 0) {
                        $closest  = $region;
                        $shortest = 0;
                        break 2;
                    }
                    if ($lev <= $shortest || $shortest <= 0) {
                        $closest  = $region;
                        $shortest = $lev;
                    }
                }
            }

            return $strict_search === true
                ? ($shortest == 0 ? $closest : null)
                : ($shortest <= 5 ? $closest : null); // Этим числом можно регулировать строгость похожести
        }

        return null;
    }

    /**
     * Проверяет наличие объекта региона по его названию.
     *
     * @param string $region_title
     * @param bool   $strict_search
     *
     * @return bool
     */
    public function hasByTitle($region_title, $strict_search = false)
    {
        return $this->getByTitle($region_title, $strict_search) instanceof RegionEntry;
    }

    /**
     * Получаем регион по его авто-коду (коду региона по ГИБДД).
     *
     * @param string|int $auto_code
     *
     * @return RegionEntry|null
     */
    public function getByAutoCode($auto_code)
    {
        if (is_scalar($auto_code)) {
            // Очищаем входящее значение и приводим к числу
            $auto_code = (int) preg_replace('~[^0-9]~', '', $auto_code);
            foreach ($this->items as $region) {
                foreach ((array) $region->getAutoCode() as $region_auto_code) {
                    if ($region_auto_code == $auto_code) {
                        return $region;
                    }
                }
            }
        }

        return null;
    }

    /**
     * Проверяет наличие объекта региона по его коду в справочнике.
     *
     * @param string|int $auto_code
     *
     * @return bool
     */
    public function hasByAutoCode($auto_code)
    {
        return $this->getByAutoCode($auto_code) instanceof RegionEntry;
    }

    /**
     * Получаем объект региона по коду ОКАТО.
     *
     * @param string|int $okato_code
     *
     * @return RegionEntry|null
     */
    public function getByOkato($okato_code)
    {
        if (is_scalar($okato_code)) {
            // Очищаем входящее значение и приводим к числу
            $okato_code = preg_replace('~[^0-9-]~', '', (string) $okato_code);
            foreach ($this->items as $region) {
                if ($region->getOkato() == $okato_code) {
                    return $region;
                }
            }
        }

        return null;
    }

    /**
     * Проверяет наличие объекта региона по его коду ОКАТО в справочнике.
     *
     * @param string|int $okato_code
     *
     * @return bool
     */
    public function hasByOkato($okato_code)
    {
        return $this->getByOkato($okato_code) instanceof RegionEntry;
    }

    /**
     * Получаем объект региона по коду ISO-31662.
     *
     * @param string $iso_31662
     *
     * @return RegionEntry|null
     */
    public function getByIso31662($iso_31662)
    {
        if (!empty($iso_31662) && is_string($iso_31662)) {
            // Очищаем входящее значение
            $iso_31662 = preg_replace('~[^A-Z-]~', '', Str::upper($iso_31662));
            foreach ($this->items as $region) {
                if ($region->getIso31662() == $iso_31662) {
                    return $region;
                }
            }
        }

        return null;
    }

    /**
     * Проверяет наличие объекта региона по его коду ISO-31662 в справочнике.
     *
     * @param string|int $iso_31662
     *
     * @return bool
     */
    public function hasByIso31662($iso_31662)
    {
        return $this->getByIso31662($iso_31662) instanceof RegionEntry;
    }

    /**
     * {@inheritdoc}
     */
    protected function getSourcesFilesPaths()
    {
        return $this->getVendorPath() . '/data/auto_regions/auto_regions.json';
    }

    /**
     * {@inheritdoc}
     */
    public function getReferenceEntryClassName()
    {
        return RegionEntry::class;
    }
}
