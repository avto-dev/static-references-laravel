<?php

namespace AvtoDev\StaticReferencesLaravel\References;

use Exception;
use ReflectionClass;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use AvtoDev\StaticReferencesLaravel\Traits\TransliterateTrait;
use AvtoDev\StaticReferencesLaravel\Exceptions\FileReadingException;

/**
 * Class AbstractReference.
 *
 * Абстрактный класс единичного справочника.
 */
abstract class AbstractReference extends Collection implements ReferenceInterface
{
    use TransliterateTrait;

    /**
     * Стек для хранения данных справочника в сыром виде.
     *
     * @var ReferenceEntryInterface[]|array
     */
    protected $items = [];

    /**
     * {@inheritdoc}
     */
    public function __construct($items = [])
    {
        if (($class_name = $this->getReferenceEntryClassName()) && class_exists($class_name)) {
            $source_items = [];

            // Перебираем все файлы-источники
            foreach ((array) $this->getSourcesFilesPaths() as $file_path) {
                // Если имя файла говорит о том, что он является json-ом
                if (Str::endsWith($file_path, 'json')) {
                    $source_items = array_merge_recursive($source_items, $this->getContentFromJsonFile($file_path));
                }
            }

            // Объединяем данные из файлов-источников с переданными в конструктор и преобразуем элементы к объектам
            // элемента справочника
            parent::__construct(array_filter(array_map(function ($item_data) use ($class_name) {
                return $this->referenceEntityFactory($class_name, $item_data);
            }, array_replace_recursive($this->getArrayableItems($items), $source_items))));
        } else {
            throw new Exception(sprintf('Class "%s" in "%s" does not exists', $class_name, static::class));
        }
    }

    /**
     * {@inheritdoc}
     */
    abstract public function getReferenceEntryClassName();

    /**
     * {@inheritdoc}
     */
    public function offsetSet($key, $value)
    {
        $class_name = $this->getReferenceEntryClassName();

        if (is_null($key)) {
            array_push($this->items, $this->referenceEntityFactory($class_name, $value));
        } else {
            $this->items[$key] = $this->referenceEntityFactory($class_name, $value);
        }
    }

    /**
     * Возвращает массив путей к файлам-источникам справочника.
     *
     * @return string|string[]
     */
    abstract protected function getSourcesFilesPaths();

    /**
     * Читает контент из файла по переданному пути, считая его json-файлом.
     *
     * @param string $file_path
     *
     * @throws FileReadingException
     *
     * @return array[]|array
     */
    protected function getContentFromJsonFile($file_path)
    {
        try {
            $result = json_decode(
                file_get_contents($file_path, false, null, 0, 524288),
                true
            );

            if (json_last_error() === JSON_ERROR_NONE && is_array($result) && ! empty($result)) {
                return $result;
            } else {
                throw new Exception(sprintf('Json file "%s" reading error', $file_path));
            }
        } catch (Exception $e) {
            throw new FileReadingException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Факторка по созданию инстансов элементов справочника.
     *
     * @param string $entity_class
     * @param array  ...$arguments
     *
     * @return ReferenceEntryInterface
     */
    protected function referenceEntityFactory($entity_class, ...$arguments)
    {
        return new $entity_class(...$arguments);
    }

    /**
     * Возвращает путь к директории 'vendor', и по умолчанию - добавляет путь пакета 'avto-dev/static-references-data'.
     *
     * @param string $additional
     *
     * @return string $additional
     */
    protected function getVendorPath($additional = 'avto-dev/static-references-data')
    {
        $reflector = new ReflectionClass('\\Composer\\Autoload\\ClassLoader');
        $vendor    = realpath(dirname($reflector->getFileName()) . '/..');

        return $vendor . (! empty($additional)
                ? '/' . ltrim((string) $additional, '\\/ ')
                : '');
    }
}
