<?php

namespace AvtoDev\StaticReferences\References\AutoFines;

use Illuminate\Support\Str;
use AvtoDev\StaticReferences\References\AbstractReferenceEntry;

/**
 * Сущность типа "Правонарушение в области дорожного движения".
 */
class AutoFineEntry extends AbstractReferenceEntry
{
    /**
     * Статья правонарушения в области дорожного движения.
     *
     * @var string|null
     */
    protected $article;

    /**
     * Описание правонарушения.
     *
     * @var string|null
     */
    protected $description;

    /**
     * {@inheritdoc}
     */
    public function configure($input = [])
    {
        if (\is_array($input)) {
            foreach ($input as $key => $value) {
                switch ($key = Str::lower((string) $key)) {
                    // Значение статьи правонарушения
                    case 'article':
                        $this->article = trim((string) $value);
                        break;

                    // Описание правонарушения
                    case 'description':
                        $this->description = trim((string) $value);
                        break;
                }
            }
        }
    }

    /**
     * Возвращает статью правонарушения в области дорожного движения.
     *
     * @return null|string
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * Возвращает описание правонарушения.
     *
     * @return null|string
     */
    public function getDescription()
    {
        return $this->description;
    }
}
