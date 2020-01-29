<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\References\Entities;

use Tarampampam\Wrappers\Json;

class RegistrationAction implements EntityInterface
{
    /**
     * @var int[]
     */
    protected $codes;

    /**
     * @var string
     */
    protected $description;

    /**
     * Create a new entity instance.
     *
     * @param int[]  $codes
     * @param string $description
     */
    public function __construct(array $codes, string $description)
    {
        $this->codes       = $codes;
        $this->description = $description;
    }

    /**
     * Get registration action codes.
     *
     * @return int[]
     */
    public function getCodes(): array
    {
        return $this->codes;
    }

    /**
     * Get registration action description.
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

    /**
     * {@inheritdoc}
     */
    public function toJson($options = 0): string
    {
        return (string) Json::encode($this->toArray(), $options);
    }
}
