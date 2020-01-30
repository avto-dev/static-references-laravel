<?php

declare(strict_types = 1);

namespace AvtoDev\StaticReferences\References\Entities;

use Tarampampam\Wrappers\Json;

class CadastralArea implements EntityInterface
{
    /**
     * @var int
     */
    protected $code;

    /**
     * @var string
     */
    protected $name;

    /**
     * Create a new entity instance.
     *
     * @param int    $code
     * @param string $name
     */
    public function __construct(int $code, string $name)
    {
        $this->code = $code;
        $this->name = $name;
    }

    /**
     * Get cadastral area code.
     *
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * Get cadastral area name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get the instance as an array.
     *
     * @return array{code:int, name:string}
     */
    public function toArray(): array
    {
        return [
            'code' => $this->code,
            'name' => $this->name,
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
