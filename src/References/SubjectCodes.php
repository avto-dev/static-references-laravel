<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\References;

use Generator;
use AvtoDev\StaticReferences\References\Entities\SubjectCodesInfo;
use AvtoDev\StaticReferencesData\ReferencesData\StaticReferenceInterface;

class SubjectCodes implements ReferenceInterface
{
    /**
     * Key is subject code (positive integer value).
     *
     * @var array<int, SubjectCodesInfo>
     */
    protected $entities = [];

    /**
     * @var array<int, SubjectCodesInfo>
     */
    protected $gibdd_codes_idx = [];

    /**
     * Create a new reference instance.
     *
     * @param StaticReferenceInterface $static_reference
     *
     * @see \AvtoDev\StaticReferencesData\StaticReferencesData::subjectCodes()
     */
    public function __construct(StaticReferenceInterface $static_reference)
    {
        foreach ((array) $static_reference->getData(true) as $datum) {
            $code = $datum['code'];

            $this->entities[$code] = new SubjectCodesInfo(
                $code,
                $datum['title'],
                $datum['gibdd'],
                $datum['code_iso_31662']
            );

            // burn gibdd codex index
            foreach ($datum['gibdd'] as $auto_code) {
                $this->gibdd_codes_idx[$auto_code] = &$this->entities[$code];
            }
        }
    }

    /**
     * @return Generator<SubjectCodesInfo>|SubjectCodesInfo[]
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
        return \array_map(static function (SubjectCodesInfo $e): array {
            return $e->toArray();
        }, \array_values($this->entities));
    }

    /**
     * Get subject codes info by subject code.
     *
     * @param int $subject_code
     *
     * @return SubjectCodesInfo|null
     */
    public function getBySubjectCode(int $subject_code): ?SubjectCodesInfo
    {
        return $this->entities[$subject_code] ?? null;
    }

    /**
     * Check for subject codes info exists by subject code.
     *
     * @param int $subject_code
     *
     * @return bool
     */
    public function hasSubjectCode(int $subject_code): bool
    {
        return isset($this->entities[$subject_code]);
    }

    /**
     * Get subject codes info by GIBDD code.
     *
     * @param int $gibdd_code
     *
     * @return SubjectCodesInfo|null
     */
    public function getByGibddCode(int $gibdd_code): ?SubjectCodesInfo
    {
        return $this->gibdd_codes_idx[$gibdd_code] ?? null;
    }

    /**
     * Check for subject codes info exists by GIBDD code.
     *
     * @param int $gibdd_code
     *
     * @return bool
     */
    public function hasGibddCode(int $gibdd_code): bool
    {
        return isset($this->gibdd_codes_idx[$gibdd_code]);
    }

    /**
     * {@inheritdoc}
     */
    public function count(): int
    {
        return \count($this->entities);
    }
}
