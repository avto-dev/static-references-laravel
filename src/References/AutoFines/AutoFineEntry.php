<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\References\AutoFines;

use Illuminate\Support\Str;
use AvtoDev\StaticReferences\References\AbstractReferenceEntry;

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
    public function configure($input = []): void
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
    public function getArticle(): ?string
    {
        return $this->article;
    }

    /**
     * Возвращает описание правонарушения.
     *
     * @return null|string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }
}
