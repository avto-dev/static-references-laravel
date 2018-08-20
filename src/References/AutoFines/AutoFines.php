<?php

namespace AvtoDev\StaticReferences\References\AutoFines;

use Illuminate\Support\Str;
use AvtoDev\StaticReferencesData\StaticReferencesData;
use AvtoDev\StaticReferences\References\AbstractReference;

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
    public static function getVendorStaticReferenceInstance()
    {
        static $instance;

        return $instance === null
            ? $instance = StaticReferencesData::getAutoFines()
            : $instance;
    }

    /**
     * Возвращает объект правонарушения по коду статьи.
     *
     * @param string $article
     *
     * @return AutoFineEntry|null
     */
    public function getByArticle($article)
    {
        if (\is_scalar($article) && ! empty($article = \trim($article))) {
            // Производим "очистку" входящего значения
            $article = $this->clearArticleValue($article);

            foreach ($this->items as $auto_fine_entry) {
                if ($this->clearArticleValue($auto_fine_entry->getArticle()) === $article) {
                    return $auto_fine_entry;
                }
            }
        }
    }

    /**
     * Возвращает true в том случае, если объект правонарушения (по коду статьи) имеется в справочнике.
     *
     * @param string $code
     *
     * @return bool
     */
    public function hasArticle($code)
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
    public function getByDescription($description)
    {
        if (\is_scalar($description) && ! empty($description = Str::lower(trim((string) $description)))) {
            foreach ($this->items as $auto_fine_entry) {
                if (Str::contains(Str::lower($auto_fine_entry->getDescription()), $description)) {
                    return $auto_fine_entry;
                }
            }
        }
    }

    /**
     * Возвращает true в том случае, если объект правонарушения (по описанию) имеется в справочнике.
     *
     * @param string $description
     *
     * @return bool
     */
    public function hasDescription($description)
    {
        return $this->getByDescription($description) instanceof AutoFineEntry;
    }

    /**
     * {@inheritdoc}
     */
    public function getReferenceEntryClassName()
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
    protected function clearArticleValue($value)
    {
        // Заменяем все символы, кроме чисел - на точки + trim по точкам
        $value = \trim(\preg_replace('/[\D]/', '.', (string) $value), '.');

        // Множественные точки - заменяем на одинарные
        $value = \preg_replace('/\.+/', '.', $value);

        // И возвращаем значение
        return (string) $value;
    }
}
