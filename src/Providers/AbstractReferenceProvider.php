<?php

namespace AvtoDev\StaticReferencesLaravel\Providers;

use Closure;
use Exception;
use Illuminate\Support\Str;
use AvtoDev\StaticReferencesLaravel\ReferencesStackInterface;
use AvtoDev\StaticReferencesLaravel\Exceptions\FileReadingException;

/**
 * Class AbstractReferenceProvider.
 *
 * Абстрактный класс единичного справочника.
 */
abstract class AbstractReferenceProvider implements ReferenceProviderInterface
{
    /**
     * Стек для хранения данных справочника в сыром виде.
     *
     * @var ReferenceEntityInterface[]
     */
    protected $stack = [];

    /**
     * Инстанс объекта-стека справочников.
     *
     * @var ReferencesStackInterface
     */
    protected $references;

    /**
     * {@inheritdoc}
     */
    public function __construct(ReferencesStackInterface $references_stack)
    {
        $this->references = $references_stack;
        $this->initializeStack();
    }

    /**
     * {@inheritdoc}
     */
    public function all()
    {
        return $this->stack;
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->stack);
    }

    /**
     * Перебирает все элементы стека с помощью callback-функции.
     *
     * @param Closure $callback
     *
     * @return void
     */
    public function each(Closure $callback)
    {
        foreach ($this->stack as &$item) {
            $callback($item);
        }
    }

    /**
     * Инициализирует (загружает) все элементы справочника в стек.
     *
     * @throws Exception
     */
    protected function initializeStack()
    {
        $raw_stack = [];

        // Перебираем все файлы-источники
        foreach ((array) $this->getSourcesFilesPaths() as $file_path) {
            // Если имя файла говорит о том, что он является json-ом
            if (Str::endsWith($file_path, 'json')) {
                $raw_stack = array_merge_recursive($raw_stack, $this->getContentFromJsonFile($file_path));
            }
        }

        if (($class_name = $this->getReferenceEntityClassName()) && class_exists($class_name)) {
            // Преобразуем элементы массива к объектам элемента справочника
            foreach ($raw_stack as &$reference_item) {
                try {
                    array_push($this->stack, $this->referenceEntityFactory($class_name, $reference_item));
                } catch (Exception $e) {
                    //
                }
            }
        } else {
            throw new Exception(sprintf('Class "%s" does not exists', $class_name));
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
        if (is_string($file_path) && file_exists($file_path) && is_readable($file_path)) {
            try {
                $content = file_get_contents($file_path, false, null, 0, 524288);
                if (is_string($content) && ! empty($content)) {
                    $result = json_decode($content, true);

                    if (json_last_error() === JSON_ERROR_NONE && is_array($result) && ! empty($result)) {
                        return $result;
                    }
                }
            } catch (Exception $e) {
                throw new FileReadingException($e->getMessage(), $e->getCode(), $e);
            }
        }

        return [];
    }

    /**
     * Возвращает класс сущности, с которой работает справочник.
     *
     * @return string
     */
    abstract protected function getReferenceEntityClassName();

    /**
     * Факторка по созданию инстансов элементов справочника.
     *
     * @param string $entity_class
     * @param array  ...$arguments
     *
     * @return ReferenceEntityInterface
     */
    protected function referenceEntityFactory($entity_class, ...$arguments)
    {
        return new $entity_class(...$arguments);
    }
}
