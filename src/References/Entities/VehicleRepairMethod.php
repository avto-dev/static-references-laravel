<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\References\Entities;

class VehicleRepairMethod implements EntityInterface
{
    /**
     * @var string[]
     */
    protected $codes;

    /**
     * @var string
     */
    protected $description;

    /**
     * Create a new entity instance.
     *
     * @param string[] $codes
     * @param string   $description
     */
    public function __construct(array $codes, string $description)
    {
        $this->codes       = $codes;
        $this->description = $description;
    }

    /**
     * Get repair method codes.
     *
     * @return string[]
     */
    public function getCodes(): array
    {
        return $this->codes;
    }

    /**
     * Get repair method description.
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Get the instance as an array.
     *
     * @return array{codes:array, description:string}
     */
    public function toArray(): array
    {
        return [
            'codes'       => $this->codes,
            'description' => $this->description,
        ];
    }
}
