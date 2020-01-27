<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\References\AutoFines;

use AvtoDev\StaticReferences\References\AbstractReference;
use AvtoDev\StaticReferencesData\ReferencesData\StaticReferenceInterface;
use AvtoDev\StaticReferencesData\StaticReferencesData;
use Illuminate\Support\Str;

/**
 * Справочник "Правонарушения в области дорожного движения".
 */
class AutoFines extends AbstractReference
{
    /**
     * @var AutoFineEntry[]
     */
    protected $items = [];

    /**
     * {@inheritdoc}
     */
    public static function getVendorStaticReferenceInstance(): StaticReferenceInterface
    {
        static $instance;

        return $instance ?? $instance = StaticReferencesData::getAutoFines();
    }

    /**
     * Возвращает объект правонарушения по коду статьи.
     *
     * @param string $article
     *
     * @return AutoFineEntry|null
     */
    public function getByArticle($article): ?AutoFineEntry
    {
        if (\is_string($article) && ! empty($article = \trim($article))) {
            // Производим "очистку" входящего значения
            $article = $this->clearArticleValue($article);

            foreach ($this->items as $auto_fine_entry) {
                if (
                    \is_string($auto_fine_entry->getArticle()) &&
                    $this->clearArticleValue($auto_fine_entry->getArticle()) === $article
                ) {
                    return $auto_fine_entry;
                }
            }
        }

        return null;
    }

    /**
     * Возвращает true в том случае, если объект правонарушения (по коду статьи) имеется в справочнике.
     *
     * @param string $code
     *
     * @return bool
     */
    public function hasArticle($code): bool
    {
        return $this->getByArticle($code) instanceof AutoFineEntry;
    }

    /**
     * Возвращает объект правонарушения по его описанию. Поиск НЕ СТРОГИЙ - по наличию подстроки. Возвращает лишь
     * первое найденное вхождение.
     *
     * @param string $description
     *
     * @return AutoFineEntry|null
     */
    public function getByDescription($description): ?AutoFineEntry
    {
        if (\is_scalar($description) && ! empty($description = Str::lower(trim((string) $description)))) {
            foreach ($this->items as $auto_fine_entry) {
                if (
                    \is_string($auto_fine_entry->getDescription()) &&
                    Str::contains(Str::lower($auto_fine_entry->getDescription()), $description)
                ) {
                    return $auto_fine_entry;
                }
            }
        }

        return null;
    }

    /**
     * Возвращает true в том случае, если объект правонарушения (по описанию) имеется в справочнике.
     *
     * @param string $description
     *
     * @return bool
     */
    public function hasDescription($description): bool
    {
        return $this->getByDescription($description) instanceof AutoFineEntry;
    }

    /**
     * {@inheritdoc}
     */
    public function getReferenceEntryClassName(): string
    {
        return AutoFineEntry::class;
    }

    /**
     * Производим "безопасные" замены в значении статьи (например: "11.23 Ч.2" или "12.18") для осуществления более
     * "мягкого" поиска (без зависимости точного указания, например, "Ч" или "часть").
     *
     * @param string $value
     *
     * @return string
     */
    protected function clearArticleValue($value): string
    {
        // Заменяем все символы, кроме чисел - на точки + trim по точкам
        $value = \trim((string) \preg_replace('/[\D]/', '.', (string) $value), '.');

        // Множественные точки - заменяем на одинарные
        $value = \preg_replace('/\.+/', '.', $value);

        // И возвращаем значение
        return (string) $value;
    }
}
