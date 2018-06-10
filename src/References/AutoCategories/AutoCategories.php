<?php

namespace AvtoDev\StaticReferences\References\AutoCategories;

use Illuminate\Support\Str;
use AvtoDev\StaticReferencesData\StaticReferencesData;
use AvtoDev\StaticReferences\References\AbstractReference;

/**
 * Справочник "Категории транспортных средств".
 */
class AutoCategories extends AbstractReference
{
    /**
     * @var AutoCategoryEntry[]
     */
    protected $items = [];

    /**
     * {@inheritdoc}
     */
    public static function getVendorStaticReferenceInstance()
    {
        static $instance;

        return $instance === null
            ? $instance = StaticReferencesData::getAutoCategories()
            : $instance;
    }

    /**
     * Возвращает объект категории по её коду.
     *
     * @param string $code
     *
     * @return AutoCategoryEntry|null
     */
    public function getByCode($code)
    {
        if (\is_scalar($code) && ! empty($code = trim($this->uppercaseAndSafeTransliterate($code)))) {
            foreach ($this->items as $auto_category) {
                if ($auto_category->getCode() === $code) {
                    return $auto_category;
                }
            }
        }
    }

    /**
     * Возвращает true в том случае, если категория (по коду) имеется в справочнике.
     *
     * @param string $code
     *
     * @return bool
     */
    public function hasCode($code)
    {
        return $this->getByCode($code) instanceof AutoCategoryEntry;
    }

    /**
     * Возвращает объект категории по её описанию. Поиск НЕ СТРОГИЙ - по наличию подстроки.
     *
     * @param string $description
     *
     * @return AutoCategoryEntry|null
     */
    public function getByDescription($description)
    {
        if (\is_scalar($description) && ! empty($description = Str::lower(trim((string) $description)))) {
            foreach ($this->items as $auto_category) {
                if (Str::contains(Str::lower($auto_category->getDescription()), $description)) {
                    return $auto_category;
                }
            }
        }
    }

    /**
     * Возвращает true в том случае, если категория (по описанию) имеется в справочнике.
     *
     * @param string $description
     *
     * @return bool
     */
    public function hasDescription($description)
    {
        return $this->getByDescription($description) instanceof AutoCategoryEntry;
    }

    /**
     * {@inheritdoc}
     */
    public function getReferenceEntryClassName()
    {
        return AutoCategoryEntry::class;
    }
}
