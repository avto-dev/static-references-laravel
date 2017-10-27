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
        return parent::__construct(empty($items)
            ? $this->getStaticEntries()
            : $items);
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
        if (is_null($key)) {
            array_push($this->items, $this->referenceEntityFactory($value));
        } else {
            $this->items[$key] = $this->referenceEntityFactory($value);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function map(callable $callback)
    {
        $result = parent::map($callback);

        return $result->contains(function ($item) {
            return ! $item instanceof static;
        })
            ? $result->toBase()
            : $result;
    }

    /**
     * {@inheritdoc}
     */
    public function pluck($value, $key = null)
    {
        return $this->toBase()->pluck($value, $key);
    }

    /**
     * {@inheritdoc}
     */
    public function keys()
    {
        return $this->toBase()->keys();
    }

    /**
     * {@inheritdoc}
     */
    public function zip($items)
    {
        return call_user_func_array([$this->toBase(), 'zip'], func_get_args());
    }

    /**
     * {@inheritdoc}
     */
    public function collapse()
    {
        return $this->toBase()->collapse();
    }

    /**
     * {@inheritdoc}
     */
    public function flatten($depth = INF)
    {
        return $this->toBase()->flatten($depth);
    }

    /**
     * {@inheritdoc}
     */
    public function flip()
    {
        return $this->toBase()->flip();
    }

    /**
     * {@inheritdoc}
     */
    public function pad($size, $value)
    {
        return $this->toBase()->pad($size, $value);
    }

    /**
     * Возвращает массив объектов, прочитав данные о них из статических файлов данных.
     *
     * @return ReferenceEntryInterface[]|array
     */
    protected function getStaticEntries()
    {
        $source_items = [];

        // Перебираем все файлы-источники
        foreach ((array) $this->getSourcesFilesPaths() as $file_path) {
            // Если имя файла говорит о том, что он является json-ом
            if (Str::endsWith($file_path, 'json')) {
                $source_items = array_merge_recursive($source_items, $this->getContentFromJsonFile($file_path));
            }
        }

        // Преобразуем элементы к объектам элемента справочника
        return array_map(function ($item_data) {
            return $this->referenceEntityFactory($item_data);
        }, array_filter($source_items));
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
     * @param array ...$arguments
     *
     * @throws Exception
     *
     * @return ReferenceEntryInterface
     */
    protected function referenceEntityFactory(...$arguments)
    {
        $class_name = $this->getReferenceEntryClassName();

        if (class_exists($class_name)) {
            return new $class_name(...$arguments);
        }

        throw new Exception(sprintf('Class "%s" in "%s" does not exists', $class_name, static::class));
    }

    /**
     * Возвращает путь к директории 'vendor', и по умолчанию - добавляет путь пакета 'avto-dev/static-references-data'.
     *
     * @param string $additional
     *
     * @throws Exception
     *
     * @return string $additional
     */
    protected function getVendorPath($additional = 'avto-dev/static-references-data')
    {
        static $vendor = null;

        if (is_null($vendor)) {
            $reflector = new ReflectionClass('\\Composer\\Autoload\\ClassLoader');
            $vendor    = realpath(dirname($reflector->getFileName()) . '/..');

            if (! is_dir($vendor) || ! is_readable($vendor)) {
                throw new Exception(sprintf('Cannot detect vendors directory path: "%s"', $vendor));
            }
        }

        return $vendor . (! empty($additional)
                ? '/' . ltrim((string) $additional, '\\/ ')
                : '');
    }
}
