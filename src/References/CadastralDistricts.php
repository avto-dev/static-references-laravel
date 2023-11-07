<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\References;

use Generator;
use AvtoDev\StaticReferences\References\Entities\CadastralArea;
use AvtoDev\StaticReferences\References\Entities\CadastralDistrict;
use AvtoDev\StaticReferencesData\ReferencesData\StaticReferenceInterface;

class CadastralDistricts implements ReferenceInterface
{
    /**
     * @var array<int, CadastralDistrict>
     */
    protected $entities = [];

    /**
     * Create a new reference instance.
     *
     * @param StaticReferenceInterface $static_reference
     *
     * @see \AvtoDev\StaticReferencesData\StaticReferencesData::cadastralDistricts()
     */
    public function __construct(StaticReferenceInterface $static_reference)
    {
        foreach ((array) $static_reference->getData(true) as $datum) {
            /** @var int $code */
            $code  = $datum['code'];
            $areas = [];

            foreach ($datum['areas'] as $area_info) {
                $areas[] = new CadastralArea($area_info['code'], $area_info['name']);
            }

            $this->entities[$code] = new CadastralDistrict($code, $datum['name'], $areas);
        }
    }

    /**
     * @return Generator<CadastralDistrict>
     */
    public function getIterator(): Generator
    {
        foreach ($this->entities as $item) {
            yield $item;
        }
    }

    /**
     * @return array<array-key, array<string, mixed>>
     */
    public function toArray(): array
    {
        return \array_map(static function (CadastralDistrict $e): array {
            return $e->toArray();
        }, \array_values($this->entities));
    }

    /**
     * Get cadastral district by code.
     *
     * @param int $code
     *
     * @return CadastralDistrict|null
     */
    public function getByCode(int $code): ?CadastralDistrict
    {
        return $this->entities[$code] ?? null;
    }

    /**
     * Cadastral district by code is exists?
     *
     * @param int $code
     *
     * @return bool
     */
    public function hasCode(int $code): bool
    {
        return isset($this->entities[$code]);
    }

    /**
     * {@inheritdoc}
     */
    public function count(): int
    {
        return \count($this->entities);
    }
}
