<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\References;

use Generator;
use AvtoDev\StaticReferencesData\ReferencesData\StaticReference;
use AvtoDev\StaticReferences\References\Entities\RegistrationAction;

class RegistrationActions implements ReferenceInterface
{
    /**
     * @var array<int, RegistrationAction>
     */
    protected $entities = [];

    /**
     * @var array<int, RegistrationAction>
     */
    protected $codes_idx = [];

    /**
     * Create a new reference instance.
     *
     * @param StaticReference $static_reference
     *
     * @see \AvtoDev\StaticReferencesData\StaticReferencesData::getRegistrationActions()
     */
    public function __construct(StaticReference $static_reference)
    {
        $counter = 0;

        foreach ((array) $static_reference->getData(true) as $datum) {
            $this->entities[$counter] = new RegistrationAction($datum['codes'], $datum['description']);

            // burn codes index
            foreach ($datum['codes'] as $code) {
                $this->codes_idx[$code] = &$this->entities[$counter];
            }

            $counter++;
        }
    }

    /**
     * @return Generator<RegistrationAction>|RegistrationAction[]
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
        return \array_map(static function (RegistrationAction $e) {
            return $e->toArray();
        }, $this->entities);
    }

    /**
     * Get registration action entity by code.
     *
     * @param int $reg_action_code
     *
     * @return RegistrationAction|null
     */
    public function getByCode(int $reg_action_code): ?RegistrationAction
    {
        return $this->codes_idx[$reg_action_code] ?? null;
    }

    /**
     * Check for registration action exists by code.
     *
     * @param int $reg_action_code
     *
     * @return bool
     */
    public function hasCode(int $reg_action_code): bool
    {
        return isset($this->codes_idx[$reg_action_code]);
    }

    /**
     * {@inheritdoc}
     */
    public function count(): int
    {
        return \count($this->entities);
    }
}
