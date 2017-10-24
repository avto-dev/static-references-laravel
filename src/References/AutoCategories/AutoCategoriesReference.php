<?php

namespace AvtoDev\StaticReferencesLaravel\References\AutoCategories;

use Illuminate\Support\Str;
use AvtoDev\StaticReferencesLaravel\References\AbstractReference;

/**
 * Class AutoCategoriesReference.
 *
 * Провайдер данных о категориях ТС.
 */
class AutoCategoriesReference extends AbstractReference
{
    /**
     * @var AutoCategoryEntry[]
     */
    protected $items = [];

    /**
     * Возвращает объект категории по её коду.
     *
     * @param string $code
     *
     * @return AutoCategoryEntry|null
     */
    public function getByCode($code)
    {
        if (is_scalar($code) && ! empty($code = trim($this->uppercaseAndSafeTransliterate($code)))) {
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
        if (is_scalar($description) && ! empty($description = Str::lower(trim((string) $description)))) {
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
    protected function getSourcesFilesPaths()
    {
        return $this->getVendorPath() . '/data/auto_categories/auto_categories.json';
    }

    /**
     * {@inheritdoc}
     */
    protected function getReferenceEntityClassName()
    {
        return AutoCategoryEntry::class;
    }
}
