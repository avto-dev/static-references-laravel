<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\References\Entities;

use Tarampampam\Wrappers\Json;

class VehicleCategory implements EntityInterface
{
    /**
     * @var string
     */
    protected $code;

    /**
     * @var string
     */
    protected $description;

    /**
     * Create a new entity instance.
     *
     * @param string $code
     * @param string $description
     */
    public function __construct(string $code, string $description)
    {
        $this->code        = $code;
        $this->description = $description;
    }

    /**
     * Get category code.
     *
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * Get category description.
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
     * @return array{code:string, description:string}
     */
    public function toArray(): array
    {
        return [
            'code'        => $this->code,
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
