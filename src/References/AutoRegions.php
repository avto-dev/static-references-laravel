<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\References;

use Generator;
use AvtoDev\StaticReferences\References\Entities\AutoRegion;
use AvtoDev\StaticReferencesData\ReferencesData\StaticReference;

class AutoRegions implements ReferenceInterface
{
    /**
     * Key is region code (positive integer value).
     *
     * @var array<int, AutoRegion>
     */
    protected $entities = [];

    /**
     * @var array<int, AutoRegion>
     */
    protected $auto_codes_idx = [];

    /**
     * Create a new reference instance.
     *
     * @param StaticReference $static_reference
     *
     * @see \AvtoDev\StaticReferencesData\StaticReferencesData::getAutoRegions()
     */
    public function __construct(StaticReference $static_reference)
    {
        foreach ((array) $static_reference->getData(true) as $datum) {
            $code = $datum['code'];

            $this->entities[$code] = new AutoRegion(
                $code,
                $datum['title'],
                $datum['short'],
                $datum['gibdd'],
                $datum['okato'],
                $datum['code_iso_31662'],
                $datum['type']
            );

            // burn gibdd codex index
            foreach ($datum['gibdd'] as $auto_code) {
                $this->auto_codes_idx[$auto_code] = &$this->entities[$code];
            }
        }
    }

    /**
     * @return Generator<AutoRegion>|AutoRegion[]
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
        return \array_map(static function (AutoRegion $e) {
            return $e->toArray();
        }, $this->entities);
    }

    /**
     * Get region entry by own code.
     *
     * @param int $region_code
     *
     * @return AutoRegion|null
     */
    public function getByRegionCode(int $region_code): ?AutoRegion
    {
        return $this->entities[$region_code] ?? null;
    }

    /**
     * Check for region entry exists by own code.
     *
     * @param int $region_code
     *
     * @return bool
     */
    public function hasRegionCode(int $region_code): bool
    {
        return isset($this->entities[$region_code]);
    }

    /**
     * Get region entry by auto (gibdd) code.
     *
     * @param int $auto_code
     *
     * @return AutoRegion|null
     */
    public function getByAutoCode(int $auto_code): ?AutoRegion
    {
        return $this->auto_codes_idx[$auto_code] ?? null;
    }

    /**
     * Check for region entry exists by auto (gibdd) code.
     *
     * @param int $auto_code
     *
     * @return bool
     */
    public function hasAutoCode(int $auto_code): bool
    {
        return isset($this->auto_codes_idx[$auto_code]);
    }

    /**
     * {@inheritdoc}
     */
    public function count(): int
    {
        return \count($this->entities);
    }
}
