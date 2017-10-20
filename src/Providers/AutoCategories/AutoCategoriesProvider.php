<?php

namespace AvtoDev\StaticReferencesLaravel\Providers\AutoCategories;

use Illuminate\Support\Str;
use AvtoDev\StaticReferencesLaravel\Providers\AbstractReferenceProvider;

/**
 * Class AutoCategoriesProvider.
 *
 * Провайдер данных о категориях ТС.
 */
class AutoCategoriesProvider extends AbstractReferenceProvider
{
    /**
     * @var AutoCategory[]
     */
    protected $stack = [];

    /**
     * {@inheritdoc}
     */
    public static function getName()
    {
        return 'autoCategories';
    }

    /**
     * Возвращает объект категории по её коду.
     *
     * @param string $code
     *
     * @return AutoCategory|null
     */
    public function getByCode($code)
    {
        if (is_scalar($code) && ! empty($code = Str::upper(Str::ascii(trim((string) $code))))) {
            foreach ($this->stack as $auto_category) {
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
        return $this->getByCode($code) instanceof AutoCategory;
    }

    /**
     * Возвращает объект категории по её описанию. Поиск НЕ СТРОГИЙ - по наличию подстроки.
     *
     * @param string $description
     *
     * @return AutoCategory|null
     */
    public function getByDescription($description)
    {
        if (is_scalar($description) && ! empty($description = trim((string) $description))) {
            foreach ($this->stack as $auto_category) {
                if (Str::contains($auto_category->getDescription(), $description)) {
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
        return $this->getByDescription($description) instanceof AutoCategory;
    }

    /**
     * {@inheritdoc}
     */
    protected function getSourcesFilesPaths()
    {
        return __DIR__ . '/../../../vendor/avto-dev/static-references-data/data/auto_categories/auto_categories.json';
    }

    /**
     * {@inheritdoc}
     */
    protected function getReferenceEntityClassName()
    {
        return AutoCategory::class;
    }
}
