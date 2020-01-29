<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\References\AutoFines;

use Generator;
use InvalidArgumentException;
use Tarampampam\Wrappers\Json;
use AvtoDev\StaticReferences\References\ReferenceInterface;
use AvtoDev\StaticReferencesData\ReferencesData\StaticReference;

class AutoFines implements ReferenceInterface
{
    /**
     * @var array<string, AutoFineEntry>
     */
    protected $entities = [];

    /**
     * Create a new reference instance.
     *
     * @param StaticReference $static_reference
     *
     * @throws InvalidArgumentException
     *
     * @see \AvtoDev\StaticReferencesData\StaticReferencesData::getAutoFines()
     */
    public function __construct(StaticReference $static_reference)
    {
        foreach ((array) $static_reference->getData(true) as $datum) {
            if (! $this->validateRawEntry($datum)) {
                throw new InvalidArgumentException('Wrong reference element passed: ' . Json::encode($datum));
            }

            $this->entities[$this->clearArticleValue($datum['article'])] = new AutoFineEntry(
                $datum['article'], $datum['description'] ?? null
            );
        }
    }

    /**
     * Validate raw data entry.
     *
     * @param mixed $entry
     *
     * @return bool
     */
    protected function validateRawEntry($entry): bool
    {
        return \is_array($entry) && \array_key_exists('article', $entry) && \is_string($entry['article']);
    }

    /**
     * @return Generator<AutoFineEntry>|AutoFineEntry[]
     */
    public function getIterator(): Generator
    {
        foreach ($this->entities as $item) {
            yield $item;
        }
    }

    /**
     * @return AutoFineEntry[]
     */
    public function all(): array
    {
        return \array_values($this->entities);
    }

    /**
     * Get fine entry by number.
     *
     * @param string $article
     *
     * @return AutoFineEntry|null
     */
    public function getByArticle(string $article): ?AutoFineEntry
    {
        return $this->entities[$this->clearArticleValue($article)] ?? null;
    }

    /**
     * Check for fine exists by article number.
     *
     * @param string $article
     *
     * @return bool
     */
    public function hasArticle(string $article): bool
    {
        return isset($this->entities[$this->clearArticleValue($article)]);
    }

    /**
     * Make article value transformation for better searching.
     *
     * Samples:
     *  - `12.31.1 Ч.5` -> `12.31.1.5`
     *  - `12.31 Ч.5` -> `12.31.5`
     *  - `12.31 Часть 5` -> `12.31.5`
     *  - `12.31 part 5` -> `12.31.5`
     *  - `12.1` -> `12.1`
     *  - `12.1   ` -> `12.1`
     *
     * @param string $value
     *
     * @return string
     */
    protected function clearArticleValue(string $value): string
    {
        // Replace any chars except numbers to dots (`.`) + make dots trimming
        $value = \trim((string) \preg_replace('/[\D]/', '.', $value), '.');

        // Множественные точки - заменяем на одинарные
        $value = \preg_replace('/\.+/', '.', $value);

        // И возвращаем значение
        return (string) $value;
    }

    /**
     * {@inheritDoc}
     */
    public function count(): int
    {
        return \count($this->entities);
    }
}
