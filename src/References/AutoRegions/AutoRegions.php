<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\References\AutoRegions;

use Generator;
use InvalidArgumentException;
use Tarampampam\Wrappers\Json;
use AvtoDev\StaticReferences\References\ReferenceInterface;
use AvtoDev\StaticReferencesData\ReferencesData\StaticReference;

class AutoRegions implements ReferenceInterface
{
    /**
     * Key is region code (positive integer value).
     *
     * @var array<int, AutoRegionEntry>
     */
    protected $entities = [];

    /**
     * @var array<int, AutoRegionEntry>
     */
    protected $auto_codes_idx = [];

    /**
     * Create a new reference instance.
     *
     * @param StaticReference $static_reference
     *
     * @throws InvalidArgumentException
     *
     * @see \AvtoDev\StaticReferencesData\StaticReferencesData::getAutoRegions()
     */
    public function __construct(StaticReference $static_reference)
    {
        foreach ((array) $static_reference->getData(true) as $datum) {
            if (! $this->validateRawEntry($datum)) {
                throw new InvalidArgumentException('Wrong reference element passed: ' . Json::encode($datum));
            }

            $code = $datum['code'];

            $this->entities[$code] = new AutoRegionEntry(
                $code,
                $datum['title'] ?? null,
                $datum['short'] ?? null,
                $auto_codes = ($datum['gibdd'] ?? null),
                $datum['okato'] ?? null,
                $datum['code_iso_31662'] ?? null,
                $datum['type'] ?? null
            );

            // burn gibdd codex index
            foreach ($auto_codes ?? [] as $auto_code) {
                $this->auto_codes_idx[$auto_code] =&$this->entities[$code];
            }
        }
    }

    /**
     * @return Generator<AutoRegionEntry>|AutoRegionEntry[]
     */
    public function getIterator(): Generator
    {
        foreach ($this->entities as $item) {
            yield $item;
        }
    }

    /**
     * @return AutoRegionEntry[]
     */
    public function all(): array
    {
        return \array_values($this->entities);
    }

    /**
     * Get region entry by own code.
     *
     * @param int $region_code
     *
     * @return AutoRegionEntry|null
     */
    public function getByRegionCode(int $region_code): ?AutoRegionEntry
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
     * @return AutoRegionEntry|null
     */
    public function getByAutoCode(int $auto_code): ?AutoRegionEntry
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

    /**
     * Validate raw data entry.
     *
     * @param mixed $entry
     *
     * @return bool
     */
    protected function validateRawEntry($entry): bool
    {
        // Entry must be an array with 'code' key
        if (\is_array($entry) && \array_key_exists('code', $entry) && \is_int($entry['code'])) {
            // If 'gibdd' key exists - it must be an array
            if (\array_key_exists('gibdd', $entry) && ! \is_array($entry['gibdd'])) {
                return false;
            }

            // If 'code_iso_31662' key exists - it must be string
            if (\array_key_exists('code_iso_31662', $entry) && ! \is_string($entry['code_iso_31662'])) {
                return false;
            }

            return true;
        }

        return false;
    }
}
