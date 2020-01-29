<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\References;

use Generator;
use AvtoDev\StaticReferences\References\Entities\AutoFine;
use AvtoDev\StaticReferencesData\ReferencesData\StaticReference;

class AutoFines implements ReferenceInterface
{
    /**
     * @var array<string, AutoFine>
     */
    protected $entities = [];

    /**
     * Create a new reference instance.
     *
     * @param StaticReference $static_reference
     *
     * @see \AvtoDev\StaticReferencesData\StaticReferencesData::getAutoFines()
     */
    public function __construct(StaticReference $static_reference)
    {
        foreach ((array) $static_reference->getData(true) as $datum) {
            $this->entities[$this->clearArticleValue($datum['article'])] = new AutoFine(
                $datum['article'], $datum['description']
            );
        }
    }

    /**
     * @return Generator<AutoFine>|AutoFine[]
     */
    public function getIterator(): Generator
    {
        foreach ($this->entities as $item) {
            yield $item;
        }
    }

    /**
     * @return array[]
     */
    public function toArray(): array
    {
        return \array_map(static function (AutoFine $e) {
            return $e->toArray();
        }, $this->entities);
    }

    /**
     * Get fine entry by number.
     *
     * @param string $article
     *
     * @return AutoFine|null
     */
    public function getByArticle(string $article): ?AutoFine
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
     * {@inheritdoc}
     */
    public function count(): int
    {
        return \count($this->entities);
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

        // Multiple dots replace with single
        return (string) \preg_replace('/\.+/', '.', $value);
    }
}
