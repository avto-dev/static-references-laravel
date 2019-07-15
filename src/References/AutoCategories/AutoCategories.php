<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\References\AutoCategories;

use Illuminate\Support\Str;
use AvtoDev\StaticReferencesData\StaticReferencesData;
use AvtoDev\StaticReferences\References\AbstractReference;
use AvtoDev\StaticReferencesData\ReferencesData\StaticReferenceInterface;

class AutoCategories extends AbstractReference
{
    /**
     * @var AutoCategoryEntry[]
     */
    protected $items = [];

    /**
     * {@inheritdoc}
     */
    public static function getVendorStaticReferenceInstance(): StaticReferenceInterface
    {
        static $instance;

        return $instance ?? $instance = StaticReferencesData::getAutoCategories();
    }

    /**
     * Возвращает объект категории по её коду.
     *
     * @param string $code
     *
     * @return AutoCategoryEntry|null
     */
    public function getByCode($code): ?AutoCategoryEntry
    {
        if (\is_scalar($code) && ! empty($code = trim($this->uppercaseAndSafeTransliterate($code)))) {
            foreach ($this->items as $auto_category) {
                if ($auto_category->getCode() === $code) {
                    return $auto_category;
                }
            }
        }

        return null;
    }

    /**
     * Возвращает true в том случае, если категория (по коду) имеется в справочнике.
     *
     * @param string $code
     *
     * @return bool
     */
    public function hasCode($code): bool
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
    public function getByDescription($description): ?AutoCategoryEntry
    {
        if (\is_string($description) && ! empty($description = Str::lower(trim((string) $description)))) {
            foreach ($this->items as $auto_category) {
                if (
                    \is_string($auto_category->getDescription()) &&
                    Str::contains(Str::lower($auto_category->getDescription()), $description)
                ) {
                    return $auto_category;
                }
            }
        }

        return null;
    }

    /**
     * Возвращает true в том случае, если категория (по описанию) имеется в справочнике.
     *
     * @param string $description
     *
     * @return bool
     */
    public function hasDescription($description): bool
    {
        return $this->getByDescription($description) instanceof AutoCategoryEntry;
    }

    /**
     * {@inheritdoc}
     */
    public function getReferenceEntryClassName(): string
    {
        return AutoCategoryEntry::class;
    }
}
