<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\References\AutoCategories;

use AvtoDev\StaticReferences\References\AbstractReferenceEntry;
use Illuminate\Support\Str;

/**
 * Сущность типа "Категория автомобиля".
 */
class AutoCategoryEntry extends AbstractReferenceEntry
{
    /**
     * Код категории.
     *
     * @var string|null
     */
    protected $code;

    /**
     * Описание категории.
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
                    // Код категории
                    case 'code':
                    case 'category':
                    case 'categoryname':
                    case 'category_name':
                        $this->code = trim($this->uppercaseAndSafeTransliterate($value));
                        break;

                    case 'desc':
                    case 'descr':
                    case 'description':
                        $this->description = trim((string) $value);
                        break;
                }
            }
        }
    }

    /**
     * Возвращает код категории.
     *
     * @return null|string
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * Возвращает описание категории.
     *
     * @return null|string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }
}
