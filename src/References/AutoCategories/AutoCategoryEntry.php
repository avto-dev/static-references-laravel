<?php

namespace AvtoDev\StaticReferencesLaravel\References\AutoCategories;

use AvtoDev\StaticReferencesLaravel\References\AbstractReferenceEntry;
use Illuminate\Support\Str;

/**
 * Class AutoCategoryEntry.
 *
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
    public function configure($input = [])
    {
        if (is_array($input)) {
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
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Возвращает описание категории.
     *
     * @return null|string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return [
            'code'        => $this->code,
            'description' => $this->description,
        ];
    }
}
