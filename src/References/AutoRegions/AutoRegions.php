<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\References\AutoRegions;

use Illuminate\Support\Str;
use AvtoDev\StaticReferencesData\StaticReferencesData;
use AvtoDev\StaticReferences\References\AbstractReference;
use AvtoDev\StaticReferencesData\ReferencesData\StaticReferenceInterface;

class AutoRegions extends AbstractReference
{
    /**
     * @var AutoRegionEntry[]
     */
    protected $items = [];

    /**
     * {@inheritdoc}
     */
    public static function getVendorStaticReferenceInstance(): StaticReferenceInterface
    {
        static $instance;

        return $instance ?? $instance = StaticReferencesData::getAutoRegions();
    }

    /**
     * Получаем объект региона по коду субъекта РФ.
     *
     * @param string|int|mixed $region_code
     *
     * @return AutoRegionEntry|null
     */
    public function getByRegionCode($region_code): ?AutoRegionEntry
    {
        if (\is_string($region_code) || \is_int($region_code)) {
            // Очищаем входящее значение и приводим к числу
            $region_code = (int) \preg_replace('~\D~', '', (string) $region_code);
            foreach ($this->items as $region) {
                if ($region->getRegionCode() === $region_code) {
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
    public function hasRegionCode($region_code): bool
    {
        return $this->getByRegionCode($region_code) instanceof AutoRegionEntry;
    }

    /**
     * Получаем регион по его названию. Если параметр $strict_search == false, то производится не строгий поиск по
     * переданной строке. Внимание - алгоритм не самый надежный, и надо быть с эти дерьмом поаккуратнее.
     *
     * @param string $region_title
     * @param bool   $strict_search
     *
     * @return AutoRegionEntry|null
     */
    public function getByTitle($region_title, bool $strict_search = false): ?AutoRegionEntry
    {
        if (\is_string($region_title) && $region_title !== '') {
            // Ближайшее совпадение
            $closest = null;
            // Наименьшее расстояние
            $shortest = 0;

            foreach ($this->items as $region) {
                $titles = [$region->getTitle()];
                // Проверяем наличие свойства 'short_title', и если оно есть - то берем его в работу
                if (! empty($region->getShortTitles())) {
                    $titles = \array_merge($titles, (array) $region->getShortTitles());
                }
                foreach ($titles as $title) {
                    // Вычисляем расстояние между входным словом и текущим
                    $lev = \levenshtein($region_title, \trim((string) $title));
                    // Проверяем полное совпадение
                    if ($lev === 0) {
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

            if ($strict_search) {
                return $shortest === 0 ? $closest : null;
            }

            return $shortest <= 5 ? $closest : null; // Этим числом можно регулировать строгость похожести
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
    public function hasTitle($region_title, bool $strict_search = false): bool
    {
        return $this->getByTitle($region_title, $strict_search) instanceof AutoRegionEntry;
    }

    /**
     * Получаем регион по его авто-коду (коду региона по ГИБДД).
     *
     * @param string|int|mixed $auto_code
     *
     * @return AutoRegionEntry|null
     */
    public function getByAutoCode($auto_code): ?AutoRegionEntry
    {
        if (\is_string($auto_code) || \is_int($auto_code)) {
            // Очищаем входящее значение и приводим к числу
            $auto_code = (int) \preg_replace('~\D~', '', (string) $auto_code);
            foreach ($this->items as $region) {
                foreach ((array) $region->getAutoCodes() as $region_auto_code) {
                    if ($region_auto_code === $auto_code) {
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
    public function hasAutoCode($auto_code): bool
    {
        return $this->getByAutoCode($auto_code) instanceof AutoRegionEntry;
    }

    /**
     * Получаем объект региона по коду ОКАТО.
     *
     * @param string|int $okato_code
     *
     * @return AutoRegionEntry|null
     */
    public function getByOkato($okato_code): ?AutoRegionEntry
    {
        if (\is_scalar($okato_code)) {
            // Очищаем входящее значение и приводим к числу
            $okato_code = \preg_replace('~[^0-9-]~', '', (string) $okato_code);
            foreach ($this->items as $region) {
                if ($region->getOkato() === $okato_code) {
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
    public function hasOkato($okato_code): bool
    {
        return $this->getByOkato($okato_code) instanceof AutoRegionEntry;
    }

    /**
     * Получаем объект региона по коду ISO-31662.
     *
     * @param string|mixed $iso_31662
     *
     * @return AutoRegionEntry|null
     */
    public function getByIso31662($iso_31662): ?AutoRegionEntry
    {
        if (\is_string($iso_31662) && $iso_31662 !== '') {
            // Очищаем входящее значение
            $iso_31662 = \preg_replace('~[^A-Z-]~', '', Str::upper($iso_31662));
            foreach ($this->items as $region) {
                if ($region->getIso31662() === $iso_31662) {
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
    public function hasIso31662($iso_31662): bool
    {
        return $this->getByIso31662($iso_31662) instanceof AutoRegionEntry;
    }

    /**
     * {@inheritdoc}
     */
    public function getReferenceEntryClassName(): string
    {
        return AutoRegionEntry::class;
    }
}
